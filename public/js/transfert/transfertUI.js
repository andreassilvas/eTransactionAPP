import { createTransfert, getClients } from "./transfertAPI.js";

document.addEventListener("DOMContentLoaded", async () => {
  const form = document.getElementById("transferForm");

  const messageBox = document.getElementById("transferMessage");

  const clientSelect = document.getElementById("to_client_id");

  // Load clients
  const clients = await getClients();

  clients.forEach((client) => {
    const option = document.createElement("option");

    option.value = client.id;

    option.textContent = `${client.id} - ${client.name} ${client.lastname}`;

    clientSelect.appendChild(option);
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    messageBox.innerHTML = "";

    const submitBtn = form.querySelector('button[type="submit"]');

    submitBtn.disabled = true;

    const payload = {
      to_client_id: parseInt(document.getElementById("to_client_id").value),

      amount: parseFloat(document.getElementById("amount").value),

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
