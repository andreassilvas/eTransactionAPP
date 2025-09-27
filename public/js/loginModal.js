document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  const loginModal = new bootstrap.Modal(document.getElementById("loginModal"));
  const errorContainer = document.getElementById("loginErrorContainer");
  const inputs = loginForm.querySelectorAll("input");

  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(loginForm);
    const response = await fetch(loginForm.action, {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    // Clear any existing error
    errorContainer.innerHTML = "";

    if (result.status === "error") {
      const div = document.createElement("div");
      div.className = "alert alert-danger small py-1 px-2 mb-0"; // smaller size
      // div.style.fontSize = "0.85rem"; // optional, make text smaller
      div.textContent = result.message;
      errorContainer.appendChild(div);
      loginModal.show();
    } else if (result.status === "success") {
      window.location.href = result.redirect;
    }
  });

  // Remove error when user types in any input
  inputs.forEach((input) => {
    input.addEventListener("input", () => {
      errorContainer.innerHTML = "";
    });
  });
});
