// Controller: DataTable init + events + inline edit
document.addEventListener("DOMContentLoaded", () => {
  const { list, store, update, remove } = window.ProductAPI;
  const { input, select, actionBtns, editBtns } = window.ProductAction;

  // Single source of truth for columns/validation
  const FIELDS = [
    { key: "id", type: "readonly" },
    { key: "name", type: "text", required: "create+update" },
    { key: "category", type: "select-category", required: "create+update" },
    { key: "brand", type: "select-brand", required: "create+update" },
    { key: "model", type: "text", required: "create+update" },
    { key: "specs_desc", type: "text", required: "create+update" },
    { key: "price", type: "text", required: "create+update" },
    { key: "stock", type: "text", required: "create+update" },
    {
      key: "warranty_period",
      type: "select-warranty_period",
      required: "create+update",
    },
    {
      key: "support_level",
      type: "select-support_level",
      required: "create+update",
    },
    { key: "supplier", type: "select-supplier", required: "create+update" },
  ];

  // Table column index map (to avoid magic numbers)
  const colIndex = FIELDS.reduce((acc, f, i) => {
    acc[f.key] = i;
    return acc;
  }, {});

  colIndex.actions = FIELDS.length; // action column at the end
  const OPTIONS = {
    category: [],
    brand: [],
    supplier: [],
    support_level: [],
    warranty_period: [],
  };

  async function loadAllOptions() {
    OPTIONS.category = await window.ProductAPI.loadOptions("category");
    OPTIONS.brand = await window.ProductAPI.loadOptions("brand");
    OPTIONS.supplier = await window.ProductAPI.loadOptions("supplier");
    OPTIONS.support_level = await window.ProductAPI.loadOptions(
      "support_level"
    );
    OPTIONS.warranty_period = await window.ProductAPI.loadOptions(
      "warranty_period"
    );
  }

  (async function init() {
    // Load select options
    await loadAllOptions();
    // Load rows
    const rows = await list();

    const table = $("#tbl").DataTable({
      data: rows,
      deferRender: true,
      processing: true,
      pageLength: 10,
      columns: [
        { data: "id" },
        { data: "name" },
        { data: "category" },
        { data: "brand" },
        { data: "model" },
        { data: "specs_desc" },
        {
          data: "price",
          className: "text-end",
          render: function (data) {
            if (!data) return "";
            const formatted = parseFloat(data)
              .toFixed(2)
              .replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            return formatted + " $";
          },
        },
        { data: "stock", className: "text-center" },
        { data: "warranty_period", className: "text-center" },
        { data: "support_level", className: "text-center" },
        { data: "supplier" },
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

      // readonly fields
      if (field.type === "readonly") {
        td.textContent = val;
        return;
      }

      // select fields
      if (field.type.startsWith("select-")) {
        const name = field.key;
        const opts = OPTIONS[name] || [];
        td.innerHTML = select(
          name,
          opts.map((o) => ({ value: o, label: o })), // <-- conversion
          val,
          { placeholder: "-- choose --" }
        );
        return;
      }

      // default: input
      td.innerHTML = input(val, "text", field.key);
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
      const numericFields = ["price", "stock"];
      const tds = tr.querySelectorAll("td");
      const out = {};
      FIELDS.forEach((f, i) => {
        if (f.type === "readonly") {
          if (f.key === "id") out.id = table.row(tr).data().id;
          return;
        }
        let val;
        if (f.type.startsWith("select-")) {
          const sel = tds[i].querySelector("select");
          val = sel ? sel.value.trim() : table.row(tr).data()[f.key] ?? "";
        } else {
          const inp = tds[i].querySelector("input");
          val = inp ? inp.value.trim() : table.row(tr).data()[f.key] ?? "";
        }
        if (numericFields.includes(f.key)) val = parseFloat(val) || 0;
        out[f.key] = val;
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

        let payload = collect(tr); // let, so we can overwrite

        if (isCreate) {
          const res = await store(payload); // server returns saved product object
          if (!res || !res.id) {
            alert("Failed to add product");
            return;
          }
          payload = res; // now payload has the real data from server
          createMode = false;
        } else {
          const res = await update(payload); // <-- get server response
          if (!res || !res.success) {
            alert("Failed to update product");
            return;
          }
        }

        // Update the row with the server response
        table.row(tr).data(payload).invalidate().draw(false);
        editingTr = null;

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
      if (confirm("Supprimer ce produit ?")) {
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
    const addBtn = document.getElementById("ajouterProduit");
    if (addBtn) {
      addBtn.addEventListener("click", (e) => {
        e.preventDefault();
        if (editingTr) cancelEdit(editingTr);

        const blank = FIELDS.reduce((acc, f) => ((acc[f.key] = ""), acc), {});
        const rowNode = table.row.add(blank).draw(false).node();

        // Move row to the top
        $(rowNode).prependTo("#tbl tbody");

        $(rowNode).data("orig", { ...blank });
        createMode = true;
        toEdit(rowNode);
      });
    }
  })().catch((err) => {
    console.error(err);
    alert("Init failed. See console.");
  });
});
