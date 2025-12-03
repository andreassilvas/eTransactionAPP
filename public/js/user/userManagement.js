// Controller: DataTable init + events + inline edit
document.addEventListener("DOMContentLoaded", () => {
  const { list, store, update, remove } = window.UserAPI;
  const { input, select, actionBtns, editBtns } = window.UserAction;
  const { listProvinces, listCitiesByProvince } = window.GeoAPI;

  // province caches (controller state)
  let PROVINCES = []; // [{ code, name }]
  let PROV_BY_NAME = {}; // name -> code
  let NAME_BY_CODE = {}; // code -> name

  // Single source of truth for columns/validation
  const FIELDS = [
    { key: "id", type: "readonly" },
    { key: "name", type: "text", required: "create+update" },
    { key: "lastname", type: "text", required: "create+update" },
    { key: "phone", type: "text", required: "create+update", maxLength: 14 },
    { key: "extention", type: "text", required: "optional", maxLength: 5 },
    { key: "email", type: "email", required: "create+update" },
    { key: "address", type: "text", required: "create+update" },
    { key: "city", type: "select-city", required: "create+update" },
    { key: "province", type: "select-province", required: "create+update" },
    { key: "postcode", type: "text", required: "create+update", maxLength: 7 },

    {
      key: "password",
      type: "password",
      required: "createOnly",
      maxLength: 4,
    },
  ];

  // Table column index map (to avoid magic numbers)
  const colIndex = FIELDS.reduce((acc, f, i) => {
    acc[f.key] = i;
    return acc;
  }, {});

  colIndex.actions = FIELDS.length; // action column at the end

  (async function init() {
    // Load provinces once for row editors
    const provRows = await listProvinces(); // [{id, code, name}]
    PROVINCES = provRows
      .map((p) => ({ code: (p.code || "").toUpperCase(), name: p.name }))
      .sort((a, b) => a.code.localeCompare(b.code)); // show by code

    PROV_BY_NAME = {};
    NAME_BY_CODE = {};
    PROVINCES.forEach((p) => {
      PROV_BY_NAME[p.name] = p.code;
      NAME_BY_CODE[p.code] = p.name;
    });

    // Load rows
    const rows = await list();

    // Ensure password masked in the grid (server should already do this, but just in case)
    rows.forEach((r) => {
      if ("password" in r) r.password = "••••••";
    });

    const table = $("#tbl").DataTable({
      data: rows,
      columns: [
        { data: "id" },
        { data: "name" },
        { data: "lastname" },
        { data: "phone" },
        { data: "extention" },
        { data: "email" },
        { data: "address" },
        { data: "city" },
        { data: "province" },
        { data: "postcode" },
        { data: "password" }, // masked; not prefilled for edit
        { data: null, orderable: false, render: (_, __, r) => actionBtns(r) },
      ],
    });

    let editingTr = null;
    let createMode = false;

    // Build required keys for Validation from FIELDS
    function requiredKeysFor(isCreate) {
      return FIELDS.filter(
        (f) =>
          f.required === "create+update" ||
          (isCreate && f.required === "createOnly")
      ).map((f) => f.key);
    }

    function setCellEditor(td, field, row) {
      const val = row[field.key] ?? "";

      if (field.type === "readonly") {
        td.textContent = val;
        return;
      }

      // Password always blank on edit
      if (field.key === "password") {
        td.innerHTML = input("", "text", "password", {
          maxLength: field.maxLength,
        });
        return;
      }

      if (field.type === "select-province") {
        // row.province might be a NAME (old data) or a CODE (new). Normalize to CODE.
        const currentRaw = row.province || "";
        const currentCode = NAME_BY_CODE[currentRaw]
          ? currentRaw
          : PROV_BY_NAME[currentRaw] || currentRaw;
        const options = PROVINCES.map((p) => ({
          value: p.code,
          label: p.code,
        })); // show CODE
        td.innerHTML = select("province", options, currentCode, {
          placeholder: "Select",
          disabled: false,
        });

        const provSel = td.querySelector('select[name="province"]');
        provSel.classList.add("dt-inline"); // ensure validation picks it up

        //validate province right away so it gets is-valid/is-invalid immediately
        if (window.Validation) {
          window.Validation.validateFieldByName(provSel);
        }

        // province change => reload cities list for this row
        provSel.addEventListener("change", async () => {
          const tr = td.closest("tr");
          const tds = tr.querySelectorAll("td");
          const provCode = provSel.value;

          const cityCell = tds[colIndex.city];
          if (!provCode) {
            cityCell.innerHTML = select("city", [], "", {
              placeholder: "— Select a province first —",
              disabled: false,
            });
          } else {
            const res = await listCitiesByProvince(provCode);
            const cityOptions = (res.items || []).map((c) => ({
              value: c.name,
              label: c.name,
            }));
            cityCell.innerHTML = select("city", cityOptions, "", {
              placeholder: "— Select a province first —",
              disabled: false,
            });
          }

          const citySel = tds[colIndex.city].querySelector(
            'select[name="city"]'
          );
          if (window.Validation) {
            window.Validation.validateFieldByName(provSel);
            const citySel = td
              .closest("tr")
              .querySelectorAll("td")
              [colIndex.city].querySelector('select[name="city"]');
            if (citySel) window.Validation.validateFieldByName(citySel);
          }
        });
        return;
      }

      if (field.type === "select-city") {
        // find province code in this row (may be name or code previously)
        const provRaw = row.province || "";
        const provCode = NAME_BY_CODE[provRaw]
          ? provRaw
          : PROV_BY_NAME[provRaw] || provRaw;

        if (!provCode) {
          // keep current city so it doesn't look blank/invalid if user didn't touch province
          const currentCity = row.city || "";
          const opts = currentCity
            ? [{ value: currentCity, label: currentCity }]
            : [];
          td.innerHTML = select("city", opts, currentCity, {
            placeholder: "Select a province first",
            disabled: false,
          });
          const citySel = td.querySelector('select[name="city"]');
          citySel.classList.add("dt-inline");
          return;
        }

        // async load cities for current province
        td.innerHTML = select("city", [], "", {
          placeholder: "Loading…",
          disabled: false,
        });
        (async () => {
          const res = await listCitiesByProvince(provCode);
          const cityOptions = (res.items || []).map((c) => ({
            value: c.name,
            label: c.name,
          }));
          td.innerHTML = select("city", cityOptions, row.city || "", {
            placeholder: "Select",
            disabled: false,
          });
          const citySel = td.querySelector('select[name="city"]');
          citySel.classList.add("dt-inline");
          if (window.Validation) window.Validation.validateFieldByName(citySel);
        })();
        return;
      }

      // default input
      td.innerHTML = input(val, field.type, field.key, {
        maxLength: field.maxLength,
      });
    }

    function toEdit(tr) {
      if (editingTr && editingTr !== tr) cancelEdit(editingTr);
      editingTr = tr;

      const data = table.row(tr).data();
      $(tr).data("orig", { ...data });

      const tds = tr.querySelectorAll("td");

      // Render editors for field columns
      FIELDS.forEach((f, i) => setCellEditor(tds[i], f, data));

      // Actions
      tds[colIndex.actions].innerHTML = editBtns();

      // Initial validation pass (marks fields)
      tr.querySelectorAll("input.dt-inline, select.dt-inline").forEach((el) => {
        if (window.Validation) window.Validation.validateFieldByName(el);
      });

      // Focus first editable
      tds[colIndex.name].querySelector("input")?.focus();
    }

    function collect(tr) {
      const tds = tr.querySelectorAll("td");
      const out = {};
      FIELDS.forEach((f, i) => {
        if (f.type === "readonly") {
          if (f.key === "id") out.id = table.row(tr).data().id;
          return;
        }
        if (f.type && f.type.startsWith("select-")) {
          const sel = tds[i].querySelector("select");
          const v = sel ? (sel.value || "").trim() : "";
          // keep existing table value if user didn’t choose anything in this select
          out[f.key] = v !== "" ? v : table.row(tr).data()[f.key] ?? "";
        } else {
          const inp = tds[i].querySelector("input");
          out[f.key] = inp
            ? (inp.value || "").trim()
            : table.row(tr).data()[f.key] ?? "";
        }
      });
      return out;
    }

    async function saveEdit(tr) {
      try {
        const current = table.row(tr).data();
        const isCreate = createMode || !current?.id;

        if (window.Validation) {
          const ok = window.Validation.validateRow(tr, {
            requiredKeys: requiredKeysFor(isCreate),
          });
          if (!ok) return;
        }

        const payload = collect(tr);

        // do not overwrite password unless user typed one
        if (!isCreate && (!payload.password || payload.password === "")) {
          delete payload.password;
        }

        if (isCreate) {
          const res = await store(payload);
          payload.id = res.id;
          createMode = false;
        } else {
          await update(payload);
        }

        payload.password = "••••••"; // mask in table
        table.row(tr).data(payload);
        tr.querySelectorAll("td")[colIndex.actions].innerHTML =
          actionBtns(payload);
        table.row(tr).invalidate().draw(false);
        editingTr = null;
      } catch (err) {
        console.error("[saveEdit] error:", err);
        alert(
          err && err.message && err.message.includes("409")
            ? "Email already exists"
            : (err && err.message) || "Une erreur est survenue"
        );
      }
    }

    function cancelEdit(tr) {
      const orig = $(tr).data("orig");
      if (createMode && (!orig || !orig.id)) {
        table.row(tr).remove().draw(false);
        createMode = false;
      } else if (orig) {
        table.row(tr).data(orig).invalidate().draw(false);
      }
      editingTr = null;
    }

    // Delegated actions on tbody (works with DataTables redraws)
    const $tbody = $("#tbl tbody");

    $tbody.on("click", "button.btn-edit", function (e) {
      e.preventDefault();
      e.stopPropagation();
      toEdit($(this).closest("tr").get(0));
    });

    $tbody.on("click", "button.btn-save", async function (e) {
      e.preventDefault();
      e.stopPropagation();
      await saveEdit($(this).closest("tr").get(0));
    });

    $tbody.on("click", "button.btn-cancel", function (e) {
      e.preventDefault();
      e.stopPropagation();
      cancelEdit($(this).closest("tr").get(0));
    });

    $tbody.on("click", "button.btn-del", async function (e) {
      e.preventDefault();
      e.stopPropagation();
      const id = this.dataset.id;
      if (confirm("Supprimer ce client ?")) {
        await remove(id);
        table.row($(this).closest("tr")).remove().draw(false);
      }
    });

    // live validation (inputs + selects)
    document.addEventListener("input", (e) => {
      const el = e.target;
      if (!(el instanceof HTMLInputElement)) return;
      if (!el.classList.contains("dt-inline")) return;
      if (window.Validation) window.Validation.validateFieldByName(el);
    });

    document.addEventListener("change", (e) => {
      const el = e.target;
      if (!(el instanceof HTMLSelectElement)) return;
      if (!el.classList.contains("dt-inline")) return;
      if (window.Validation) window.Validation.validateFieldByName(el);
    });

    // add row
    const addBtn = document.getElementById("ajouterUtilisateur");
    if (addBtn) {
      addBtn.addEventListener("click", (e) => {
        e.preventDefault();
        if (editingTr) cancelEdit(editingTr);

        const blank = FIELDS.reduce((acc, f) => ((acc[f.key] = ""), acc), {});
        const tr = table.row.add(blank).draw(false).node();
        $(tr).data("orig", { ...blank });
        createMode = true;
        toEdit(tr);
      });
    }
  })().catch((err) => {
    console.error(err);
    alert("Init failed. See console.");
  });
});
