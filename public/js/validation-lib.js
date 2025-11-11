(function (root, factory) {
  if (typeof define === "function" && define.amd) {
    define([], factory); // AMD
  } else if (typeof module === "object" && module.exports) {
    module.exports = factory(); // CommonJS
  } else {
    root.Validation = factory(); // Browser global
  }
})(typeof self !== "undefined" ? self : this, function () {
  "use strict";

  // --- Regex
  const regex = {
    name: /^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ\s\-]{1,49}$/u,
    lastname: /^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ\s\-]{1,49}$/u,
    phone: /^\(?\d{3}\)?\s?\d{3}\s?\d{4}$/u,
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    address: /^\d+\s[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s]*$/u,
    city: /^[A-Za-zÀ-ÿ][a-zA-ZÀ-ÿ\- ]*$/,
    province: /^[A-Za-zÀ-ÿ][a-zA-ZÀ-ÿ\- ]*$/,
    postcode: /^[A-Z]\d[A-Z]\s\d[A-Z]\d$/,
    password: /^\d{2,5}$/, // adjust if you need stronger rules
  };

  // --- Formatters (keys correspond to field names you’ll use)
  const formatters = {
    postcode: (v) =>
      String(v || "")
        .toUpperCase()
        .replace(/[^A-Z0-9]/g, "")
        .replace(/(.{3})(.)/, "$1 $2"),
    phone: (v) =>
      String(v || "")
        .replace(/\D/g, "")
        .replace(/(\d{3})(\d{3})(\d{0,4})/, (match, p1, p2, p3) =>
          p3 ? `(${p1}) ${p2} ${p3}` : p2 ? `(${p1}) ${p2}` : `(${p1}`
        ),
  };

  // --- Core validate helper (same as your pattern)
  const validate = (inputEl, pattern, extraCheck = null) => {
    const value = inputEl.value.trim();
    let isValid = pattern.test(value);

    if (isValid && extraCheck) isValid = extraCheck(value);
    inputEl.setAttribute("aria-invalid", String(!isValid));
    inputEl.classList.toggle("is-valid", isValid);
    inputEl.classList.toggle("is-invalid", !isValid);
    return isValid;
  };

  // Validate a single input by its name against regex/formatters maps
  const validateFieldByName = (inputEl) => {
    if (!inputEl) return true;
    const key = inputEl.getAttribute("name");
    if (!key) return true;
    if (formatters[key]) {
      const after = formatters[key](inputEl.value);
      if (after !== inputEl.value) inputEl.value = after;
    }
    const re = regex[key];
    if (!re) return true;
    return validate(inputEl, re);
  };

  // Validate all inline inputs in a <tr> (for DataTables inline editing)
  const validateRow = (
    tr,
    { requiredKeys = ["name", "lastname", "email"] } = {}
  ) => {
    const inputs = tr.querySelectorAll("input.dt-inline");
    let allOk = true;
    let firstBad = null;

    inputs.forEach((el) => {
      const key = el.getAttribute("name");
      const required = requiredKeys.includes(key);
      if (required && !el.value.trim()) {
        el.setAttribute("aria-invalid", "true");
        el.classList.add("is-invalid");
        el.classList.remove("is-valid");
        allOk = false;
        if (!firstBad) firstBad = el;
        return;
      }
      const ok = validateFieldByName(el);
      if (!ok && !firstBad) firstBad = el;
      allOk = allOk && ok;
    });

    if (!allOk) firstBad?.focus();
    return allOk;
  };

  // Wire up input listeners for a classic form (by element IDs)
  // fields: [{ id: "name", re: "name", format: "postal" }, ...]
  const attachFieldValidators = (fields) => {
    const map = {};
    fields.forEach((f) => {
      const el = document.getElementById(f.id);
      if (!el) return;
      map[f.id] = el;
      el.addEventListener("input", (e) => {
        if (f.format && formatters[f.format]) {
          e.target.value = formatters[f.format](e.target.value);
        }
        const r = regex[f.re] || regex[f.id]; // allow using "re" or fallback by id name
        if (r) validate(e.target, r);
      });
    });
    return map; // returns { id: element } for submit-time checks
  };

  // Validate the attached fields on submit (classic form)
  const validateFields = (fields, elsMap) => {
    let okAll = true;
    let firstBad = null;
    fields.forEach((f) => {
      const el = elsMap[f.id];
      if (!el) return;
      // re-run formatter on submit just in case
      if (f.format && formatters[f.format]) {
        el.value = formatters[f.format](el.value);
      }
      const r = regex[f.re] || regex[f.id];
      const ok = r ? validate(el, r) : true;
      if (!ok && !firstBad) firstBad = el;
      okAll = okAll && ok;
    });
    if (!okAll) firstBad?.focus();
    return okAll;
  };

  return {
    regex,
    formatters,
    validate, // low-level
    validateFieldByName, // for inline inputs (uses input[name])
    validateRow, // for the DataTable row
    attachFieldValidators, // for classic form wiring
    validateFields, // final submit check for classic forms
  };
});
