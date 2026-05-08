document.addEventListener("DOMContentLoaded", async function () {
  try {
    const res = await fetch("/api/currentuser");

    if (!res.ok) {
      console.error("User API error:", res.status);
      return;
    }

    const data = await res.json();
    console.log("Current user:", data);
    console.log("User name:", data.client.name);

    if (data.client.name) {
      document.getElementById("clientName").textContent =
        `${data.client.name} ${data.client.lastname}`;
    }
  } catch (err) {
    console.error("Fetch error:", err);
  }
});
