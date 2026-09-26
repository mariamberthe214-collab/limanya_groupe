<template>
  <!-- Top Bar -->
  <div class="top-bar">
    <div class="container">

      <div class="top-contact">

        <a href="tel:+2250777047057">
          <i class="bi bi-telephone-fill"></i>
          +225 07 77 04 70 57
        </a>

        <a href="mailto:contact@limanyagroupe.com">
          <i class="bi bi-envelope-fill"></i>
          contact@limanyagroupe.com
        </a>

        <span>
          <i class="bi bi-geo-alt-fill"></i>
          Port-Bouët • Gonzagueville
        </span>

      </div>

      <div class="top-social">

        <a href="https://www.facebook.com/limanyagroup" target="_blank" rel="noopener noreferrer" aria-label="Facebook LIMANYA Groupe">
          <i class="bi bi-facebook"></i>
        </a>

        <a href="https://www.tiktok.com/@limanya.groupe8?_r=1&_t=ZS-97rV14EWJVc" target="_blank" rel="noopener noreferrer" aria-label="TikTok LIMANYA Groupe">
          <i class="bi bi-tiktok"></i>
        </a>

      </div>

    </div>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top main-navbar">

    <div class="container">

      <RouterLink class="navbar-brand" to="/">
        <img src="../assets/logo.png" class="logo" alt="LIMANYA Groupe">
      </RouterLink>

      <button
        type="button"
        class="navbar-toggler"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Ouvrir le menu de navigation">

        <i class="bi bi-list"></i>

      </button>

      <div
        class="collapse navbar-collapse"
        id="navbarNav">

        <ul class="navbar-nav mx-auto align-items-lg-center">

          <li class="nav-item">
            <RouterLink class="nav-link" to="/">
              Accueil
            </RouterLink>
          </li>

          <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle"
              href="#"
              data-bs-toggle="dropdown">

              Entreprise

            </a>

            <ul class="dropdown-menu shadow-lg">

              <li>
                <RouterLink class="dropdown-item" to="/apropos">
                  <i class="bi bi-building me-2"></i>
                  À propos
                </RouterLink>
              </li>

              <li>
                <RouterLink class="dropdown-item" to="/realisations-btp-services-de-construction">
                  <i class="bi bi-images me-2"></i>
                  Réalisations
                </RouterLink>
              </li>

              <li>
                <RouterLink class="dropdown-item" to="/actualites">
                  <i class="bi bi-newspaper me-2"></i>
                  Actualités
                </RouterLink>
              </li>

            </ul>

          </li>

          <li class="nav-item dropdown position-static">

            <button
              type="button"
              class="nav-link dropdown-toggle"
              data-bs-toggle="dropdown"
              aria-expanded="false">

              Nos Services

            </button>

            <div class="dropdown-menu mega-menu shadow-lg">
              <div class="service-menu-heading">
                <span class="service-menu-eyebrow">Nos domaines d’expertise</span>
                <p>Des solutions adaptées à chaque étape de votre projet.</p>
              </div>

              <div class="service-menu-grid">
                <RouterLink class="service-menu-card" to="/forage">
                  <span class="service-menu-icon"><i class="bi bi-droplet-half"></i></span>
                  <span class="service-menu-copy">
                    <strong>Forages hydrauliques</strong>
                    <small>Étude, réalisation et pompage</small>
                  </span>
                  <i class="bi bi-arrow-up-right service-menu-arrow"></i>
                </RouterLink>

                <RouterLink class="service-menu-card" to="/etudes-geophysiques">
                  <span class="service-menu-icon"><i class="bi bi-globe2"></i></span>
                  <span class="service-menu-copy">
                    <strong>Études géophysiques</strong>
                    <small>Études, mesures et location de matériel</small>
                  </span>
                  <i class="bi bi-arrow-up-right service-menu-arrow"></i>
                </RouterLink>

                <RouterLink class="service-menu-card" to="/assainissement">
                  <span class="service-menu-icon"><i class="bi bi-recycle"></i></span>
                  <span class="service-menu-copy">
                    <strong>Assainissement</strong>
                    <small>Réseaux et traitement des eaux</small>
                  </span>
                  <i class="bi bi-arrow-up-right service-menu-arrow"></i>
                </RouterLink>

                <RouterLink class="service-menu-card" to="/btp">
                  <span class="service-menu-icon"><i class="bi bi-building"></i></span>
                  <span class="service-menu-copy">
                    <strong>BTP &amp; génie civil</strong>
                    <small>Construction, VRD et travaux publics</small>
                  </span>
                  <i class="bi bi-arrow-up-right service-menu-arrow"></i>
                </RouterLink>

                <RouterLink class="service-menu-card" to="/materiels-de-forages">
                  <span class="service-menu-icon"><i class="bi bi-tools"></i></span>
                  <span class="service-menu-copy">
                    <strong>Vente de matériels</strong>
                    <small>Pompes et équipements hydrauliques</small>
                  </span>
                  <i class="bi bi-arrow-up-right service-menu-arrow"></i>
                </RouterLink>

                <RouterLink
                  class="service-menu-card"
                  :to="{ path: '/contacts', query: { sujet: 'Vente de terrains' } }"
                >
                  <span class="service-menu-icon"><i class="bi bi-geo-alt"></i></span>
                  <span class="service-menu-copy">
                    <strong>Vente de terrains</strong>
                    <small>Terrains en lotissement, selon disponibilité</small>
                  </span>
                  <i class="bi bi-arrow-up-right service-menu-arrow"></i>
                </RouterLink>
              </div>
            </div>

          </li>

          <li class="nav-item">
            <RouterLink class="nav-link" to="/contacts">
              Contact
            </RouterLink>
          </li>

        </ul>
        <RouterLink
          class="btn devis-btn"
          to="/contacts">

          Demander un devis

          <i class="bi bi-arrow-right ms-2"></i>

        </RouterLink>

      </div>

    </div>

  </nav>
