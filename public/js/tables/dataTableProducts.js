document.addEventListener("DOMContentLoaded", function () {
  const table = document.getElementById("tbl-products");

  new DataTable(table, {
    pageLength: 10,
    lengthChange: false,
    ordering: true,
    searching: true,
  });
});
