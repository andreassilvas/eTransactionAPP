const BASE = "/eTransactionAPP";

//API calls---------------------------------------------------
export async function getCart() {
  const res = await fetch(`${BASE}/api/cart`);
  return res.json();
}

export async function addToCart(productId, quantity) {
  const res = await fetch(`${BASE}/api/cart/add`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      product_id: productId,
      quantity: quantity,
    }),
  });

  return res.json();
}

export async function updateCart(productId, quantity) {
  const res = await fetch(`${BASE}/api/cart/update`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      product_id: productId,
      quantity: quantity,
    }),
  });
  return res.json();
}

export async function removeItemCart(productId) {
  const res = await fetch(`${BASE}/api/cart/remove`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      product_id: productId,
    }),
  });
  return res.json();
}
