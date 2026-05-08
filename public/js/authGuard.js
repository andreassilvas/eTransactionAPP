document.addEventListener("DOMContentLoaded", () => {
  //   let inactivityTimer;
  //   const INACTIVITY_TIME = 15 * 60 * 1000;
  //   async function logoutUser() {
  //     try {
  //       await fetch("/api/logout", {
  //         method: "POST",
  //       });
  //     } catch (err) {
  //       console.error("Logout error:", err);
  //     }
  //     sessionStorage.removeItem("sessionWarningShown");
  //     alert("Session expirée après inactivité.");
  //     window.location.href = "/";
  //   }
  //   function resetTimer() {
  //     clearTimeout(inactivityTimer);
  //     inactivityTimer = setTimeout(() => {
  //       logoutUser();
  //     }, INACTIVITY_TIME);
  //   }
  //   [
  //     "mousemove",
  //     "mousedown",
  //     "keypress",
  //     "touchstart",
  //     "scroll",
  //     "click",
  //   ].forEach((event) => {
  //     document.addEventListener(event, resetTimer, true);
  //   });
  //   resetTimer();
});
