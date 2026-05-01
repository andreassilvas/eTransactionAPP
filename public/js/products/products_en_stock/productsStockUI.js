import { getProductStock } from "./productsStockAPI.js";

console.log("STOCK UI");

function getStockStatus(stock) {
  if (stock === 0) {
    return { status: "Rupture stock", badge: "danger" };
  } else if (stock <= 5) {
    return { status: "Faible stock", badge: "warning" };
  }
  return { status: "OK", badge: "success" };
}

function renderProducts(products) {
  const tbody = document.getElementById("productsBody");
  tbody.innerHTML = ""; //reset before rendering

  products.forEach((product) => {
    const stock = parseInt(product.stock || 0);
    const { status, badge } = getStockStatus(stock);

    const row = `
        <tr>
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>${product.category}</td>
            <td>${product.brand}</td>
            <td>${product.model}</td>
            <td class="text-end">${parseFloat(product.price).toFixed(2)} $</td>
            <td class="text-end">${stock}</td>
            <td><span class="badge text-bg-${badge}">${status}</span></td>
        </tr>
        `;

    tbody.innerHTML += row;
  });
}

export async function initStockUI() {
  const products = await getProductStock();
  renderProducts(products);
}
initStockUI();
