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

        <a href="#" target="_blank">
          <i class="bi bi-linkedin"></i>
        </a>

        <a href="https://www.facebook.com/limanyagroup" target="_blank">
          <i class="bi bi-facebook"></i>
        </a>

        <a href="#" target="_blank">
          <i class="bi bi-instagram"></i>
        </a>

        <a href="https://www.tiktok.com/@limanya.groupe8?_r=1&_t=ZS-97rV14EWJVc" target="_blank">
          <i class="bi bi-tiktok"></i>
        </a>

      </div>

    </div>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top main-navbar">

    <div class="container">

      <RouterLink class="navbar-brand" to="/">
        <img src="../assets/logo.png" class="logo">
      </RouterLink>

      <button
        class="navbar-toggler"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav">

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

            <a
              class="nav-link dropdown-toggle"
              href="#"
              data-bs-toggle="dropdown">

              Nos Services

            </a>

            <div class="dropdown-menu mega-menu shadow-lg">

              <div class="row">

                <!-- FORAGES HYDRAULIQUES (activité principale) -->

                <div class="col-lg-3">

                  <h6 class="mega-title">
                    <i class="bi bi-droplet-half"></i>
                    Forages Hydrauliques
                  </h6>

                  <RouterLink class="dropdown-item" to="/forage">
                    Forages villageois
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/forage">
                    Forages agricoles
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/forage">
                    Forages industriels
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/forage">
                    Essais de pompage
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/forage">
                    Réhabilitation & maintenance
                  </RouterLink>

                </div>

                <!-- ETUDES GEOPHYSIQUES -->

                <div class="col-lg-3">

                  <h6 class="mega-title">
                    <i class="bi bi-globe2"></i>
                    Études Géophysiques
                  </h6>

                  <RouterLink class="dropdown-item" to="/etudes-geophysiques">
                    Recherche d'eau
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/etudes-geophysiques">
                    Études géophysiques
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/etudes-geophysiques">
                    Implantation des forages
                  </RouterLink>

                </div>

                <!-- Assainissement -->

                <div class="col-lg-3">

                  <h6 class="mega-title">
                    <i class="bi bi-recycle"></i>
                    Assainissement
                  </h6>

                  <RouterLink class="dropdown-item" to="/assainissement">
                    Réseaux d'assainissement
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/assainissement">
                    Gestion des eaux usées
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/assainissement">
                    Fosses septiques
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/assainissement">
                    Fosses biodigesteurs
                  </RouterLink>

                </div>

                <!-- BTP & Genie Civil -->

                <div class="col-lg-3">

                  <h6 class="mega-title">
                    <i class="bi bi-building"></i>
                    BTP & Génie Civil
                  </h6>

                  <RouterLink class="dropdown-item" to="/btp">
                    Construction
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/btp">
                    Génie civil
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/btp">
                    VRD
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/btp">
                    Travaux publics
                  </RouterLink>

                  <RouterLink class="dropdown-item" to="/materiels-de-forages">
                    Vente de matériels
                  </RouterLink>

                </div>

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
import logo from '../assets/logo.png'
</script>

<style scoped>

.mega-menu{

    width:900px;

    left:50% !important;

    transform:translateX(-50%);

    border:none;

    border-radius:22px;

    padding:30px;

    margin-top:18px;

    box-shadow:0 25px 60px rgba(0,0,0,.12);

}

.mega-title{

    color:#F36A21;

    font-weight:700;

    margin-bottom:18px;

    padding-bottom:10px;

    border-bottom:2px solid #f1f1f1;

}

.mega-title i{

    margin-right:8px;

}

.mega-menu .dropdown-item{

    border-radius:10px;

    padding:10px 14px;

    margin-bottom:5px;

}

.mega-menu .dropdown-item:hover{

    background:#F36A21;

    color:white;

    transform:translateX(6px);

}

