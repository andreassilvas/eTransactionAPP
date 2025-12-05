// UMD-ish namespace so it works without a bundler
window.ProductAPI = (function () {
  const API = window.API || "/gestion-des-produits";

  const esc = (s) =>
    String(s ?? "").replace(
      /[&<>"']/g,
      (m) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[m])
    );

  // Server JSON fetch
  const jsonFetch = async (u, o) => {
    console.log("[jsonFetch] →", u, o); // LOG URL + options
    const resp = await fetch(u, o);
    const text = await resp.text();
    let data = null;
    try {
      data = JSON.parse(text);
    } catch {}
    console.log("[jsonFetch] ←", resp.status, data || text);
    if (!resp.ok) {
      const err = new Error(
        (data && data.error) || text || `HTTP ${resp.status}`
      );
      err.status = resp.status;
      throw err;
    }
    return data ?? {};
  };

  // server calls
  const loadOptions = (type) =>
    jsonFetch(`${API}/options/${encodeURIComponent(type)}`);

  const list = () => jsonFetch(`${API}/list`);
  const store = (payload) =>
    jsonFetch(`${API}/store`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

  const update = (payload) =>
    jsonFetch(`${API}/update`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

  const remove = (id) =>
    jsonFetch(`${API}/delete?id=${encodeURIComponent(id)}`);

  return { esc, jsonFetch, list, store, update, remove, loadOptions };
})();