</template>

<script setup>
import { watch } from 'vue'
import { useRoute } from 'vue-router'
import logo from '../assets/logo.png'

const route = useRoute()

const fermerMenuMobile = () => {
  const menu = document.getElementById('navbarNav')
  const toggler = document.querySelector('[data-bs-target="#navbarNav"]')
  if (!menu || !toggler || !menu.classList.contains('show')) return

  toggler.click()
}

watch(() => route.fullPath, fermerMenuMobile)
</script>

<style scoped>

/* =========================================================
   TOP BAR
========================================================= */

.top-bar {
  background: #17140f;
  color: #fff;
  padding: 8px 0;
  font-size: 0.78rem;
}

.top-bar .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.top-contact {
  display: flex;
  align-items: center;
  gap: 22px;
  flex-wrap: wrap;
}

.top-contact a,
.top-contact span {
  display: flex;
  align-items: center;
  gap: 7px;

  color: rgba(255, 255, 255, 0.78);
  text-decoration: none;

  transition: color 0.2s ease;
}

.top-contact a:hover {
  color: #fff;
}

.top-contact i {
  color: var(--lg-amber);
}

.top-social {
  display: flex;
  align-items: center;
  gap: 7px;
}

.top-social a {
  width: 30px;
  height: 30px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  color: rgba(255, 255, 255, 0.8);
  background: rgba(255, 255, 255, 0.07);

  text-decoration: none;

  transition:
    background 0.25s ease,
    color 0.25s ease,
    transform 0.25s ease;
}

.top-social a:hover {
  background: var(--lg-amber);
  color: #fff;
  transform: translateY(-2px);
}


/* =========================================================
   NAVBAR
========================================================= */

