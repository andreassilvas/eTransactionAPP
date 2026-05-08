export async function getCart() {
  const res = await fetch("/api/cart");
  return res.json();
}

export async function addToCart(productId, quantity) {
  const res = await fetch("/api/cart/add", {
    method: "POST",
    body: JSON.stringify({
      product_id: productId,
      quantity: quantity,
    }),
  });

  return res.json();
}

export async function updateCart(productId, quantity) {
  const res = await fetch("/api/cart/update", {
    method: "POST",
    body: JSON.stringify({
      product_id: productId,
      quantity: quantity,
    }),
  });
  return res.json();
}

export async function removeItemCart(productId) {
  const res = await fetch("/api/cart/remove", {
    method: "POST",
    body: JSON.stringify({
      product_id: productId,
    }),
  });
  return res.json();
}
