document.addEventListener("DOMContentLoaded", () => {
  const loginModal = document.getElementById("loginModal");
  if (!loginModal) return;

  const loginForm = loginModal.querySelector("#loginForm");
  const redirect = loginForm?.querySelector("input[name=redirect]");
  const modalTitle = loginModal.querySelector(".modal-title");

  loginModal.addEventListener("show.bs.modal", (event) => {
    const button = event.relatedTarget;
    // set values if form exists
    if (redirect) redirect.value = button?.dataset?.redirect || "expedition";
    if (modalTitle)
      modalTitle.textContent = button?.dataset?.title || "Connexion";

    // dataset.hasError is string "true"/"false" or undefined — normalize to boolean
    const hasError = loginModal.dataset.hasError === "true";

    // Reset only when there is NO server error
    if (!hasError && loginForm) {
      loginForm.reset();
    }
  });

  // Auto-open when server said there's an error
  if (loginModal.dataset.hasError === "true") {
    new bootstrap.Modal(loginModal).show();
  }
});
