const BASE = "/eTransactionAPP";

export async function createPayment(data) {
  const res = await fetch(`${BASE}/api/payment`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  });

  return res.json();
}

export async function getCart() {
  const res = await fetch(`${BASE}/api/cart`);
  return res.json();
}