.main-navbar {
  position: sticky;
  top: 0;
  z-index: 1030;

  background: rgba(255, 255, 255, 0.94);

  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);

  border-bottom: 1px solid rgba(23, 20, 15, 0.07);

  box-shadow:
    0 8px 30px rgba(23, 20, 15, 0.06);

  padding: 10px 0;

  transition:
    padding 0.3s ease,
    box-shadow 0.3s ease;
}


/* =========================================================
   LOGO
========================================================= */

.navbar-brand {
  display: flex;
  align-items: center;

  margin-right: 28px;
}

.logo {
  width: auto;
  height: 58px;

  object-fit: contain;

  transition: transform 0.25s ease;
}

.logo:hover {
  transform: scale(1.04);
}


/* =========================================================
   NAVIGATION
========================================================= */

.navbar-nav {
  display: flex;
  align-items: center;

  gap: 4px;
}

.nav-link {
  position: relative;

  display: inline-flex;
  align-items: center;

  padding: 12px 15px !important;

  color: #292621;

  font-size: 0.88rem;
  font-weight: 600;

  white-space: nowrap;

  transition:
    color 0.25s ease;
}

.nav-link:hover {
  color: var(--lg-amber);
}


/* Ligne active */

.nav-link::after {
  content: '';

  position: absolute;

  left: 15px;
  right: 15px;
  bottom: 5px;

  height: 2px;

  background: var(--lg-amber);

  border-radius: 10px;

  transform: scaleX(0);
  transform-origin: center;

  transition: transform 0.25s ease;
}

.nav-link:hover::after,
.nav-link.router-link-active::after {
  transform: scaleX(1);
}

.nav-link.router-link-active {
  color: var(--lg-amber) !important;
}


/* =========================================================
   DROPDOWNS
========================================================= */

.dropdown-menu {
  border: 0;

  border-radius: 16px;

  padding: 10px;

  margin-top: 12px;

  background: #fff;

  box-shadow:
    0 20px 55px rgba(23, 20, 15, 0.13);

  animation: dropdownIn 0.22s ease;
}

