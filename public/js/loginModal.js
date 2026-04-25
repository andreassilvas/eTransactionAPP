document.addEventListener("DOMContentLoaded", () => {
  console.log("loginModal.js loaded");
  const loginForm = document.getElementById("loginForm");
  const loginModalEl = document.getElementById("loginModal");
  const loginModal = new bootstrap.Modal(loginModalEl);
  const errorContainer = document.getElementById("loginErrorContainer");
  const inputs = loginForm.querySelectorAll("input");

  const title = document.getElementById("loginModalLabel");

  loginModalEl.addEventListener("show.bs.modal", (event) => {
    const trigger = event.relatedTarget; // button or link clicked

    if (!trigger) return;

    const source = trigger.getAttribute("data-source");

    if (source === "admin") {
      title.textContent = "Connexion administrateur";
    } else {
      title.textContent = "Connexion utilisateur";
    }
  });

  // Gérer la soumission du formulaire
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    console.log("FORM SUBMITTED");

    const formData = new FormData(loginForm);

    const response = await fetch(loginForm.action, {
      method: "POST",
      body: formData,
    });

    const result = await response.json();
    console.log("RESULT:", result);

    // Effacer les erreurs précédentes
    errorContainer.innerHTML = "";

    if (result.status === "error") {
      const div = document.createElement("div");
      div.className = "alert alert-danger small py-1 px-2 mb-0";
      div.textContent = result.message;
      errorContainer.appendChild(div);
      loginModal.show();
    } else if (result.status === "success") {
      // Redirect after successful login
      window.location.href = result.redirect;
    }
  });

  // Effacer les erreurs lorsque l'utilisateur tape
  inputs.forEach((input) => {
    input.addEventListener("input", () => {
      errorContainer.innerHTML = "";
    });
  });

  const emailInput = document.getElementById("email_adresse");
  loginModalEl.addEventListener("shown.bs.modal", () => {
    emailInput.focus();
  });

  // Réinitialiser le formulaire et les erreurs lorsque la modale est fermée
  loginModalEl.addEventListener("hidden.bs.modal", () => {
    loginForm.reset();
    errorContainer.innerHTML = "";
  });

  //Fix aria-hidden bootstrap focus issue
  loginModalEl.addEventListener("hidden.bs.modal", () => {
    document.activeElement.blur(); // remove focus from button
  });
});
