import { getClients, addCard } from "./add_cardAPI.js";

document.addEventListener("DOMContentLoaded", async () => {
  const form = document.getElementById("addCardForm");
  const clientSelect = document.getElementById("to_client_id");
  const messageBox = document.getElementById("transferMessage");

  const res = await fetch("/api/currentuser");

  if (!res.ok) {
    console.error("User API error:", res.status);
    return;
  }

  // Load clients
  const clients = await getClients();

  clients.forEach((client) => {
    const option = document.createElement("option");

    option.value = client.id;

    option.textContent = `${client.id} - ${client.name} ${client.lastname} - ${client.role}`;

    clientSelect.appendChild(option);
  });

  if (!clients.length) {
    const option = document.createElement("option");

    option.textContent = "Aucun client sans carte trouvé";

    option.disabled = true;

    clientSelect.appendChild(option);

    return;
  }

  // Submit form
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const payload = {
      client_id: parseInt(clientSelect.value),

      card_name: document.getElementById("card_name").value,

      card_number: document.getElementById("nro_carte").value,

      expiry_date: document.getElementById("exp_date").value,

      code_postal: document.getElementById("postCode").value,

      cvv: document.getElementById("nro_cvv").value,

      card_type: document.getElementById("card_type").value,
    };

    try {
      const result = await addCard(payload);

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

      setTimeout(() => {
        messageBox.innerHTML = "";
      }, 3000);

      form.reset();

      form.querySelectorAll(".is-valid, .is-invalid").forEach((input) => {
        input.classList.remove("is-valid", "is-invalid");

        input.removeAttribute("aria-invalid");
      });

      clientSelect
        .querySelector(`option[value="${payload.client_id}"]`)
        ?.remove();
    } catch (error) {
      console.error(error);

      messageBox.innerHTML = `
            <div class="alert alert-danger">
              Erreur serveur
            </div>
          `;
    }
  });
});
