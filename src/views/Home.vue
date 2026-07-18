<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'
import { buildWhatsAppLink } from '../utils/whatsapp'

const whatsappLink = buildWhatsAppLink('Bonjour LIMANYA Groupe, je souhaite un devis pour un projet.')

const videos = ref([])
const chargementVideos = ref(true)

const chargerVideos = async () => {
  chargementVideos.value = true
  try {
    const response = await api.get('/realisations')
    videos.value = response.data.filter((r) => r.video).slice(0, 3)
  } catch (error) {
    console.error(error)
  } finally {
    chargementVideos.value = false
  }
}

onMounted(chargerVideos)

const stats = [
  { value: '15+', label: "Années d'expertise" },
  { value: '200+', label: 'Projets livrés' },
  { value: '100%', label: 'Satisfaction client' },
  { value: '24/7', label: 'Accompagnement' },
]

const categories = [
  {
    icon: 'bi-water',
    title: 'Forages Hydrauliques',
    description: 'Forages villageois, agricoles, industriels, essais de pompage, réhabilitation et maintenance.',
    action: '/forage',
  },
  {
    icon: 'bi-globe2',
    title: 'Études Géophysiques',
    description: "Recherche d'eau, études géophysiques et implantation des forages avant tout chantier.",
    action: '/etudes-geophysiques',
  },
  {
    icon: 'bi-recycle',
    title: 'Assainissement',
    description: 'Réseaux d\u2019assainissement, gestion des eaux usées, fosses septiques et fosses biodigesteurs.',
    action: '/assainissement',
  },
  {
    icon: 'bi-building',
    title: 'BTP & Génie Civil',
    description: 'Construction, génie civil, VRD et travaux publics.',
    action: '/btp',
  },
  {
    icon: 'bi-tools',
    title: 'Vente de Matériels',
    description: 'Pompes solaires, électriques et hybrides, panneaux solaires, PVC hydrauliques, matériels de forage et accessoires.',
    action: '/materiels-de-forages',
  },
]

const highlights = [
  { icon: 'bi-patch-check', title: 'Spécialiste du forage', text: "Forages hydrauliques, villageois, agricoles et industriels, réalisés avec rigueur." },
  { icon: 'bi-diagram-3', title: 'Solutions complètes', text: 'Études géophysiques, assainissement, BTP et vente de matériels réunis.' },
  { icon: 'bi-clock-history', title: 'Respect des délais', text: 'Engagement qualité rigoureux sur chaque chantier.' },
  { icon: 'bi-tree', title: 'Approche durable', text: 'Bâtir un avenir solide et respectueux de l’environnement.' },
]
</script>

