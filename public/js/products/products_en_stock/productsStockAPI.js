const BASE = "/eTransactionAPP";

export async function getProductStock() {
  try {
    const res = await fetch(`${BASE}/api/products`, {
      method: "GET",
    });

    if (!res.ok) {
      throw new Error(`HTTP error: ${res.status}`);
    }

    const data = await res.json();

    console.log("API DATA:", data);

    return data.data;
  } catch (error) {
    console.error("Error fetching products:", error);
    return [];
  }
}
