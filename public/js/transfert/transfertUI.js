import { createTransfert } from "./transfertAPI.js";

document.addEventListener("DOMContentLoaded", async () => {
  const form = document.getElementById("transferForm");
  const messageBox = document.getElementById("transferMessage");

  function showMessage(type, message) {
    messageBox.innerHTML = `
      <div class="alert alert-${type}">
        ${message}
      </div>
    `;

    setTimeout(() => {
      messageBox.innerHTML = "";
    }, 3000);
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    messageBox.innerHTML = "";

    const submitBtn = form.querySelector('button[type="submit"]');

    submitBtn.disabled = true;

    const payload = {
      amount: parseFloat(document.getElementById("amount").value),

      operation_type: document.getElementById("operation_type").value,

      description: document.getElementById("description").value.trim(),
    };

    try {
      const result = await createTransfert(payload);

      if (result.status !== "success") {
        showMessage("danger", result.message);

        setTimeout(() => {
          messageBox.innerHTML = "";
        }, 3000);

        return;
      }

      showMessage("success", result.message);

      form.reset();
    } catch (error) {
      console.error(error);

      showMessage("danger", " Erreur serveur");
    } finally {
      submitBtn.disabled = false;
    }
  });
});
