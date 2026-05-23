//Message toast pour le panier if it is empty
export function showToast(message) {
  const container = document.createElement("div");
  container.className = "toast-container position-fixed top-0 end-0 p-3 pt-5";
  container.style.zIndex = "9999";

  const toastEl = document.createElement("div");
  toastEl.className = "toast";
  toastEl.innerHTML = `
    <div class="d-flex" style="background-color: #FFE083">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-black me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  `;

  container.appendChild(toastEl);
  document.body.appendChild(container);

  const toast = new bootstrap.Toast(toastEl);
  toast.show();

  // remove after hidden (clean DOM)
  toastEl.addEventListener("hidden.bs.toast", () => {
    container.remove();
  });
}
