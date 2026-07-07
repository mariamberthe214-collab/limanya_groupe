<script setup>
const whatsappLink = 'https://wa.me/0564613584?text=Bonjour%20LIMANYA%20Groupe%20%2D%20je%20souhaite%20un%20devis%20pour%20un%20projet.'

const stats = [
  { value: '15+', label: "Années d'expertise" },
  { value: '200+', label: 'Projets livrés' },
  { value: '100%', label: 'Satisfaction client' },
  { value: '24/7', label: 'Accompagnement' },
]

const categories = [
  {
    icon: 'bi-building',
    title: 'Construction & Travaux Publics',
    description: 'Construction immobilière, implantation de poteaux de réseaux, achat/vente de terrains, plans, devis et suivi de chantier.',
    action: '/btp',
  },
  {
    icon: 'bi-droplet-half',
    title: 'Assainissement',
    description: 'Solutions écologiques : fosses septiques biodigesteurs, caniveaux et égouts, traitement des eaux usées.',
    action: '/assainissement',
  },
  {
    icon: 'bi-water',
    title: 'Forages & Énergies renouvelables',
    description: "Forages, châteaux d'eau, installation de pompes et systèmes photovoltaïques pour un accès durable à l'eau.",
    action: '/forage',
  },
  {
    icon: 'bi-house-gear',
    title: 'Gestion immobilière',
    description: 'Vente et gestion de biens immobiliers avec sérieux et confiance pour valoriser votre patrimoine.',
    action: '/realisations-btp-services-de-construction',
  },
  {
    icon: 'bi-tools',
    title: 'Ventes de matériels de forage',
    description: 'Une sélection de matériels de forage adaptés à chaque besoin hydraulique, conçus pour le terrain.',
    action: '/materiels-de-forages',
  },
  {
    icon: 'bi-lightning-charge',
    title: 'Énergie solaire',
    description: 'Installation de systèmes solaires photovoltaïques pour une énergie propre, fiable et économique.',
    action: '/forage',
  },
]

const highlights = [
  { icon: 'bi-patch-check', title: 'Expertise reconnue', text: "Fort de son savoir-faire dans le BTP et la gestion immobilière." },
  { icon: 'bi-diagram-3', title: 'Solutions complètes', text: 'Construction, assainissement et énergies renouvelables réunis.' },
  { icon: 'bi-clock-history', title: 'Respect des délais', text: 'Engagement qualité rigoureux sur chaque chantier.' },
  { icon: 'bi-tree', title: 'Approche durable', text: 'Bâtir un avenir solide et respectueux de l’environnement.' },
]
</script>

