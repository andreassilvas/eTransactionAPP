document.addEventListener("DOMContentLoaded", () => {
  const form =
    document.getElementById("paymentForm") ||
    document.getElementById("addCardForm");

  const nameInput = document.getElementById("card_name");
  const cardNumberInput = document.getElementById("nro_carte");
  const postalInput = document.getElementById("postCode");
  const expiryInput = document.getElementById("exp_date");
  const cvvInput = document.getElementById("nro_cvv");

  const regex = {
    name: /^[A-ZÀ-Ÿ][a-zA-ZÀ-ÿ\s\-]{1,49}$/u,
    card: /^\d{4}\s\d{4}\s\d{4}\s\d{4}$/,
    postal: /^[A-Z]\d[A-Z]\s\d[A-Z]\d$/,
    expiry: /^(0[1-9]|1[0-2])\/\d{2}$/,
    cvv: /^\d{3,4}$/,
  };

  const fields = [
    {
      input: nameInput,
      regex: regex.name,
    },
    {
      input: cardNumberInput,
      regex: regex.card,
    },
    {
      input: postalInput,
      regex: regex.postal,
    },
    {
      input: expiryInput,
      regex: regex.expiry,
    },
    {
      input: cvvInput,
      regex: regex.cvv,
    },
  ];

  function capitalizeWords(value) {
    return value.toLowerCase().replace(/\b\w/g, (char) => char.toUpperCase());
  }

  nameInput.addEventListener("input", (e) => {
    e.target.value = capitalizeWords(e.target.value);
    validate(e.target, regex.name);
  });

  // Formatage et validation du numéro de carte
  cardNumberInput.addEventListener("input", (e) => {
    e.target.value = e.target.value
      .replace(/\D/g, "")
      .replace(/(.{4})/g, "$1 ")
      .trim();
    validate(e.target, regex.card);
  });

  // Vérification de la date d'expiration
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
    let value = e.target.value.replace(/\D/g, ""); // Remove non-digits

    // Auto-insert slash
    if (value.length > 2) {
      value = value.slice(0, 2) + "/" + value.slice(2, 4);
    }

    e.target.value = value;

    // Appel de la validation avec vérification supplémentaire
    validate(e.target, regex.expiry, checkExpiry);
  });

  // Formatage et validation du code postal
  postalInput.addEventListener("input", (e) => {
    e.target.value = e.target.value
      .toUpperCase()
      .replace(/[^A-Z0-9]/g, "")
      .replace(/(.{3})(.)/, "$1 $2");
    validate(e.target, regex.postal);
  });

  // Validation du nom
  nameInput.addEventListener("input", (e) => validate(e.target, regex.name));

  // Validation du CVV
  cvvInput.addEventListener("input", (e) => validate(e.target, regex.cvv));

  // Disable all except first
  fields.forEach((field, index) => {
    if (index !== 0) {
      field.input.disabled = true;
    }
  });

  function validate(input, regex) {
    const isValid = regex.test(input.value.trim());

    input.classList.toggle("is-valid", isValid);
    input.classList.toggle("is-invalid", !isValid);

    return isValid;
  }

  // const validate = (input, pattern, extraCheck = null) => {
  //   const value = input.value.trim();
  //   let isValid = pattern.test(value);

  //   if (isValid && extraCheck) isValid = extraCheck(value);

  //   input.setAttribute("aria-invalid", !isValid);
  //   input.classList.toggle("is-valid", isValid);
  //   input.classList.toggle("is-invalid", !isValid);

  //   return isValid;
  // };

  fields.forEach((field, index) => {
    field.input.addEventListener("input", () => {
      const valid = validate(field.input, field.regex);

      // Next field
      const nextField = fields[index + 1];

      if (nextField) {
        nextField.input.disabled = !valid;

        // Clear next field if current becomes invalid
        if (!valid) {
          nextField.input.value = "";
          nextField.input.classList.remove("is-valid", "is-invalid");
        }
      }

      // Disable ALL following fields too
      if (!valid) {
        for (let i = index + 1; i < fields.length; i++) {
          fields[i].input.disabled = true;
          fields[i].input.value = "";
          fields[i].input.classList.remove("is-valid", "is-invalid");
        }
      }
    });
  });

  // Validation finale au moment de la soumission
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
