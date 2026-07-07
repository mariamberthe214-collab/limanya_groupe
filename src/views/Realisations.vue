<template>
  <section class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <span class="eyebrow text-uppercase">Services & Réalisations</span>
        <h1 class="display-6 fw-bold mt-3">Construction Bâtiment, Assainissement et Forages hydrauliques</h1>
        <p class="text-muted mt-3">Nous bâtissons vos projets : construction immobilière, implantation de réseaux, forages hydrauliques, assainissement écologique et gestion immobilière.</p>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-4" v-for="section in sections" :key="section.title">
          <div class="card border-0 shadow-sm h-100 p-4 d-flex flex-column">
            <div class="service-icon mb-3"><i class="bi bi-bricks"></i></div>
            <h5>{{ section.title }}</h5>
            <p class="text-muted flex-grow-1">{{ section.description }}</p>
            <router-link :to="section.link" class="fw-semibold text-amber">En savoir plus <i class="bi bi-arrow-right"></i></router-link>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const sections = [
  {
    title: 'Construction Bâtiment & Travaux Publics (BTP)',
    description: 'Bâtiment, Travaux Publics et gestion de chantier avec prise en charge complète du devis à la livraison.',
    link: '/realisations-btp-services-de-construction',
  },
  {
    title: 'Assainissement',
    description: 'Fosses septiques biodigesteurs, caniveaux, égouts et traitement des eaux usées.',
    link: '/realisations-btp-services-de-construction',
  },
  {
    title: 'Forages hydrauliques & Énergies renouvelables',
    description: 'Forages d’eau, châteaux d’eau, installations de pompes et systèmes photovoltaïques.',
    link: '/realisations-btp-services-de-construction',
  },
  {
    title: 'Gestion immobilière',
    description: 'Vente et gestion de biens immobiliers pour sécuriser et valoriser votre patrimoine.',
    link: '/realisations-btp-services-de-construction',
  },
  {
    title: 'Ventes de matériels de forages',
    description: 'Une sélection de matériels de forage adaptés aux besoins hydrauliques de vos chantiers.',
    link: '/materiels-de-forages',
  },
]
</script>


