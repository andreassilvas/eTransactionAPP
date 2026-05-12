document.addEventListener("DOMContentLoaded", async () => {
  try {
    const res = await fetch("/eTransactionAPP/api/currentuser");

    if (!res.ok) {
      console.error("User API error:", res.status);
      return;
    }

    const data = await res.json();

    console.log("Current user:", data);

    // Example: display user name
    const el = document.getElementById("user-name");
    if (el) {
      el.textContent = data.user.name;
    }
  } catch (err) {
    console.error("Fetch error:", err);
  }

  const container = document.getElementById("card-summary");

  if (!container) {
    console.error("cart-summary not found");
    return;
  }

  try {
    const res = await fetch("/eTransactionAPP/api/cart", {
      method: "GET",
    });

    if (!res.ok) {
      console.error("API ERROR:", res.status);
      return;
    }

    const data = await res.json();

    let subtotal = 0;
    let itemsHTML = "";

    if (Array.isArray(data.cart)) {
      data.cart.forEach((item) => {
        subtotal += item.price * item.quantity;

        itemsHTML += `
        <li class="list-group-item d-flex justify-content-between">
          <span>${item.name} x${item.quantity}</span>
          <span>${(item.price * item.quantity).toFixed(2)}$</span>
        </li>
        `;
      });
    }

    const eco = 0.45;
    const shipping = 0;
    const taxes = subtotal * 0.15;
    const total = subtotal + eco + shipping + taxes;

    container.innerHTML = `
      ${itemsHTML}
      <li class="list-group-item d-flex justify-content-between fw-bold custom-color-b">
        <span>Sous-total</span>
        <span>${subtotal.toFixed(2)}$</span>
      </li>
      <li class="list-group-item d-flex justify-content-between">
        <span>Frais de livraison estimés</span>
        <span>Gratuit</span>
      </li>
      <li class="list-group-item d-flex justify-content-between">
        <span>Écofrais</span>
        <span>${eco.toFixed(2)}$</span>
      </li> 
      <li class="list-group-item d-flex justify-content-between">
        <span>Estimation des taxes</span>
        <span>${taxes.toFixed(2)}$</span>
      </li>
      <li class="list-group-item d-flex justify-content-between fw-bold custom-color-b">
        <span>Total final</span>
        <span>${total.toFixed(3)}$</span>
      </li>
    `;
  } catch (error) {
    console.error("Fetch error:", error);
  }
});
