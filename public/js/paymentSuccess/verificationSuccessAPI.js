export async function getPayment(id) {
  const res = await fetch(`/api/payment/details?id=${id}`, {
    method: "GET",
  });

  const data = await res.json();
  return data;
}
