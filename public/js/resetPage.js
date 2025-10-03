window.addEventListener("pageshow", function (event) {
  if (event.persisted) {
    const form = document.getElementById("billingForm");
    if (form) {
      form.reset(); // wipe input values

      // Remove validation classes and aria-invalid
      form.querySelectorAll("input").forEach((input) => {
        input.classList.remove("is-valid", "is-invalid");
        input.removeAttribute("aria-invalid");
      });
    }
  }
});
