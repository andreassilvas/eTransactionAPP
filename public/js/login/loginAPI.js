export async function getClient() {
  const res = await fetch("/api/cart");
  return res.json();
}
