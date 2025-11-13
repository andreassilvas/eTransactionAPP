document.addEventListener("DOMContentLoaded", function () {
  const table = document.getElementById("tbl-commands");

  new DataTable(table, {
    pageLength: 10,
    lengthChange: false,
    ordering: true,
    searching: true,
  });
});
