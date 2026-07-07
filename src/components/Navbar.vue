<template>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <router-link class="navbar-brand d-flex align-items-center gap-2" to="/">
        <span class="brand-mark"><i class="bi bi-buildings-fill"></i></span>
        <span>LIMANYA<span class="brand-accent"> Groupe</span></span>
      </router-link>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list fs-2"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><router-link class="nav-link" to="/">Accueil</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/apropos">À propos</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/forage">Forage</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/btp">BTP</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/assainissement">Assainissement</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/location">Location</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/realisations-btp-services-de-construction">Réalisations</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/actualites">Actualités</router-link></li>
          <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
            <router-link class="btn btn-primary btn-sm px-3" to="/contacts">
              Devis gratuit <i class="bi bi-arrow-right ms-1"></i>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
</script>

<style scoped>
.brand-mark {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: var(--lg-amber);
  color: #fff;
  font-size: 1.1rem;
}
.navbar-brand {
  font-family: 'Space Grotesk', sans-serif;
}
</style>
