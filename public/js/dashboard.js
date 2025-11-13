document.addEventListener("DOMContentLoaded", () => {
  const data = window.DASHBOARD_DATA || {};
  const healthPct = Number(data.healthPct || 0);
  const stockByCategory = data.stockByCategory || [];
  const expStatus = data.expStatus || {};

  initHealthChart(healthPct);
  initCategoryChart(stockByCategory);
  initExpeditionChart(expStatus);
});

function initHealthChart(healthPct) {
  const ctx = document.getElementById("chart-health");
  if (!ctx) return;

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["OK", "À risque"],
      datasets: [
        {
          data: [healthPct, 100 - healthPct],
          backgroundColor: ["#00738A", "#e9ecef"],
          borderWidth: 0,
        },
      ],
    },
    options: {
      cutout: "60%",
      //   plugins: { legend: { display: false } },
      plugins: {
        legend: { position: "bottom" },
      },
    },
  });
}

function initCategoryChart(stockByCategory) {
  const ctx = document.getElementById("chart-category");
  if (!ctx) return;

  const labels = stockByCategory.map((r) => r.category);
  const values = stockByCategory.map((r) => Number(r.total_stock));

  new Chart(ctx, {
    type: "bar",
    data: {
      labels,
      datasets: [
        {
          data: values,
          borderWidth: 1,
          backgroundColor: ["#DFEFB2"],
        },
      ],
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true },
      },
    },
  });
}

function initExpeditionChart(expStatus) {
  const ctx = document.getElementById("chart-expedition");
  if (!ctx) return;

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["En attente", "Expédiées", "Livrées"],
      datasets: [
        {
          data: [
            Number(expStatus.pending || 0),
            Number(expStatus.shipped || 0),
            Number(expStatus.delivered || 0),
          ],
          backgroundColor: ["#5C5CFF", "#ADEBB3", "#198754"],
          borderWidth: 0,
        },
      ],
    },
    options: {
      cutout: "60%",
      plugins: {
        legend: { position: "bottom" },
      },
    },
  });
}
