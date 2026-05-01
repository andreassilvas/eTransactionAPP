import { createPayment, getCart } from "./paymentAPI.js";

document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("paymentForm")
    .addEventListener("submit", async function (e) {
      e.preventDefault();

      try {
        const cartData = await getCart();

        let products = [];
        if (Array.isArray(cartData.cart)) {
          products = cartData.cart.map((item) => ({
            id: item.id,
            quantity: item.quantity,
          }));
        }

        const data = {
          products: products,

          expedition: EXPEDITION,

          card_name: document.getElementById("card_name").value,
          card_number: document.getElementById("nro_carte").value,
          postcode: document.getElementById("postCode").value,
          expiry_date: document.getElementById("exp_date").value,
          cvv: document.getElementById("nro_cvv").value,
        };

        const response = await createPayment(data);

        if (response.status === "success") {
          window.location.href =
            "/eTransactionAPP/verification/success?id=" + response.payment_id;
        } else {
          // update existing PHP alert dynamically
          let alertBox = document.querySelector(".alert.alert-danger.mt-3");

          if (!alertBox) {
            alertBox = document.createElement("div");
            alertBox.className = "alert alert-danger mt-3";
            alertBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> <span></span>`;
            document.querySelector(".card-body.mt-3").appendChild(alertBox);
          }

          alertBox.querySelector("span").textContent = response.message;
        }
      } catch (err) {
        console.error(err);

        let alertBox = document.querySelector(".alert.alert-danger.mt-3");

        if (!alertBox) {
          alertBox = document.createElement("div");
          alertBox.className = "alert alert-danger mt-3";
          alertBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> <span></span>`;
          document.querySelector(".card-body.mt-3").appendChild(alertBox);
        }

        alertBox.querySelector("span").textContent = "Erreur paiement";
      }
    });
});
