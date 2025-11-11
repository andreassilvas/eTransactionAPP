// Controller: DataTable init + events + inline edit
document.addEventListener("DOMContentLoaded", () => {
  const { list, store, update, remove } = window.ClientAPI;
  const { input, actionBtns, editBtns } = window.ClientTableView;

  // Single source of truth for columns/validation
  const FIELDS = [
    { key: "id", type: "readonly" },
    { key: "name", type: "text", required: "create+update" },
    { key: "lastname", type: "text", required: "create+update" },
    { key: "phone", type: "text", required: "create+update", maxLength: 12 },
    { key: "extention", type: "text", required: "optional", maxLength: 5 },
    { key: "email", type: "email", required: "create+update" },
    { key: "address", type: "text", required: "create+update" },
    { key: "city", type: "text", required: "create+update" },
    { key: "province", type: "text", required: "create+update" }, // no maxLength
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
      tr.querySelectorAll("input.dt-inline").forEach((el) => {
        if (window.Validation) window.Validation.validateFieldByName(el);
      });

      // Focus first editable
      tds[colIndex.name].querySelector("input")?.focus();
    }

    function collect(tr) {
      const tds = tr.querySelectorAll("td");
      const out = {};
      // Read all fields from the row editors
      FIELDS.forEach((f, i) => {
        if (f.type === "readonly") {
          // keep id from original DataTables row
          if (f.key === "id") out.id = table.row(tr).data().id;
          return;
        }
        const el = tds[i].querySelector("input");
        out[f.key] = el ? el.value.trim() : table.row(tr).data()[f.key] ?? "";
      });
      return out;
    }

    async function saveEdit(tr) {
      try {
        const current = table.row(tr).data();
        const isCreate = createMode || !current?.id;

        // Validate using required keys from metadata
        if (window.Validation) {
          const ok = window.Validation.validateRow(tr, {
            requiredKeys: requiredKeysFor(isCreate),
          });
          if (!ok) return;
        }

        const payload = collect(tr);

        // On UPDATE, if password is empty, don't send it so server won't overwrite hash
        if (!isCreate && (!payload.password || payload.password === "")) {
          delete payload.password;
        }

        // Create vs Update
        if (isCreate) {
          const res = await store(payload);
          payload.id = res.id;
          createMode = false;
        } else {
          await update(payload);
        }

        // Mask password in table view
        payload.password = "••••••";

        // Redraw the row
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

    // Live validation for inline inputs
    document.addEventListener("input", (e) => {
      const el = e.target;
      if (!(el instanceof HTMLInputElement)) return;
      if (!el.classList.contains("dt-inline")) return;
      if (window.Validation) window.Validation.validateFieldByName(el);
    });

    // Add row
    const addBtn = document.getElementById("ajouterUtilisateur");
    if (addBtn) {
      addBtn.addEventListener("click", (e) => {
        e.preventDefault();
        if (editingTr) cancelEdit(editingTr);
        const blank = FIELDS.reduce((acc, f) => {
          acc[f.key] = "";
          return acc;
        }, {});
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
