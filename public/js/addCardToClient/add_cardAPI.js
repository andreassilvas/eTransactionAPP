export async function getClients() {
  const res = await fetch("/gestion-utilisateurs/sans-cartes");

  return await res.json();
}

export async function addCard(payload) {
  const res = await fetch("/api/payment/add-card", {
    method: "POST",
    body: JSON.stringify(payload),
  });

  return await res.json();
}
