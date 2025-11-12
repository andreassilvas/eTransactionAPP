// public/js/geoApi.js
window.GeoAPI = (function () {
  const GEO = "/geo"; // matches your GeoController routes

  const jsonFetch = async (u) => {
    const r = await fetch(u);
    if (!r.ok) throw new Error(`HTTP ${r.status} ${u}`);
    return r.json();
  };

  /** Provinces: [{id, code, name}] */
  const listProvinces = () => jsonFetch(`${GEO}/provinces`);

  /**
   * Cities by province code:
   * returns { items: [{id, name, province_code}], total, limit, offset }
   */
  const listCitiesByProvince = (
    code,
    { search = "", limit = 200, offset = 0 } = {}
  ) => {
    const qs = new URLSearchParams({ code, search, limit, offset });
    return jsonFetch(`${GEO}/provinces/cities?` + qs.toString());
  };

  return { listProvinces, listCitiesByProvince };
})();
