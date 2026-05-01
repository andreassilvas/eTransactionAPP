const BASE = "/eTransactionAPP";

export async function getPayment(id) {
  const res = await fetch(`${BASE}/api/payment/details?id=${id}`, {
    method: "GET",
  });

  const data = await res.json();
  return data;
}
