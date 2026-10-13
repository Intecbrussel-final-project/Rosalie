// ==========================
// LOGIN
// ==========================

const loginOpen = document.querySelector(".login-open");
const loginPanel = document.querySelector("#login-panel");
const loginWrap = document.querySelector(".login-wrap");

if (loginOpen && loginPanel && loginWrap) {

  function toggleLogin(open) {
    loginPanel.classList.toggle("is-open", open);
    loginOpen.setAttribute("aria-expanded", open);
  }

  loginOpen.addEventListener("click", (e) => {
    e.stopPropagation();

    toggleLogin(
      !loginPanel.classList.contains("is-open")
    );
  });

  document.addEventListener("click", (e) => {

    if (!loginWrap.contains(e.target)) {
      toggleLogin(false);
    }

  });

  document.addEventListener("keydown", (e) => {

    if (e.key === "Escape") {
      toggleLogin(false);
    }

  });

  document
    .querySelectorAll("[data-open-login]")
    .forEach((btn) => {

      btn.addEventListener("click", (e) => {

        e.stopPropagation();

        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });

        toggleLogin(true);

        document
          .querySelector("#login-email")
          ?.focus({ preventScroll: true });

      });

    });
}


// ==========================
// LEAFLET MAP
// ==========================

const mapElement = document.getElementById("map");

if (mapElement && typeof L !== "undefined") {

  const latitude = 50.6326;
  const longitude = 5.5797;

  const map = L.map(mapElement).setView(
    [latitude, longitude],
    14
  );

  L.tileLayer(
    "https://tile.openstreetmap.org/{z}/{x}/{y}.png",
    {
      maxZoom: 19,
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }
  ).addTo(map);


  L.marker([latitude, longitude])
    .addTo(map)
    .bindPopup(
      "<strong>Maison Rosalie</strong><br>Liège, Belgique"
    )
    .openPopup();

}