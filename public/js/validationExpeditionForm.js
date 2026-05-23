document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("billingForm");
  const nameInput = document.getElementById("name");
  const nomFamilleInput = document.getElementById("nomFamille");
  const phoneNumberInput = document.getElementById("telephone");
  const emailInput = document.getElementById("email_adresse");
  const addressInput = document.getElementById("address");
  const cityInput = document.getElementById("city");
  const provinceInput = document.getElementById("province");
  const postalInput = document.getElementById("postCode");

  const regex = {
    name: /^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ\s\-]{1,49}$/u,
    lastname: /^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ\s\-]{1,49}$/u,
    phoneNumber: /^\(\d{3}\)\s\d{3}\s\d{4}$/,
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    address: /^\d+\s[A-Za-zÀ-ÿ]{2,}(?:\s[A-Za-zÀ-ÿ]{3,})*$/,
    city: /^[A-Za-zÀ-ÿ][a-zA-ZÀ-ÿ\- ]*$/,
    province: /.+/,
    postal: /^[A-Z]\d[A-Z]\s\d[A-Z]\d$/,
  };

  const fields = [
    {
      input: nameInput,
      regex: regex.name,
    },
    {
      input: nomFamilleInput,
      regex: regex.name,
    },
    {
      input: phoneNumberInput,
      regex: regex.phoneNumber,
    },
    {
      input: emailInput,
      regex: regex.email,
    },
    {
      input: addressInput,
      regex: regex.address,
    },
    {
      input: cityInput,
      regex: regex.city,
    },
    {
      input: provinceInput,
      regex: regex.province,
    },
    {
      input: postalInput,
      regex: regex.postal,
    },
  ];

  // Capitalize first letter
  function capitalizeWords(value) {
    return value.toLowerCase().replace(/\b\w/g, (char) => char.toUpperCase());
  }
  nameInput.addEventListener("input", (e) => {
    e.target.value = capitalizeWords(e.target.value);
    validate(e.target, regex.name);
  });

  nomFamilleInput.addEventListener("input", (e) => {
    e.target.value = capitalizeWords(e.target.value);
    validate(e.target, regex.lastname);
  });

  phoneNumberInput.addEventListener("input", (e) => {
    let numbers = e.target.value.replace(/\D/g, "");

    numbers = numbers.substring(0, 10);

    if (numbers.length > 6) {
      numbers = numbers.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2 $3");
    } else if (numbers.length > 3) {
      numbers = numbers.replace(/(\d{3})(\d+)/, "($1) $2");
    }

    e.target.value = numbers;

    validate(e.target, regex.phoneNumber);
  });

  cityInput.addEventListener("input", (e) => {
    e.target.value = capitalizeWords(e.target.value);
    validate(e.target, regex.city);
  });

  postalInput.addEventListener("input", (e) => {
    e.target.value = e.target.value
      .toUpperCase()
      .replace(/[^A-Z0-9]/g, "")
      .replace(/(.{3})(.)/, "$1 $2");

    validate(e.target, regex.postal);
  });

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

  form.addEventListener("submit", (e) => {
    let valid = true;

    fields.forEach((field) => {
      if (!validate(field.input, field.regex)) {
        valid = false;
      }
    });

    if (!valid) {
      e.preventDefault();
    }
  });
});
