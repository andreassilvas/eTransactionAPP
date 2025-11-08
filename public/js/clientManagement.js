document.addEventListener("DOMContentLoaded", () => {
  const API = window.API || "/gestion-utilisateurs";

  // helpers
  const esc = (s) =>
    String(s ?? "").replace(
      /[&<>"']/g,
      (m) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[m])
    );

  const colIndex = {
    id: 0,
    name: 1,
    lastname: 2,
    phone: 3,
    extention: 4,
    email: 5,
    address: 6,
    city: 7,
    province: 8,
    postcode: 9,
    password: 10,
    actions: 11,
  };

  // server JSON fetch
  const jsonFetch = async (u, o) => {
    const resp = await fetch(u, o);

    if (!resp.ok) throw new Error(`HTTP ${resp.status} ${u}`);
    const contentType = resp.headers.get("content-type") || "";
    const txt = await resp.text();

    if (!contentType.includes("application/json"))
      throw new Error("Not JSON: " + txt.slice(0, 200));
    return JSON.parse(txt);
  };

  const input = (val, type = "text") =>
    `<input class="form-control form-control-sm dt-inline" type="${type}" value="${esc(
      val
    )}">`;

  const actionBtns = (row) => `
    <div class="d-grid gap-2 d-md-block">
      <button class="btn btn-sm btn-edit custom-btn-bg" data-id="${row.id}"><i class="fa-solid fa-pen custom-edit-icon"></i></button>
      <button class="btn btn-sm btn-del custom-btn-bg" data-id="${row.id}"><i class="fa-solid fa-trash custom-edit-icon"></i></button>
    </div>`;

  const editBtns = () => `
    <div class="d-grid gap-2 d-md-block">
      <button class="btn btn-sm btn-save custom-btn-bg"><i class="fa-solid fa-check custom-edit-icon"></i></button>
      <button class="btn btn-sm btn-cancel custom-btn-bg"><i class="fa-solid fa-xmark custom-edit-icon"></i></button>
    </div>`;

  // 1) init table
  (async function init() {
    const rows = await jsonFetch(`${API}/list`);
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
        { data: "password" },
        { data: null, orderable: false, render: (_, __, r) => actionBtns(r) },
      ],
    });

    let editingTr = null; // currently edited <tr>
    let createMode = false; // true when adding a new blank row

    // turn a row into inline edit
    function toEdit(tr) {
      if (editingTr && editingTr !== tr) cancelEdit(editingTr);
      editingTr = tr;

      const data = table.row(tr).data();
      $(tr).data("orig", { ...data }); // keep copy

      const tds = tr.querySelectorAll("td");

      tds[colIndex.name].innerHTML = input(data.name);
      tds[colIndex.lastname].innerHTML = input(data.lastname);
      tds[colIndex.phone].innerHTML = input(data.phone);
      tds[colIndex.extention].innerHTML = input(data.extention);
      tds[colIndex.email].innerHTML = input(data.email, "email");
      tds[colIndex.address].innerHTML = input(data.address);
      tds[colIndex.city].innerHTML = input(data.city);
      tds[colIndex.province].innerHTML = input(data.province);
      tds[colIndex.postcode].innerHTML = input(data.postcode);
      tds[colIndex.password].innerHTML = input(data.password);
      tds[colIndex.actions].innerHTML = editBtns();
      // focus first input
      tds[colIndex.name].querySelector("input")?.focus();
    }

    function collect(tr) {
      const tds = tr.querySelectorAll("td");
      return {
        id: table.row(tr).data().id,
        name: tds[colIndex.name].querySelector("input").value.trim(),
        lastname: tds[colIndex.lastname].querySelector("input").value.trim(),
        phone: tds[colIndex.phone].querySelector("input").value.trim(),
        extention: tds[colIndex.extention].querySelector("input").value.trim(),
        email: tds[colIndex.email].querySelector("input").value.trim(),
        address: tds[colIndex.address].querySelector("input").value.trim(),
        city: tds[colIndex.city].querySelector("input").value.trim(),
        province: tds[colIndex.province].querySelector("input").value.trim(),
        postcode: tds[colIndex.postcode].querySelector("input").value.trim(),
        password: tds[colIndex.password].querySelector("input").value.trim(),
      };
    }

    const emailRe = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

    async function saveEdit(tr) {
      const payload = collect(tr);
      if (!payload.name || !payload.lastname || !emailRe.test(payload.email)) {
        alert("Prénom/Nom requis et courriel valide.");
        return;
      }

      if (createMode || !payload.id) {
        // CREATE
        const res = await jsonFetch(`${API}/store`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });

        payload.id = res.id;
        createMode = false;
      } else {
        // UPDATE
        await jsonFetch(`${API}/update`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });
      }

      // redraw row with plain text + action buttons
      table.row(tr).data(payload);
      tr.querySelectorAll("td")[colIndex.actions].innerHTML =
        actionBtns(payload);
      table.row(tr).invalidate().draw(false);
      editingTr = null;
    }

    function cancelEdit(tr) {
      const orig = $(tr).data("orig");
      if (createMode && (!orig || !orig.id)) {
        // cancel brand-new row
        table.row(tr).remove().draw(false);
        createMode = false;
      } else if (orig) {
        table.row(tr).data(orig).invalidate().draw(false);
      }
      editingTr = null;
    }

    // 2) Delegated events
    document.addEventListener("click", async (e) => {
      const btn = e.target.closest("button");
      if (!btn) return;

      if (btn.classList.contains("btn-edit")) {
        const tr = btn.closest("tr");
        toEdit(tr);
      }

      if (btn.classList.contains("btn-del")) {
        const id = btn.dataset.id;
        if (confirm("Supprimer ce client ?")) {
          await jsonFetch(`${API}/delete?id=${encodeURIComponent(id)}`);
          table.row(btn.closest("tr")).remove().draw(false);
        }
      }

      if (btn.classList.contains("btn-save")) {
        await saveEdit(btn.closest("tr"));
      }

      if (btn.classList.contains("btn-cancel")) {
        cancelEdit(btn.closest("tr"));
      }
    });

    // 3) "Ajouter" button:
    const addBtn = document.getElementById("ajouterUtilisateur");
    if (addBtn) {
      addBtn.addEventListener("click", async () => {
        if (editingTr) cancelEdit(editingTr);
        const blank = {
          id: "",
          name: "",
          lastname: "",
          phone: "",
          extention: "",
          email: "",
          address: "",
          city: "",
          province: "",
          postcode: "",
          password: "",
        };
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