@keyframes dropdownIn {
  from {
    opacity: 0;
    transform: translateY(8px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dropdown-item {
  display: flex;
  align-items: center;

  padding: 10px 13px;

  border-radius: 10px;

  color: #292621;

  font-size: 0.85rem;
  font-weight: 500;

  transition:
    background 0.2s ease,
    color 0.2s ease,
    transform 0.2s ease;
}

.dropdown-item:hover {
  background: var(--lg-amber);
  color: #fff;

  transform: translateX(3px);
}


/* =========================================================
   MEGA MENU SERVICES
========================================================= */

.mega-menu {
  width: min(980px, calc(100vw - 40px));

  left: 50% !important;

  transform: translateX(-50%);

  padding: 24px;

  margin-top: 12px;

  border: 0;

  border-radius: 18px;

  background: #fffefa;

  box-shadow:
    0 24px 60px rgba(23, 20, 15, 0.16);
}

.service-menu-heading {
  margin-bottom: 18px;
  padding: 0 2px 14px;
  border-bottom: 1px solid rgba(23, 20, 15, 0.09);
}

.service-menu-eyebrow {
  color: var(--lg-amber);
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
}

.service-menu-heading p {
  margin: 5px 0 0;
  color: #77736c;
  font-size: 0.88rem;
}

.service-menu-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 10px;
}

.service-menu-card {
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 0;
  padding: 14px 12px;
  border: 1px solid rgba(23, 20, 15, 0.08);
  border-radius: 12px;
  background: #fff;
  color: #292621;
  text-decoration: none;
  transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.service-menu-card:hover,
.service-menu-card:focus-visible {
  transform: translateY(-2px);
  border-color: rgba(191, 87, 28, 0.45);
  background: #fffaf3;
  color: #292621;
  box-shadow: 0 10px 22px rgba(23, 20, 15, 0.08);
}

.service-menu-icon {
  display: inline-flex;
  flex: 0 0 42px;
  width: 42px;
  height: 42px;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  background: rgba(191, 87, 28, 0.1);
  color: var(--lg-amber);
  font-size: 1.2rem;
}

.service-menu-copy {
  display: flex;
  flex: 1;
  flex-direction: column;
  min-width: 0;
  gap: 3px;
}

.service-menu-copy strong {
  font-size: 0.84rem;
  font-weight: 700;
  line-height: 1.25;
}

.service-menu-copy small {
  color: #77736c;
  font-size: 0.72rem;
  line-height: 1.35;
}

.service-menu-arrow {
  flex: 0 0 auto;
  color: #a7a198;
  font-size: 0.85rem;
  transition: color 0.2s ease, transform 0.2s ease;
}

.service-menu-card:hover .service-menu-arrow,
.service-menu-card:focus-visible .service-menu-arrow {
  color: var(--lg-amber);
  transform: translate(2px, -2px);
}


/* =========================================================
   BOUTON DEVIS
========================================================= */

.devis-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;

  gap: 4px;

  padding: 11px 19px;

  border: 0;
  border-radius: 999px;

  background: var(--lg-amber);
  color: #fff !important;

  font-size: 0.82rem;
  font-weight: 700;

  white-space: nowrap;

  box-shadow:
    0 9px 22px rgba(191, 87, 28, 0.25);

  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease,
    filter 0.25s ease;
}

.devis-btn:hover {
  color: #fff !important;

  transform: translateY(-2px);

  filter: brightness(1.05);

  box-shadow:
    0 13px 28px rgba(191, 87, 28, 0.32);
}


/* =========================================================
   MENU MOBILE
========================================================= */

.navbar-toggler {
  width: 44px;
  height: 44px;

  display: none;
  align-items: center;
  justify-content: center;

  padding: 0;

  border: 1px solid var(--lg-line);
  border-radius: 12px;

  color: var(--lg-ink);

  font-size: 1.4rem;

  background: var(--lg-surface);

  transition:
    background 0.2s ease,
    color 0.2s ease;
}

.navbar-toggler:hover {
  background: var(--lg-amber);
  color: #fff;
}

.navbar-toggler:focus {
  box-shadow: none;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 991.98px) {

  .navbar-toggler {
    display: flex;
  }

  .top-bar {
    display: none;
  }

  .main-navbar {
    padding: 8px 0;
  }

  .logo {
    height: 52px;
  }

  .navbar-collapse {
    margin-top: 12px;

    padding: 14px;

    border-radius: 18px;

    background: #fff;

    box-shadow:
      0 18px 45px rgba(23, 20, 15, 0.10);
  }

  .navbar-nav {
    align-items: stretch;

    gap: 2px;
  }

  .nav-link {
    width: 100%;

    padding: 11px 12px !important;

    border-radius: 10px;
  }

  .nav-link:hover {
    background: var(--lg-amber-soft);
  }

  .nav-link::after {
    display: none;
  }

  .dropdown-menu {
    margin-top: 0;

    padding: 5px;

    border-radius: 12px;

    box-shadow: none;

    background: var(--lg-surface-alt, #f5f1e8);
  }

  .mega-menu {
    width: 100%;

    left: 0 !important;

    transform: none;

    padding: 10px;

    margin-top: 0;

    border-radius: 14px;
  }

  .service-menu-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px;
  }

  .service-menu-card {
    padding: 11px 9px;
  }

  .service-menu-icon {
    flex-basis: 36px;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    font-size: 1rem;
  }

  .devis-btn {
    width: 100%;

    margin-top: 12px;

    padding: 12px 18px;
  }
}


@media (max-width: 575.98px) {

  .logo {
    height: 48px;
  }

  .main-navbar {
    padding: 6px 0;
  }

  .navbar-collapse {
    padding: 10px;
  }

  .mega-menu {
    padding: 12px;
  }

  .service-menu-grid {
    grid-template-columns: 1fr;
  }

  .service-menu-card {
    padding: 11px;
  }
}

</style>
