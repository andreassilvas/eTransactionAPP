import { getPayment } from "./verificationSuccessAPI.js";

document.addEventListener("DOMContentLoaded", async () => {
  const params = new URLSearchParams(window.location.search);
  const paymentId = params.get("id");

  if (!paymentId) return;

  const clientBox = document.getElementById("client-info");
  const expeditionBox = document.getElementById("expedition-info");
  const itemsBox = document.getElementById("order-items");
  const totalsBox = document.getElementById("order-totals");

  try {
    const data = await getPayment(paymentId);

    if (data.status !== "success") {
      itemsBox.innerHTML = `<li class="list-group-item">${data.message}</li>`;
      return;
    }

    const { payment, commands, client, expedition } = data;

    // ================= CLIENT =================
    clientBox.innerHTML = `
      <li class="list-group-item border-0 py-1">${client.name} ${client.lastname}</li>
      <li class="list-group-item border-0 py-1">
        ${client.address}, ${client.city}, ${client.province}, ${client.postcode}
      </li>
      <li class="list-group-item border-0 py-1">${client.email}</li>
      <li class="list-group-item border-0 py-1">${client.phone}</li>
    `;

    // ================= EXPEDITION =================
    expeditionBox.innerHTML = `
      <li class="list-group-item border-0 fw-semibold d-flex justify-content-between">
        <span><span class="custom-color-c">Date :</span> ${expedition.date}</span>
        <span>
          <i class="fa-solid fa-truck-fast fa-lg custom-color-a me-2"></i>
          <span class="custom-color-c">Tracking :</span> ${expedition.tracking_number ?? "N/A"}
        </span>
      </li>

      <li class="list-group-item border-0 fw-semibold d-flex justify-content-end">
        <span class="px-3"><span class="custom-color-c">ID :</span> ${payment.expedition_id}</span>
        <span><span class="custom-color-c">Statut :</span> ${expedition.status}</span>
      </li>

      <li class="list-group-item border-0 py-1">
        Nom : ${expedition.ship_name} ${expedition.ship_lastname}
      </li>

      <li class="list-group-item border-0 py-1">
        Adresse : ${expedition.ship_address}, ${expedition.ship_city}, ${expedition.ship_province}, ${expedition.ship_postcode}
      </li>

      <li class="list-group-item border-0 py-1">
        Courriel : ${expedition.ship_email}
      </li>

      <li class="list-group-item border-0 py-1">
        Téléphone : ${expedition.ship_phone}
      </li>
    `;

    // ================= COMMANDES =================
    let subtotal = 0;
    let itemsHTML = "";

    commands.forEach((item) => {
      const total = item.price * item.quantity;
      subtotal += total;

      itemsHTML += `
        <li class="list-group-item d-flex justify-content-between border-0">
          <span>${item.product_name} x${item.quantity}</span>
          <span>${total.toFixed(2)}$</span>
        </li>
      `;
    });

    itemsBox.innerHTML = itemsHTML;

    // ================= TOTALS =================
    const eco = 0.45;
    const taxes = subtotal * 0.15;
    const total = subtotal + eco + taxes;

    totalsBox.innerHTML = `
      <div class="container text-end">
        <div class="row justify-content-end pb-1">
          <div class="col-4 custom-color-b fw-bold">Sous-total :</div>
          <div class="col-2">${subtotal.toFixed(2)}$</div>
        </div>

        <div class="row justify-content-end pb-1">
          <div class="col-4 custom-color-b fw-bold">Frais livraison :</div>
          <div class="col-2">Gratuit</div>
        </div>

        <div class="row justify-content-end pb-1">
          <div class="col-4 custom-color-b fw-bold">Écofrais :</div>
          <div class="col-2">${eco.toFixed(2)}$</div>
        </div>

        <div class="row justify-content-end pb-1">
          <div class="col-4 custom-color-b fw-bold">Taxes :</div>
          <div class="col-2">${taxes.toFixed(2)}$</div>
        </div>

        <div class="row justify-content-end pb-1">
          <div class="col-4 custom-color-b fw-bold">Montant payé :</div>
          <div class="col-2">${payment.amount}$</div>
        </div>

        <div class="row">
          <div class="col-12 text-start custom-color-c">
            Carte: **** **** **** ${payment.last4}
          </div>
        </div>
      </div>
    `;
  } catch (err) {
    console.error(err);
  }
});
