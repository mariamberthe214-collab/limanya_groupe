<script setup>
const values = [
  { title: 'Expertise technique', text: 'Maîtrise du forage, de la géophysique et des infrastructures de génie civil.' },
  { title: 'Approche durable', text: 'Respect des normes locales et des enjeux environnementaux sur chaque chantier.' },
  { title: 'Engagement qualité', text: 'Contrôle rigoureux et respect des délais du devis à la livraison.' },
  { title: 'Support réactif', text: 'Un accompagnement de proximité et un suivi après la fin du projet.' },
]
</script>

<template>
  <section class="about-hero py-5 bg-light">
    <div class="container py-3">
      <div class="row align-items-center gx-5 gy-4">
        <div class="col-lg-6">
          <span class="eyebrow">À propos de nous</span>
          <h1 class="display-6 fw-bold mt-3">Limanya Groupe : votre partenaire en forage, BTP et assainissement</h1>
          <p class="lead text-muted mt-4">Nous accompagnons les collectivités, entreprises et promoteurs immobiliers avec des solutions techniques et un savoir-faire terrain consolidé.</p>
          <p>Depuis notre création, nous avons réalisé des projets de forage d&apos;eau, des études géophysiques, des constructions de réseaux et des services de location de matériel pour garantir des résultats fiables et durables.</p>
        </div>
        <div class="col-lg-6">
          <div class="hero-media ratio ratio-4x3">
            <img src="/images/about-team.png" alt="Équipe d'ingénieurs Limanya sur un chantier" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row gx-4 gy-4">
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="service-icon mb-3"><i class="bi bi-bullseye"></i></div>
            <h3 class="h5">Notre mission</h3>
            <p class="text-muted mb-0">Offrir des prestations techniques performantes et sécurisées, tout en privilégiant la proximité avec nos clients et partenaires.</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="service-icon mb-3"><i class="bi bi-gem"></i></div>
            <h3 class="h5">Nos valeurs</h3>
            <p class="text-muted mb-0">Fiabilité, transparence, engagement et professionnalisme guident chacune de nos interventions.</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="service-icon mb-3"><i class="bi bi-diagram-3"></i></div>
            <h3 class="h5">Notre approche</h3>
            <p class="text-muted mb-0">Études précises, planification rigoureuse et exécution optimisée pour des résultats maîtrisés à chaque étape.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <span class="eyebrow">Pourquoi choisir Limanya Groupe</span>
        <h2 class="mt-3">Des solutions locales avec une expertise technique forte</h2>
      </div>
      <div class="row gy-4">
        <div class="col-md-6" v-for="(value, index) in values" :key="value.title">
          <div class="card border-0 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center mb-3">
              <span class="badge bg-primary rounded-pill me-3">0{{ index + 1 }}</span>
              <h5 class="mb-0">{{ value.title }}</h5>
            </div>
            <p class="text-muted mb-0">{{ value.text }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
