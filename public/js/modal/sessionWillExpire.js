document.addEventListener("DOMContentLoaded", () => {
  if (sessionStorage.getItem("sessionWarningShown")) {
    return;
  }

  const modalEl = document.getElementById("sessionExpiredModal");

  if (!modalEl) return;

  const modal = new bootstrap.Modal(modalEl);
  modal.show();

  sessionStorage.setItem("sessionWarningShown", "true");
});
