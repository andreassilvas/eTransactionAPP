document.addEventListener("DOMContentLoaded", function () {
  const table = document.getElementById("tbl-releve-bancaire");

  new DataTable(table, {
    pageLength: 10,
    lengthChange: false,
    ordering: true,
    searching: true,
    order: [[0, "desc"]],
  });
});
