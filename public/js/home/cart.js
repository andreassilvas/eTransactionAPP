document.addEventListener("DOMContentLoaded", function () {
  console.log("Cart JS Loaded");
  const container = document.querySelector(".cart-component");

  const addBtn = document.getElementById("add-to-cart");
  const stepper = container.querySelector(".stepper");
  const valueInput = document.querySelector(".value");
  const incrementBtn = document.getElementById("increment");
  const decrementBtn = document.getElementById("decrement");
  const toAddToCartBtn = container.querySelector(".to-add-to-cart");

  let value = 1;
  const min = 1;
  const max = 10;

  addBtn.addEventListener("click", function () {
    value = 1;
    updateCart();

    addBtn.classList.add("d-none");
    stepper.classList.remove("d-none");

    console.log("Added to cart:", value);
  });

  function updateCart() {
    valueInput.value = value;

    decrement.textContent = value === 1 ? "🗑" : "−";
    increment.disabled = value >= max;
  }

  incrementBtn.addEventListener("click", function () {
    if (value < max) {
      value++;
      updateCart();
    }
  });

  decrementBtn.addEventListener("click", function () {
    if (value > min) {
      value--;
    } else {
      console.log("Remove Item from Cart");

      stepper.classList.add("d-none");
      addBtn.classList.remove("d-none");

      value = 1; // reset
    }
    updateCart();
  });

  document.getElementById("add-to-cart").addEventListener("click", () => {
    console.log("Add to cart:", value);

    // Example API call
    /*
    fetch('/api/cart', {
        method: 'POST',
        body: JSON.stringify({ quantity: value }),
        headers: { 'Content-Type': 'application/json' }
    });
    */
  });

  updateCart();
});