@media(max-width:991px){

.mega-menu{

width:100%;

transform:none;

left:0 !important;

padding:10px;

}

}
.top-bar{
    background:#222;
    color:#fff;
    padding:10px 0;
    font-size:.92rem;
}

.top-bar .container{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
}

.top-contact{
    display:flex;
    align-items:center;
    gap:25px;
    flex-wrap:wrap;
}

.top-contact a,
.top-contact span{
    color:#fff;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:8px;
}

.top-contact i{
    color:#F36A21;
}

.top-social{
    display:flex;
    gap:12px;
}

.top-social a{
    width:36px;
    height:36px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(255,255,255,.08);
    color:#fff;
    transition:.3s;
    text-decoration:none;
}

.top-social a:hover{
    background:#F36A21;
    transform:translateY(-3px);
}

@media(max-width:991px){

.top-bar{
display:none;
}

}

/* ================= NAVBAR ================= */

.main-navbar {
  background: rgba(255,255,255,.96);
  backdrop-filter: blur(14px);
  box-shadow: 0 8px 30px rgba(0,0,0,.08);
  padding: 14px 0;
  transition: .35s;
}

.logo{
  height:65px;
  transition:.3s;
}

.logo:hover{
  transform:scale(1.05);
}

.navbar-nav{
  gap:10px;
}

.nav-link{
  position:relative;
  color:#2d2d2d;
  font-weight:600;
  padding:12px 18px !important;
  transition:.3s;
}

.nav-link:hover{
  color:#bf571c;
}

.nav-link::after{

  content:"";

  position:absolute;

  left:18px;

  bottom:6px;

  width:0;

  height:2px;

  background:#bf571c;

  transition:.3s;

}

.nav-link:hover::after{

  width:calc(100% - 36px);

}

.router-link-active{

  color:#bf571c !important;

}

.router-link-active::after{

  width:calc(100% - 36px);

}

.dropdown-menu{

  border:none;

  border-radius:16px;

  box-shadow:0 18px 40px rgba(0,0,0,.12);

  padding:12px;

  margin-top:18px;

  animation:menu .25s;

}

@keyframes menu{

from{

opacity:0;

transform:translateY(12px);

}

to{

opacity:1;

transform:translateY(0);

}

}

.dropdown-item{

  border-radius:10px;

  padding:10px 16px;

  transition:.25s;

  font-weight:500;

}

.dropdown-item:hover{

  background:#bf571c;

  color:#fff;

}

.devis-btn{

  background:#bf571c;

  color:#fff;

  padding:13px 28px;

  border-radius:40px;

  font-weight:600;

  transition:.3s;

  box-shadow:0 10px 25px rgba(191,87,28,.35);

}

.devis-btn:hover{

  background:#a94914;

  color:#fff;

  transform:translateY(-3px);

}

.navbar-toggler{

  border:none;

  font-size:1.8rem;

}

.navbar-toggler:focus{

  box-shadow:none;

}
.mega-menu{

    min-width:320px;

    border-radius:18px;

    padding:15px;

}

.dropdown-item{

    padding:12px 16px;

    border-radius:12px;

    margin-bottom:5px;

    font-weight:500;

    transition:.25s;

}

.dropdown-item:hover{

    background:#F36A21;

    color:#fff;

    transform:translateX(5px);

}

.nav-link{

    font-size:16px;

    letter-spacing:.2px;

}

.navbar-brand{

    margin-right:35px;

}

.navbar-nav{

    gap:12px;

}

.nav-link{

    padding:14px 18px !important;

}

/* ================= MOBILE ================= */

@media(max-width:991px){

.top-bar{

display:none;

}

.navbar-collapse{

margin-top:20px;

background:white;

padding:20px;

border-radius:20px;

box-shadow:0 20px 45px rgba(0,0,0,.08);

}

.navbar-nav{

gap:0;

}

.devis-btn{

margin-top:20px;

width:100%;

text-align:center;

}

.dropdown-menu{

box-shadow:none;

margin-top:0;

padding-left:15px;

}

.logo{

height:55px;

}

}
</style>
