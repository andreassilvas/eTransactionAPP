document.addEventListener("DOMContentLoaded", function () {
  const table = document.getElementById("tbl-releve-bancaire");

  new DataTable(table, {
    pageLength: 7,
    lengthChange: false,
    ordering: true,
    searching: true,
  });
});
