import { createTransfert } from "./transfertAPI.js";

document.addEventListener("DOMContentLoaded", async () => {
  const form = document.getElementById("transferForm");

  const messageBox = document.getElementById("transferMessage");

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
        messageBox.innerHTML = `
        <div class="alert alert-danger">
          ${result.message}
        </div>
      `;

        return;
      }

      messageBox.innerHTML = `
      <div class="alert alert-success">
        ${result.message}
      </div>
    `;

      form.reset();
    } catch (error) {
      console.error(error);

      messageBox.innerHTML = `
      <div class="alert alert-danger">
        Erreur serveur
      </div>
    `;
    } finally {
      submitBtn.disabled = false;
    }
  });
});
