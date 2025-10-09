document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  const loginModalEl = document.getElementById("loginModal");
  const loginModal = new bootstrap.Modal(loginModalEl);
  const errorContainer = document.getElementById("loginErrorContainer");
  const inputs = loginForm.querySelectorAll("input");

  // Gérer la soumission du formulaire
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(loginForm);
    const response = await fetch(loginForm.action, {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    // Effacer les erreurs précédentes
    errorContainer.innerHTML = "";

    if (result.status === "error") {
      const div = document.createElement("div");
      div.className = "alert alert-danger small py-1 px-2 mb-0";
      div.textContent = result.message;
      errorContainer.appendChild(div);
      loginModal.show();
    } else if (result.status === "success") {
      window.location.href = result.redirect;
    }
  });

  // Effacer les erreurs lorsque l'utilisateur tape
  inputs.forEach((input) => {
    input.addEventListener("input", () => {
      errorContainer.innerHTML = "";
    });
  });

  // Réinitialiser le formulaire et les erreurs lorsque la modale est fermée
  loginModalEl.addEventListener("hidden.bs.modal", () => {
    loginForm.reset();
    errorContainer.innerHTML = "";
  });
});
