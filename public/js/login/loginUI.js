import { showToast } from "../toast/toastCart.js";
import { getClient } from "./loginAPI.js";

document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  const loginModalEl = document.getElementById("loginModal");
  const loginModal = new bootstrap.Modal(loginModalEl);
  const errorContainer = document.getElementById("loginErrorContainer");
  const inputs = loginForm.querySelectorAll("input");
  const title = document.getElementById("loginModalLabel");
  const modalDiv = document.querySelector(".modal-content");
  let loginSource = "user"; // default source

  //Client - Open Login modal just if the cart have product(s)
  document.querySelectorAll('[data-source="client"]').forEach((el) => {
    el.addEventListener("click", async (e) => {
      e.preventDefault();

      const data = await getClient();

      let total = 0;

      if (Array.isArray(data.cart)) {
        data.cart.forEach((item) => (total += item.quantity));
      } else {
        Object.values(data.cart).forEach((q) => (total += Number(q)));
      }

      if (total === 0) {
        showToast("Votre panier est vide. Ajouter un produit.");
        return;
      }

      loginSource = "user";
      title.textContent = "Connexion utilisateur";
      modalDiv.classList.add("bg-warning-subtle");
      loginModal.show();
    });
  });

  document.querySelectorAll('[data-source="admin"]').forEach((el) => {
    el.addEventListener("click", (e) => {
      e.preventDefault();

      loginSource = "admin";
      title.textContent = "Connexion administrateur";
      modalDiv.classList.remove("bg-warning-subtle");
      loginModal.show();
    });
  });

  // Gérer la soumission du formulaire
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    console.log("FORM SUBMITTED");

    const formData = new FormData(loginForm);

    //To allow or block admin or client login if they are not using the right modal.
    formData.append("source", loginSource);

    const payload = {
      email: formData.get("email"),
      password: formData.get("password"),
      source: loginSource,
    };

    const response = await fetch("/api/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(payload),
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
      localStorage.setItem("token", result.token);
      // Redirect after successful login
      window.location.href = result.redirect;
    }
    console.log(result);
    return; // TEMP stop redirect
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