<template>
  <!-- HERO -->
  <section class="home-hero d-flex align-items-center text-white">
    <div class="overlay"></div>
    <div class="container py-5 position-relative">
      <div class="row align-items-center g-5">
        <div class="col-lg-7">
          <span class="hero-eyebrow">BTP • Forages hydrauliques • Énergies renouvelables</span>
          <h1 class="hero-title mt-4">Nous construisons <span class="text-amber">vos rêves</span></h1>
          <p class="lead text-white-75 mt-4">Découvrez tous nos services pour bâtir, gérer et assainir vos projets : construction, gestion immobilière, forages hydrauliques et énergies durables.</p>
          <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
            <router-link to="/contacts" class="btn btn-primary btn-lg">Demander un devis</router-link>
            <a :href="whatsappLink" target="_blank" class="btn btn-outline-light btn-lg"><i class="bi bi-whatsapp me-2"></i>WhatsApp</a>
          </div>
          <div class="d-flex align-items-center gap-2 mt-4">
            <span class="text-warning fs-5">★★★★★</span>
            <span class="text-white-75">Satisfaction garantie sur chaque projet</span>
          </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
          <div class="hero-panel p-4">
            <h3 class="h5 mb-4 text-white">Nos expertises</h3>
            <ul class="list-unstyled mb-0 hero-list">
              <li><i class="bi bi-check-circle-fill text-amber me-2"></i>Construction & Immobilier</li>
              <li><i class="bi bi-check-circle-fill text-amber me-2"></i>Solutions hydrauliques complètes</li>
              <li><i class="bi bi-check-circle-fill text-amber me-2"></i>Assainissement écologique</li>
              <li><i class="bi bi-check-circle-fill text-amber me-2"></i>Énergies renouvelables</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <section class="stats-bar bg-ink">
    <div class="container">
      <div class="row text-center py-4">
        <div class="col-6 col-md-3 py-3" v-for="stat in stats" :key="stat.label">
          <div class="stat-value">{{ stat.value }}</div>
          <div class="stat-label">{{ stat.label }}</div>
        </div>
      </div>
    </div>
  </section>

  <!-- EXPERTISE -->
  <section class="py-5 my-4">
    <div class="container">
      <div class="row gy-5 align-items-center">
        <div class="col-lg-6">
          <span class="eyebrow">Notre savoir-faire</span>
          <h2 class="mt-3">Une expertise complète pour construire, équiper et gérer vos projets</h2>
          <p class="text-muted mt-3">Fort de son expertise dans le BTP et la gestion immobilière, LIMANYA GROUPE maîtrise l'ensemble de la chaîne de valeur, de la conception à la gestion de votre patrimoine.</p>
          <ul class="list-unstyled mt-4 feature-list">
            <li><i class="bi bi-arrow-right-circle text-amber me-2"></i>Construction immobilière et travaux publics</li>
            <li><i class="bi bi-arrow-right-circle text-amber me-2"></i>Forages, châteaux d'eau et pompes de distribution</li>
            <li><i class="bi bi-arrow-right-circle text-amber me-2"></i>Assainissement écologique et traitement des eaux</li>
            <li><i class="bi bi-arrow-right-circle text-amber me-2"></i>Installation de systèmes solaires photovoltaïques</li>
          </ul>
          <router-link to="/apropos" class="btn btn-outline-primary mt-3">En savoir plus sur nous</router-link>
        </div>
        <div class="col-lg-6">
          <div class="expertise-img rounded-4 overflow-hidden shadow">
            <img src="/images/hero-construction.png" alt="Chantier de construction Limanya Groupe" class="w-100 h-100" style="object-fit: cover;" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SERVICES -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <span class="eyebrow">Nos services</span>
        <h2 class="mt-3">Découvrez une expertise complète</h2>
        <p class="text-muted mt-2 mx-auto" style="max-width: 620px;">De la construction à la gestion immobilière, en passant par l'assainissement et les forages hydrauliques.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-4" v-for="category in categories" :key="category.title">
          <div class="card service-card h-100 border-0 p-4">
            <div class="service-icon mb-3"><i :class="`bi ${category.icon}`"></i></div>
            <h5 class="mb-2">{{ category.title }}</h5>
            <p class="text-muted flex-grow-1">{{ category.description }}</p>
            <router-link :to="category.action" class="service-link mt-2">
              En savoir plus <i class="bi bi-arrow-right ms-1"></i>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- WHY -->
  <section class="py-5 my-4">
    <div class="container">
      <div class="text-center mb-5">
        <span class="eyebrow">Pourquoi nous choisir</span>
        <h2 class="mt-3">Un engagement total envers la qualité</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3" v-for="item in highlights" :key="item.title">
          <div class="card why-card h-100 border-0 p-4">
            <div class="why-icon mb-3"><i :class="`bi ${item.icon}`"></i></div>
            <h6 class="fw-bold">{{ item.title }}</h6>
            <p class="text-muted mb-0 small">{{ item.text }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section text-white">
    <div class="container">
      <div class="cta-card text-center">
        <h2 class="text-white mb-3">Prêt à bâtir votre projet ?</h2>
        <p class="lead text-white-75 mb-4 mx-auto" style="max-width: 640px;">Contactez-nous pour un accompagnement personnalisé en BTP, forage hydraulique et énergies renouvelables.</p>
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
          <router-link to="/contacts" class="btn btn-primary btn-lg">Contactez-nous</router-link>
          <a :href="whatsappLink" target="_blank" class="btn btn-outline-light btn-lg"><i class="bi bi-whatsapp me-2"></i>Discutons sur WhatsApp</a>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.home-hero {
  min-height: 92vh;
  background-image: linear-gradient(120deg, rgba(23, 20, 15, 0.85) 0%, rgba(23, 20, 15, 0.55) 60%, rgba(23, 20, 15, 0.4) 100%), url('/images/hero-construction.png');
  background-size: cover;
  background-position: center;
  position: relative;
}
.home-hero .position-relative {
  z-index: 1;
}
.hero-eyebrow {
  display: inline-block;
  letter-spacing: 0.2em;
  font-size: 0.78rem;
  font-weight: 600;
  text-transform: uppercase;
  color: #f6e7da;
  border: 1px solid rgba(255, 255, 255, 0.25);
  padding: 0.5rem 1rem;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.06);
}
.hero-title {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  color: #fff;
  font-size: clamp(2.4rem, 5vw, 4.2rem);
  line-height: 1.05;
  letter-spacing: -0.02em;
}
.hero-panel {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.16);
  border-radius: 1.25rem;
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  animation: fadeUp 0.9s ease both;
}
.hero-list li {
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 0.85rem;
}

/* Stats */
.stat-value {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  font-size: 2.4rem;
  color: var(--lg-amber);
  line-height: 1;
}
.stat-label {
  color: #cfc7b6;
  font-size: 0.9rem;
  margin-top: 0.4rem;
}

/* Expertise */
.expertise-img {
  aspect-ratio: 4 / 3;
}
.feature-list li {
  color: var(--lg-ink-soft);
  margin-bottom: 0.75rem;
  font-weight: 500;
}

/* Services */
.service-card {
  display: flex;
  flex-direction: column;
}
.service-card:hover {
  transform: translateY(-6px);
  border-color: var(--lg-amber) !important;
  box-shadow: 0 24px 48px rgba(191, 87, 28, 0.14);
}
.service-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: var(--lg-amber-soft);
  color: var(--lg-amber);
  font-size: 1.5rem;
}
.service-link {
  font-weight: 600;
  color: var(--lg-amber);
}
.service-link:hover {
  color: var(--lg-amber-dark);
}

/* Why */
.why-card {
  background: var(--lg-cream-2);
}
.why-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 52px;
  height: 52px;
  border-radius: 14px;
  background: var(--lg-ink);
  color: #fff;
  font-size: 1.3rem;
}

/* CTA */
.cta-section {
  padding: 4rem 0 5rem;
}
.cta-card {
  background: linear-gradient(135deg, #17140f 0%, #2c281f 100%);
  border-radius: 2rem;
  padding: 4rem 2rem;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(191, 87, 28, 0.3);
}
.cta-card::before {
  content: '';
  position: absolute;
  top: -60px;
  right: -60px;
  width: 240px;
  height: 240px;
  background: radial-gradient(circle, rgba(191, 87, 28, 0.35), transparent 70%);
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
