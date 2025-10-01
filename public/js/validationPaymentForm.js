document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("paymentForm");

  const regex = {
    name: /^[A-ZÀ-Ÿ][a-zA-ZÀ-ÿ\s\-]{1,49}$/u,
    card: /^\d{4}\s\d{4}\s\d{4}\s\d{4}$/,
    postal: /^[A-Z]\d[A-Z]\s\d[A-Z]\d$/,
    expiry: /^(0[1-9]|1[0-2])\/\d{2}$/,
    cvv: /^\d{3,4}$/,
  };

  const validate = (input, pattern, extraCheck = null) => {
    const value = input.value.trim();
    let isValid = pattern.test(value);

    if (isValid && extraCheck) isValid = extraCheck(value);

    input.setAttribute("aria-invalid", !isValid);
    input.classList.toggle("is-valid", isValid);
    input.classList.toggle("is-invalid", !isValid);

    return isValid;
  };

  // Card Number formatting & validation
  const cardNumberInput = document.getElementById("nro_carte");
  cardNumberInput.addEventListener("input", (e) => {
    e.target.value = e.target.value
      .replace(/\D/g, "")
      .replace(/(.{4})/g, "$1 ")
      .trim();
    validate(e.target, regex.card);
  });

  // Expiry date check
  const expiryInput = document.getElementById("exp_date");

  const checkExpiry = (val) => {
    if (!regex.expiry.test(val)) return false;
    const [month, year] = val.split("/");
    const expMonth = parseInt(month, 10);
    const expYear = parseInt("20" + year, 10);
    const now = new Date();
    const currentMonth = now.getMonth() + 1;
    const currentYear = now.getFullYear();
    return !(
      expYear < currentYear ||
      (expYear === currentYear && expMonth < currentMonth)
    );
  };

  expiryInput.addEventListener("input", (e) => {
    let value = e.target.value.replace(/\D/g, ""); // remove non-digits

    // Add slash after 2 digits
    if (value.length > 2) {
      value = value.slice(0, 2) + "/" + value.slice(2, 4);
    }

    e.target.value = value;

    // Call your validation
    validate(e.target, regex.expiry, checkExpiry);
  });

  // Postal code formatting & validation
  const postalInput = document.getElementById("postCode");
  postalInput.addEventListener("input", (e) => {
    e.target.value = e.target.value
      .toUpperCase()
      .replace(/[^A-Z0-9]/g, "")
      .replace(/(.{3})(.)/, "$1 $2");
    validate(e.target, regex.postal);
  });

  // Name validation
  const nameInput = document.getElementById("card_name");
  nameInput.addEventListener("input", (e) => validate(e.target, regex.name));

  // CVV validation
  const cvvInput = document.getElementById("nro_cvv");
  cvvInput.addEventListener("input", (e) => validate(e.target, regex.cvv));

  // Final submit validation
  form.addEventListener("submit", (e) => {
    const valid =
      validate(nameInput, regex.name) &&
      validate(cardNumberInput, regex.card) &&
      validate(postalInput, regex.postal) &&
      validate(expiryInput, regex.expiry, checkExpiry) &&
      validate(cvvInput, regex.cvv);

    if (!valid) {
      e.preventDefault();
      e.stopPropagation();
    }
  });
});
