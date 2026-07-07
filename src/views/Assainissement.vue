<script setup>
const services = [
  {
    icon: 'bi-recycle',
    title: 'Fosses septiques biodigesteurs',
    description: 'Installation, réparation et rénovation de systèmes d\u2019assainissement écologiques et durables.',
  },
  {
    icon: 'bi-water',
    title: 'Réseaux d\u2019égouts et caniveaux',
    description: 'Construction et maintenance de caniveaux, égouts et réseaux de collecte des eaux pluviales.',
  },
  {
    icon: 'bi-droplet-half',
    title: 'Traitement des eaux usées',
    description: 'Conception de solutions complètes de collecte et de traitement adaptées à chaque site.',
  },
  {
    icon: 'bi-shield-check',
    title: 'Conformité & durabilité',
    description: 'Ouvrages respectant les normes environnementales locales pour un impact maîtrisé.',
  },
]
</script>

<template>
  <section class="page-hero py-5">
    <div class="container py-4">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <span class="eyebrow">Assainissement Écologique</span>
          <h1 class="display-5 fw-bold mt-3">Des infrastructures durables pour la gestion des eaux</h1>
          <p class="lead mt-4">LIMANYA GROUPE conçoit et réalise des fosses septiques biodigesteurs, des caniveaux, des égouts et des solutions complètes de traitement des eaux usées.</p>
          <router-link to="/contacts" class="btn btn-primary btn-lg mt-3">Demander une étude</router-link>
        </div>
        <div class="col-lg-6">
          <div class="hero-media ratio ratio-4x3">
            <img src="/images/service-assainissement.png" alt="Chantier d'assainissement et de drainage" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <span class="eyebrow">Nos prestations</span>
        <h2 class="mt-3">Des solutions complètes de gestion des eaux</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-6" v-for="service in services" :key="service.title">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="service-icon mb-3"><i :class="['bi', service.icon]"></i></div>
            <h5>{{ service.title }}</h5>
            <p class="text-muted mb-0">{{ service.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
