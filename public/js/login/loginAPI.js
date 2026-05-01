const BASE = "/eTransactionAPP";

export async function getClient() {
  const res = await fetch(`${BASE}/api/cart`);
  return res.json();
}
