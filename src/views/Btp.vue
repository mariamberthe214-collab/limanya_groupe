<script setup>
const services = [
  {
    icon: 'bi-building',
    title: 'Construction Immobilière',
    description: 'Réalisation de maisons, immeubles et bâtiments professionnels avec un suivi chantier rigoureux.',
  },
  {
    icon: 'bi-cone-striped',
    title: 'Travaux Publics',
    description: 'Voirie, fondations, réseaux et infrastructures pour des ouvrages durables et adaptés au terrain.',
  },
  {
    icon: 'bi-tools',
    title: 'Rénovation & Réhabilitation',
    description: 'Modernisation de structures existantes et réaménagement de bâtiments pour une meilleure performance.',
  },
  {
    icon: 'bi-clipboard-check',
    title: 'Gestion de Chantier',
    description: 'Planification, coordination et contrôle qualité pour assurer le respect des délais et du budget.',
  },
]
</script>

<template>
  <section class="page-hero py-5">
    <div class="container py-4">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <span class="eyebrow">BTP &amp; Infrastructure</span>
          <h1 class="display-5 fw-bold mt-3">Des solutions BTP complètes pour vos projets</h1>
          <p class="lead mt-4">LIMANYA GROUPE accompagne les projets de construction, rénovation et travaux publics avec une expertise terrain solide et une gestion de chantier professionnelle.</p>
          <router-link to="/contacts" class="btn btn-primary btn-lg mt-3">Démarrer un projet</router-link>
        </div>
        <div class="col-lg-6">
          <div class="hero-media ratio ratio-4x3">
            <img src="/images/service-btp.png" alt="Chantier de construction BTP" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <span class="eyebrow">Nos expertises</span>
        <h2 class="mt-3">Un savoir-faire complet du gros œuvre à la livraison</h2>
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