<template>
  <!-- HERO -->
  <section class="home-hero contour-field-dark d-flex align-items-center text-white">
    <div class="overlay"></div>
    <div class="container py-5 position-relative">
      <div class="row align-items-center g-5">
        <div class="col-lg-7">
          <span class="hero-eyebrow" v-reveal>Forages Hydrauliques • Études Géophysiques • Assainissement • BTP</span>
          <h1 class="hero-title mt-4" v-reveal="80">L'eau, notre <span class="text-amber">expertise</span></h1>
          <p class="lead text-white-75 mt-4" v-reveal="160">LIMANYA Groupe réalise vos forages hydrauliques, villageois, agricoles et industriels, en Côte d'Ivoire. Nous accompagnons aussi vos projets d'études géophysiques, d'assainissement, de BTP et de vente d'équipements.</p>
          <div class="d-flex flex-column flex-sm-row gap-3 mt-4" v-reveal="240">
            <router-link to="/contacts" class="btn btn-primary btn-lg">Demander un devis</router-link>
            <a :href="whatsappLink" target="_blank" class="btn btn-outline-light btn-lg"><i class="bi bi-whatsapp me-2"></i>WhatsApp</a>
          </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
          <div class="hero-panel p-4" v-reveal="200">
            <h3 class="h6 mb-1 text-white">Nos forages descendent jusqu'à</h3>
            <p class="depth-caption mb-4">Profondeurs réelles de nos derniers chantiers</p>
            <div class="depth-scale">
              <div class="depth-scale-line"></div>
              <div class="depth-row">
                <span class="depth-marker">0 m</span>
                <span class="depth-track"><span class="depth-fill" style="width:0%"></span></span>
                <span class="depth-place">Surface</span>
              </div>
              <div class="depth-row">
                <span class="depth-marker">51 m</span>
                <span class="depth-track"><span class="depth-fill" style="width:85%"></span></span>
                <span class="depth-place">Songon</span>
              </div>
              <div class="depth-row">
                <span class="depth-marker">60 m</span>
                <span class="depth-track"><span class="depth-fill" style="width:100%"></span></span>
                <span class="depth-place">Blolequin</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <section class="stats-bar bg-ink">
    <div class="container">
      <div class="row text-center py-4">
        <div class="col-6 col-md-3 py-3" v-for="(stat, i) in stats" :key="stat.label" v-reveal="i * 80">
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
        <div class="col-lg-6" v-reveal>
          <span class="eyebrow">Notre savoir-faire</span>
          <h2 class="mt-3">Une expertise complète autour du forage hydraulique</h2>
          <p class="text-muted mt-3">Spécialiste des forages hydrauliques, LIMANYA GROUPE maîtrise l'ensemble de la chaîne de valeur : de l'étude géophysique à la mise en service, en passant par l'assainissement et le BTP.</p>
          <ul class="list-unstyled mt-4 feature-list">
            <li><i class="bi bi-arrow-right-circle text-amber me-2"></i>Forages villageois, agricoles et industriels</li>
            <li><i class="bi bi-arrow-right-circle text-amber me-2"></i>Études géophysiques et implantation des forages</li>
            <li><i class="bi bi-arrow-right-circle text-amber me-2"></i>Assainissement écologique et fosses biodigesteurs</li>
            <li><i class="bi bi-arrow-right-circle text-amber me-2"></i>Construction, génie civil et vente de matériels</li>
          </ul>
          <router-link to="/apropos" class="btn btn-outline-primary mt-3">En savoir plus sur nous</router-link>
        </div>
        <div class="col-lg-6" v-reveal="150">
          <div class="expertise-img rounded-4 overflow-hidden shadow">
            <img src="/images/hero-construction.png" alt="Chantier de construction Limanya Groupe" class="w-100 h-100" style="object-fit: cover;" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SIGNATURE : LA COURBE DU SOUS-SOL -->
  <section class="py-5 my-4 contour-field">
    <div class="container">
      <div class="text-center mb-5" v-reveal>
        <span class="eyebrow">Notre méthode</span>
        <h2 class="mt-3">Nous lisons le sous-sol avant de forer</h2>
        <p class="text-muted mx-auto" style="max-width:640px">Chaque forage commence par une étude géophysique : nous cartographions les fractures et les nappes avant la première rotation de foret.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4" v-reveal>
          <div class="method-mini">
            <span class="depth-marker">01 · Terrain</span>
            <h5 class="mt-2">Relevés géophysiques</h5>
            <p class="text-muted small mb-0">Mesures électriques et sismiques sur le site.</p>
          </div>
        </div>
        <div class="col-md-4" v-reveal="120">
          <div class="method-mini">
            <span class="depth-marker">02 · Analyse</span>
            <h5 class="mt-2">Recherche de fracture</h5>
            <p class="text-muted small mb-0">Identification précise des zones porteuses d'eau.</p>
          </div>
        </div>
        <div class="col-md-4" v-reveal="240">
          <div class="method-mini">
            <span class="depth-marker">03 · Exécution</span>
            <h5 class="mt-2">Forage & mise en service</h5>
            <p class="text-muted small mb-0">Foration, équipement et essais de pompage.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- VIDÉOS DE CHANTIER -->
  <section class="py-5 my-4" v-if="!chargementVideos && videos.length > 0">
    <div class="container">
      <div class="text-center mb-5" v-reveal>
        <span class="eyebrow">Sur le terrain</span>
        <h2 class="mt-3">Nos chantiers en vidéo</h2>
        <p class="text-muted mx-auto" style="max-width:640px">Un aperçu direct de nos interventions, filmées sur nos sites de forage et d'assainissement.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4" v-for="(v, i) in videos" :key="v.id" v-reveal="i * 100">
          <div class="video-card">
            <video
              :src="getImageUrl(v.video)"
              :poster="v.image ? getImageUrl(v.image) : ''"
              controls
              preload="metadata"
              class="w-100"></video>
            <div class="video-caption">
              <strong>{{ v.titre }}</strong>
              <span v-if="v.lieu" class="text-muted small d-block">{{ v.lieu }}</span>
            </div>
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
        <p class="text-muted mt-2 mx-auto" style="max-width: 620px;">Du forage hydraulique à la vente de matériels, en passant par l'étude géophysique, l'assainissement et le BTP.</p>
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
}
.depth-caption {
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.82rem;
}
.depth-scale {
  display: flex;
  flex-direction: column;
  gap: 1.1rem;
}
.depth-row {
  display: grid;
  grid-template-columns: 3.2rem 1fr 5rem;
  align-items: center;
  gap: 0.85rem;
}
.depth-row .depth-marker {
  color: #f6e7da;
}
.depth-track {
  height: 6px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.14);
  overflow: hidden;
}
.depth-fill {
  display: block;
  height: 100%;
  border-radius: 999px;
  background: linear-gradient(90deg, var(--lg-amber), #f0a15c);
  animation: fillBar 1.4s cubic-bezier(0.16, 1, 0.3, 1) both;
}
@keyframes fillBar {
  from { width: 0 !important; }
}
.depth-place {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.75);
  text-align: right;
}
.method-mini {
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1.1rem;
  padding: 1.75rem;
  height: 100%;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.method-mini:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(23, 20, 15, 0.08);
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

.video-card {
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1.1rem;
  overflow: hidden;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.video-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(23, 20, 15, 0.1);
}
.video-card video {
  display: block;
  aspect-ratio: 4 / 3;
  object-fit: cover;
  background: #000;
}
.video-caption {
  padding: 1rem 1.1rem;
}
</style>
