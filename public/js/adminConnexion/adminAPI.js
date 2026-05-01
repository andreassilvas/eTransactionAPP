document.addEventListener("DOMContentLoaded", async function () {
  try {
    const res = await fetch("/eTransactionAPP/api/currentuser");

    if (!res.ok) {
      console.error("User API error:", res.status);
      return;
    }

    const data = await res.json();

    console.log("Current user:", data);
  } catch (err) {
    console.error("Fetch error:", err);
  }
});
