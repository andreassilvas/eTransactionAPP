export async function createTransfert(payload) {
  const res = await fetch("/api/transferts", {
    method: "POST",

    headers: {
      "Content-Type": "application/json",
    },

    body: JSON.stringify(payload),
  });

  return await res.json();
}

export async function getClients() {
  const res = await fetch("/gestion-utilisateurs/list");

  return await res.json();
}
