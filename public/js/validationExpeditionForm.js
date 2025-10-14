document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("billingForm");

  const regex = {
    name: /^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ\s\-]{1,49}$/u,
    lastname: /^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ\s\-]{1,49}$/u,
    phoneNumber: /^\(?\d{3}\)?\s?\d{3}\s?\d{4}$/u,
    postal: /^[A-Z]\d[A-Z]\s\d[A-Z]\d$/,
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    address: /^\d+\s[A-Za-z][A-Za-z\s]*$/,
    city: /^[A-Za-zÀ-ÿ][a-zA-ZÀ-ÿ\- ]*$/,
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

  //Nom
  const nameInput = document.getElementById("name");
  nameInput.addEventListener("input", (e) => {
    e.target.value = e.target.value;
    validate(e.target, regex.name);
  });

  //Nom de famille
  const lastNameInput = document.getElementById("nomFamille");
  lastNameInput.addEventListener("input", (e) => {
    e.target.value = e.target.value;
    validate(e.target, regex.lastname);
  });

  //Téléphone
  const phoneNumberInput = document.getElementById("telephone");
  phoneNumberInput.addEventListener("input", (e) => {
    e.target.value = e.target.value;
    validate(e.target, regex.phoneNumber);
  });

  // Formatage et validation du code postal
  const postalInput = document.getElementById("postCode");
  postalInput.addEventListener("input", (e) => {
    e.target.value = e.target.value
      .toUpperCase()
      .replace(/[^A-Z0-9]/g, "")
      .replace(/(.{3})(.)/, "$1 $2");
    validate(e.target, regex.postal);
  });

  //Courriel
  const emailInput = document.getElementById("email_adresse");
  emailInput.addEventListener("input", (e) => {
    e.target.value = e.target.value;
    validate(e.target, regex.email);
  });

  //Adresse
  const addressInput = document.getElementById("address");
  addressInput.addEventListener("input", (e) => {
    e.target.value = e.target.value;
    validate(e.target, regex.address);
  });

  //Ville
  const cityInput = document.getElementById("city");
  cityInput.addEventListener("input", (e) => {
    e.target.value = e.target.value;
    validate(e.target, regex.city);
  });

  // Validation finale au moment de la soumission
  form.addEventListener("submit", (e) => {
    const valid =
      validate(nameInput, regex.name) &&
      validate(lastNameInput, regex.lastname) &&
      validate(phoneNumberInput, regex.phoneNumber) &&
      validate(emailInput, regex.email) &&
      validate(addressInput, regex.address) &&
      validate(cityInput, regex.city) &&
      validate(postalInput, regex.postal);

    if (!valid) {
      e.preventDefault();
      e.stopPropagation();
    }
  });
});
