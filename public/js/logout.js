document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".logout").forEach((el) => {
    el.addEventListener("click", async function (e) {
      e.preventDefault();
      e.stopPropagation();

      try {
        const res = await fetch("/api/logout", {
          method: "POST",
        }).then(() => {
          // reset modal flag session will expire after 15 minutes of inactivity
          sessionStorage.removeItem("sessionWarningShown");

          window.location.href = "/";
        });

        console.log("logout response:", await res.json());
      } catch (err) {
        console.error(err);
      }

      window.location.href = "/";
    });
  });
});
