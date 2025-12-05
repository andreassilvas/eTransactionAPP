"use strict";

// --- Regex patterns for validation
const regex = {
  name: /^[A-Za-z0-9À-ÿ\s\-]{1,150}$/u,
  category: /^[A-Za-zÀ-ÿ\s\-]{1,150}$/u,
  brand: /^[A-Za-zÀ-ÿ\s\-]{1,150}$/u,
  model: /^[A-Za-z0-9À-ÿ\s\-]{1,150}$/u,
  specs_desc: /^[A-Za-z0-9À-ÿ\s\-]{1,500}$/u,
  price: /^(?:\d{1,3}(?:[ \u00A0]\d{3})*|\d+)(?:\.(\d{0,2})?)?\$?$/,
  stock: /^\d+$/,
  warranty_period: /^[A-Za-z0-9À-ÿ\s\-]{1,150}$/u,
  support_level: /^[A-Za-zÀ-ÿ\s\-]{1,150}$/u,
  supplier: /^[A-Za-z0-9À-ÿ\s\-]{1,150}$/u,
};

// --- Formatters
const formatters = {
  price: (v) => {
    if (!v) return "";
    return String(v).replace(/[^\d.]/g, ""); // keep digits + dot
  },
};

// --- Core validate function
const validate = (el, pattern) => {
  if (!el || el.disabled) return true;

  const value = (el.value || "").trim();
  const isValid = pattern.test(value);

  el.setAttribute("aria-invalid", String(!isValid));
  el.classList.remove("is-valid", "is-invalid");
  el.classList.add(isValid ? "is-valid" : "is-invalid");

  return isValid;
};

// Validate a single input by its name
const validateFieldByName = (el) => {
  if (!el) return true;

  const key = el.getAttribute("name");
  if (!key) return true;

  if (formatters[key]) {
    const formatted = formatters[key](el.value);
    if (formatted !== el.value) el.value = formatted;
  }

  const re = regex[key] || (el.tagName === "SELECT" ? /^(?!\s*$).+/ : null);
  return re ? validate(el, re) : true;
};

// Validate all inputs in a table row
const validateRow = (tr, { requiredKeys = [] } = {}) => {
  const inputs = tr.querySelectorAll("input.dt-inline, select.dt-inline");
  let allOk = true;
  let firstBad = null;

  inputs.forEach((el) => {
    const key = el.getAttribute("name");
    const val = (el.value || "").trim();

    if (requiredKeys.includes(key) && !val) {
      el.setAttribute("aria-invalid", "true");
      el.classList.add("is-invalid");
      el.classList.remove("is-valid");
      allOk = false;
      if (!firstBad) firstBad = el;
      return;
    }

    if (!val) return; // optional empty is fine

    const ok = validateFieldByName(el);
    allOk = allOk && ok;
    if (!ok && !firstBad) firstBad = el;
  });

  if (!allOk) firstBad?.focus();
  return allOk;
};

// Expose the API
window.Validation = {
  regex,
  formatters,
  validate,
  validateFieldByName,
  validateRow,
};
