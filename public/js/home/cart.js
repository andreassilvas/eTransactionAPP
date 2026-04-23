document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".cart-component").forEach((component) => {
    const addBtn = component.querySelector(".add-to-cart");
    const stepper = component.querySelector(".stepper");
    const valueInput = component.querySelector(".value");
    const increment = component.querySelector(".increment");
    const decrement = component.querySelector(".decrement");

    const productId = component.dataset.productId;
    const BASE = "/eTransactionAPP";
    console.log(productId);

    let value = 1;
    const max = 10;

    function updateCartUI() {
      valueInput.value = value;

      decrement.textContent = value === 1 ? "🗑" : "−";
      increment.disabled = value >= max;
    }

    addBtn.addEventListener("click", async function () {
      value = 1;
      updateCartUI();

      addBtn.classList.add("d-none");
      stepper.classList.remove("d-none");

      try {
        console.log("SENDING productId:", JSON.stringify(productId));
        const response = await fetch(`${BASE}/api/cart/add`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            product_id: productId,
            quantity: value,
          }),
        });

        const data = await response.json();

        console.log("API response:", data);
      } catch (error) {
        console.error("API error:", error);
      }
    });

    increment.addEventListener("click", async function () {
      if (value < max) {
        value++;
        updateCartUI();

        await fetch(`${BASE}/api/cart/update`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            product_id: productId,
            quantity: value,
          }),
        });
      }
    });

    decrement.addEventListener("click", async function () {
      if (value > 1) {
        value--;
        updateCartUI();

        await fetch(`${BASE}/api/cart/update`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            product_id: productId,
            quantity: value,
          }),
        });
      } else {
        await fetch(`${BASE}/api/cart/remove`, {
          method: "POST",
          body: JSON.stringify({ product_id: productId }),
        });

        stepper.classList.add("d-none");
        addBtn.classList.remove("d-none");
        value = 1;
      }
    });

    updateCartUI();
  });
});
