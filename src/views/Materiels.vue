<script setup>
const items = [
  'Pompes solaires, électriques et hybrides',
  'Équipements robustes pour forages géotechniques',
  'Châteaux d\u2019eau et systèmes de distribution',
  'Assistance technique pour installation et maintenance',
]
</script>

<template>
  <section class="page-hero py-5">
    <div class="container py-4">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <span class="eyebrow">Matériels de Forages</span>
          <h1 class="display-5 fw-bold mt-3">Ventes de matériels de forages adaptés à vos besoins hydrauliques</h1>
          <p class="lead mt-4">Une sélection de matériels de forage conçus pour répondre aux exigences du terrain et faciliter vos travaux.</p>
          <ul class="list-unstyled mt-4">
            <li class="d-flex align-items-center mb-3" v-for="item in items" :key="item">
              <i class="bi bi-check-circle-fill text-amber me-2"></i>{{ item }}
            </li>
          </ul>
          <router-link to="/contacts" class="btn btn-primary btn-lg mt-2">Demander un devis</router-link>
        </div>
        <div class="col-lg-6">
          <div class="hero-media ratio ratio-4x3">
            <img src="/images/service-materiel.png" alt="Matériel et pompes de forage" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
