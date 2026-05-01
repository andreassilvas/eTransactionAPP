document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".logout").forEach((el) => {
    el.addEventListener("click", async function (e) {
      e.preventDefault();
      e.stopPropagation();

      try {
        const res = await fetch("/eTransactionAPP/api/logout", {
          method: "POST",
        });

        console.log("logout response:", await res.json());
      } catch (err) {
        console.error(err);
      }

      window.location.href = "/";
    });
  });
});
