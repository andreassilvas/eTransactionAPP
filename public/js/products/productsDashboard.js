document.addEventListener("DOMContentLoaded", () => {
  const data = window.DASHBOARD_PROD_DATA || {};
  const categoryLabels = data.categoryLabels || [];
  const categoryStocks = data.categoryStocks || [];

  initCategoryChart(categoryLabels, categoryStocks);
  initTableFilter();
});

function initCategoryChart(labels, values) {
  const canvas = document.getElementById("stockByCategory");
  console.log(
    "initCategoryChart, canvas =",
    canvas,
    "labels =",
    labels,
    "values =",
    values
  );
  if (!canvas) return;

  const ctx = canvas.getContext("2d");

  new Chart(ctx, {
    type: "bar",
    data: {
      labels,
      datasets: [
        {
          label: "Quantité en stock",
          data: values,
          backgroundColor: ["#B07C92", "#B5C7EB"],
          borderWidth: 1,
        },
      ],
    },
    options: {
      plugins: {
        legend: { display: false },
      },
      scales: {
        y: { beginAtZero: true },
      },
    },
  });
}

function initTableFilter() {
  const searchInput = document.getElementById("searchInput");
  const table = document.getElementById("productsTable");
  if (!searchInput || !table) return;

  const rows = table.querySelectorAll("tbody tr");

  searchInput.addEventListener("input", () => {
    const q = searchInput.value.toLowerCase();
    rows.forEach((row) => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(q) ? "" : "none";
    });
  });
}
