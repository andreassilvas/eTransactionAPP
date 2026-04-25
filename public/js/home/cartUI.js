import { getCart, addToCart, updateCart, removeItemCart } from "./cartAPI.js";

document.addEventListener("DOMContentLoaded", function () {
  //Show cart count products in navbar ----------------------------
  async function updateCartCount() {
    //Function call API layer
    const data = await getCart();

    let total = 0;

    // API returns array
    if (Array.isArray(data.cart)) {
      data.cart.forEach((item) => (total += item.quantity));
    } else {
      // if object {1:2, 2:1}
      Object.values(data.cart).forEach((quant) => (total += Number(quant)));
    }

    document.getElementById("cart-count").textContent = total;
  }
  updateCartCount();

  // Managing cart UI and API calls ----------------------------
  document.querySelectorAll(".cart-component").forEach((component) => {
    const addBtn = component.querySelector(".add-to-cart");
    const stepper = component.querySelector(".stepper");
    const valueInput = component.querySelector(".value");
    const increment = component.querySelector(".increment");
    const decrement = component.querySelector(".decrement");

    const productId = component.dataset.productId;
    const BASE = "/eTransactionAPP";

    let quantity = 1;
    const max = 10;

    function updateCartUI() {
      valueInput.value = quantity;

      decrement.innerHTML =
        quantity === 1 ? '<i class="fa-solid fa-trash hovertrash"></i>' : "−";
      increment.disabled = quantity >= max;
    }

    // Add to cart------------------------------------------
    addBtn.addEventListener("click", async function () {
      quantity = 1;
      updateCartUI();

      addBtn.classList.add("d-none");
      stepper.classList.remove("d-none");

      try {
        //Function call API layer
        await addToCart(productId, quantity);
      } catch (error) {
        console.error("API error:", error);
      }

      updateCartUI();
      updateCartCount();
    });

    // Increment quantity ------------------------------------
    increment.addEventListener("click", async function () {
      if (quantity < max) {
        quantity++;
        updateCartUI();

        //Function call API layer
        await updateCart(productId, quantity);
      }

      updateCartUI();
      updateCartCount();
    });

    // Decrement quantity or remove from cart -------------------
    decrement.addEventListener("click", async function () {
      if (quantity > 1) {
        quantity--;
        updateCartUI();
        //Function call API layer
        await await updateCart(productId, quantity);
      } else {
        //Function call API layer
        await removeItemCart(productId);

        stepper.classList.add("d-none");
        addBtn.classList.remove("d-none");
        quantity = 1;
      }

      updateCartUI();
      updateCartCount();
    });

    updateCartUI();
  });
});
