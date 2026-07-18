<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'

const projets = ref([])
const chargement = ref(true)
const filtreActif = ref(null)
const lightbox = ref(null) // { type: 'image'|'video', src, titre }

const sections = [
  {
    title: 'Forages Hydrauliques',
    description: 'Forages villageois, agricoles, industriels, essais de pompage, réhabilitation et maintenance.',
    icon: 'bi-moisture',
    link: '/forage',
  },
  {
    title: 'Études Géophysiques',
    description: 'Recherche d\u2019eau, études géophysiques et implantation des forages.',
    icon: 'bi-graph-up-arrow',
    link: '/etudes-geophysiques',
  },
  {
    title: 'Assainissement',
    description: 'Réseaux d\u2019assainissement, gestion des eaux usées, fosses septiques et fosses biodigesteurs.',
    icon: 'bi-recycle',
    link: '/assainissement',
  },
  {
    title: 'BTP & Génie Civil',
    description: 'Construction, génie civil, VRD et travaux publics.',
    icon: 'bi-bricks',
    link: '/btp',
  },
  {
    title: 'Vente de matériels',
    description: 'Pompes, panneaux solaires, PVC hydrauliques, équipements et accessoires.',
    icon: 'bi-box-seam',
    link: '/materiels-de-forages',
  },
]

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
}

const estVideo = (url) => {
  if (!url) return false
  return /\/video\//.test(url) || /\.(mp4|webm|ogg|mov|m4v)(\?|$)/i.test(url)
}

const categories = computed(() => {
  const set = new Set(projets.value.map((p) => p.categorie).filter(Boolean))
  return Array.from(set)
})

const projetsFiltres = computed(() => {
  if (!filtreActif.value) return projets.value
  return projets.value.filter((p) => p.categorie === filtreActif.value)
})

const chargerProjets = async () => {
  chargement.value = true
  try {
    const response = await api.get('/realisations')
    projets.value = response.data
  } catch (error) {
    console.error(error)
  } finally {
    chargement.value = false
  }
}

const ouvrirLightbox = (projet) => {
  if (projet.video) {
    lightbox.value = { type: 'video', src: getImageUrl(projet.video), titre: projet.titre }
  } else if (projet.image) {
    lightbox.value = {
      type: estVideo(projet.image) ? 'video' : 'image',
      src: getImageUrl(projet.image),
      titre: projet.titre,
    }
  } else {
    return
  }
  document.body.style.overflow = 'hidden'
}

const fermerLightbox = () => {
  lightbox.value = null
  document.body.style.overflow = ''
}

onMounted(chargerProjets)
</script>

<template>
  <!-- HERO -->
  <section class="real-hero contour-field-dark text-white">
    <div class="container position-relative py-5">
      <div class="row align-items-end g-4 py-4">
        <div class="col-lg-8">
          <span class="hero-eyebrow" v-reveal>
            <i class="bi bi-tools me-2"></i>Services &amp; Réalisations
          </span>
          <h1 class="real-hero-title mt-4" v-reveal="80">
            Forages, géophysique, assainissement &amp; <span class="text-amber">BTP</span>
          </h1>
          <p class="lead text-white-75 mt-3 mb-0" v-reveal="160" style="max-width: 660px">
            Spécialiste des forages hydrauliques en Côte d&apos;Ivoire, LIMANYA Groupe
            accompagne vos projets d&apos;études géophysiques, d&apos;assainissement, de BTP
            et de vente d&apos;équipements.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="real-count-badge" v-reveal="220">
            <span class="real-count-value">{{ projets.length }}</span>
            <span class="real-count-label">projet{{ projets.length > 1 ? 's' : '' }} réalisé{{ projets.length > 1 ? 's' : '' }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="real-hero-wave" aria-hidden="true"></div>
  </section>

  <!-- SERVICES -->
  <section class="page-section py-5">
    <div class="container">
      <div class="text-center mb-5" v-reveal>
        <span class="section-eyebrow">Nos expertises</span>
        <h2 class="section-heading mt-3">Une offre complète, du sol à la surface</h2>
      </div>

      <div class="row g-4">
        <div
          class="col-md-6 col-lg-4"
          v-for="(section, i) in sections"
          :key="section.title"
          v-reveal="i * 90"
        >
          <router-link :to="section.link" class="service-card h-100">
            <div class="service-card-icon"><i class="bi" :class="section.icon"></i></div>
            <h5 class="service-card-title">{{ section.title }}</h5>
            <p class="service-card-text">{{ section.description }}</p>
            <span class="service-card-link">
              En savoir plus <i class="bi bi-arrow-right"></i>
            </span>
          </router-link>
        </div>
      </div>
    </div>
  </section>

  <!-- PORTFOLIO -->
  <section class="page-section portfolio-section py-5">
    <div class="container">
      <div class="text-center mb-4" v-reveal>
        <span class="section-eyebrow">Portfolio</span>
        <h2 class="section-heading mt-3">Nos projets réalisés</h2>
        <p class="text-muted">Un aperçu de nos chantiers menés à bien sur le terrain.</p>
      </div>

      <!-- FILTRES -->
      <div
        class="filter-bar d-flex flex-wrap justify-content-center gap-2 mb-5"
        v-if="categories.length > 1"
        v-reveal
      >
        <button
          class="filter-chip"
          :class="{ 'is-active': filtreActif === null }"
          @click="filtreActif = null"
        >
          Toutes
        </button>
        <button
          v-for="cat in categories"
          :key="cat"
          class="filter-chip"
          :class="{ 'is-active': filtreActif === cat }"
          @click="filtreActif = cat"
        >
          {{ cat }}
        </button>
      </div>

      <!-- CHARGEMENT -->
      <div v-if="chargement" class="row g-4">
        <div class="col-md-6 col-lg-4" v-for="n in 6" :key="n">
          <div class="skeleton skeleton-card"></div>
        </div>
      </div>

      <!-- ÉTAT VIDE -->
      <div v-else-if="projetsFiltres.length === 0" class="empty-state text-center py-5" v-reveal>
        <div class="empty-icon mb-3"><i class="bi bi-images"></i></div>
        <h4>Aucun projet publié pour le moment</h4>
        <p class="text-muted mb-0">Nos réalisations seront bientôt mises en ligne.</p>
      </div>

      <!-- GRILLE -->
      <div v-else class="row g-4">
        <div
          class="col-md-6 col-lg-4"
          v-for="(projet, i) in projetsFiltres"
          :key="projet.id"
          v-reveal="i * 80"
        >
          <article class="project-card h-100">
            <div
              class="project-media"
              :class="{ 'is-clickable': projet.image || projet.video }"
              @click="ouvrirLightbox(projet)"
            >
              <template v-if="projet.video">
                <video
                  :src="getImageUrl(projet.video)"
                  :poster="projet.image ? getImageUrl(projet.image) : ''"
                  preload="metadata"
                  class="project-img"
                  muted
                ></video>
                <span class="media-badge"><i class="bi bi-play-fill"></i></span>
                <span class="media-play"><i class="bi bi-play-circle-fill"></i></span>
              </template>
              <template v-else-if="projet.image && estVideo(projet.image)">
                <video
                  :src="getImageUrl(projet.image)"
                  preload="metadata"
                  class="project-img"
                  muted
                ></video>
                <span class="media-badge"><i class="bi bi-play-fill"></i></span>
                <span class="media-play"><i class="bi bi-play-circle-fill"></i></span>
              </template>
              <template v-else-if="projet.image">
                <img
                  :src="getImageUrl(projet.image)"
                  :alt="projet.titre"
                  class="project-img"
                  loading="lazy"
                />
                <span class="media-badge"><i class="bi bi-camera-fill"></i></span>
                <span class="media-zoom"><i class="bi bi-arrows-fullscreen"></i></span>
              </template>
              <div v-else class="project-noimg"><i class="bi bi-image"></i></div>
            </div>
            <div class="project-body">
              <span v-if="projet.categorie" class="project-tag">{{ projet.categorie }}</span>
              <h5 class="project-title">{{ projet.titre }}</h5>
              <p class="project-meta" v-if="projet.lieu || projet.date_realisation">
                <span v-if="projet.lieu"><i class="bi bi-geo-alt me-1"></i>{{ projet.lieu }}</span>
                <span v-if="projet.lieu && projet.date_realisation" class="project-meta-sep">·</span>
                <span v-if="projet.date_realisation">
                  <i class="bi bi-calendar3 me-1"></i>{{ formatDate(projet.date_realisation) }}
                </span>
              </p>
              <p class="project-desc">{{ projet.description }}</p>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>

  <!-- LIGHTBOX -->
  <transition name="lb-fade">
    <div v-if="lightbox" class="lightbox" @click="fermerLightbox">
      <button class="lightbox-close" @click="fermerLightbox" aria-label="Fermer">
        <i class="bi bi-x-lg"></i>
      </button>
      <div class="lightbox-inner" @click.stop>
        <video
          v-if="lightbox.type === 'video'"
          :src="lightbox.src"
          controls
          autoplay
          class="lightbox-media"
        ></video>
        <img v-else :src="lightbox.src" :alt="lightbox.titre" class="lightbox-media" />
        <p class="lightbox-caption">{{ lightbox.titre }}</p>
      </div>
    </div>
  </transition>
</template>

<style scoped>
/* ---------- HERO ---------- */
.real-hero {
  position: relative;
  background: linear-gradient(135deg, #17140f 0%, #2c281f 100%);
  overflow: hidden;
}
.real-hero::before {
  content: '';
  position: absolute;
  top: -120px;
  right: -80px;
  width: 380px;
  height: 380px;
  background: radial-gradient(circle, rgba(191, 87, 28, 0.4), transparent 70%);
  pointer-events: none;
  animation: floatGlow 9s ease-in-out infinite;
}
@keyframes floatGlow {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(-24px, 26px); }
}
.real-hero .container {
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
.real-hero-title {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  color: #fff;
  font-size: clamp(2rem, 4.4vw, 3.4rem);
  line-height: 1.08;
  letter-spacing: -0.02em;
}
.real-count-badge {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.15rem;
  padding: 1.4rem 1.6rem;
  border-radius: 1.25rem;
  background: rgba(255, 255, 255, 0.07);
  border: 1px solid rgba(255, 255, 255, 0.16);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}
.real-count-value {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 2.8rem;
  font-weight: 700;
  color: var(--lg-amber);
  line-height: 1;
}
.real-count-label {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}
.real-hero-wave {
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 40px;
  background: var(--lg-cream);
  clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 55%);
}

/* ---------- SECTION HEADINGS ---------- */
.section-eyebrow {
  display: inline-block;
  letter-spacing: 0.18em;
  font-size: 0.78rem;
  font-weight: 700;
  text-transform: uppercase;
  color: var(--lg-amber);
}
.section-heading {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  font-size: clamp(1.6rem, 3vw, 2.4rem);
  letter-spacing: -0.02em;
}

/* ---------- CARTES SERVICES ---------- */
.service-card {
  display: flex;
  flex-direction: column;
  padding: 2rem 1.75rem;
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1.25rem;
  text-decoration: none;
  color: inherit;
  box-shadow: 0 14px 34px rgba(23, 20, 15, 0.05);
  transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
  position: relative;
  overflow: hidden;
}
.service-card::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: var(--lg-amber);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.35s ease;
}
.service-card:hover {
  transform: translateY(-6px);
  border-color: var(--lg-amber);
  box-shadow: 0 26px 54px rgba(191, 87, 28, 0.14);
}
.service-card:hover::after {
  transform: scaleX(1);
}
.service-card-icon {
  width: 58px;
  height: 58px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 16px;
  background: var(--lg-amber-soft);
  color: var(--lg-amber);
  font-size: 1.6rem;
  margin-bottom: 1.1rem;
  transition: transform 0.3s ease;
}
.service-card:hover .service-card-icon {
  transform: rotate(-6deg) scale(1.05);
}
.service-card-title {
  font-weight: 700;
  margin-bottom: 0.6rem;
}
.service-card-text {
  color: var(--lg-muted);
  font-size: 0.92rem;
  line-height: 1.65;
  flex-grow: 1;
  margin-bottom: 1rem;
}
.service-card-link {
  font-weight: 600;
  color: var(--lg-amber);
  font-size: 0.9rem;
}
.service-card-link i {
  transition: transform 0.25s ease;
}
.service-card:hover .service-card-link i {
  transform: translateX(5px);
}

/* ---------- PORTFOLIO ---------- */
.portfolio-section {
  background: var(--lg-surface-alt, #f2eee4);
}
.filter-bar {
  --_gap: 0.5rem;
}
.filter-chip {
  border: 1px solid var(--lg-line);
  background: var(--lg-surface);
  color: var(--lg-ink);
  font-size: 0.88rem;
  font-weight: 600;
  padding: 0.5rem 1.15rem;
  border-radius: 999px;
  cursor: pointer;
  transition: all 0.22s ease;
}
.filter-chip:hover {
  border-color: var(--lg-amber);
  color: var(--lg-amber);
}
.filter-chip.is-active {
  background: var(--lg-amber);
  border-color: var(--lg-amber);
  color: #fff;
  box-shadow: 0 8px 18px rgba(191, 87, 28, 0.28);
}

/* ---------- CARTES PROJET ---------- */
.project-card {
  display: flex;
  flex-direction: column;
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1.25rem;
  overflow: hidden;
  box-shadow: 0 14px 34px rgba(23, 20, 15, 0.05);
  transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
}
.project-card:hover {
  transform: translateY(-6px);
  border-color: var(--lg-amber);
  box-shadow: 0 26px 54px rgba(191, 87, 28, 0.14);
}
.project-media {
  position: relative;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  background: var(--lg-ink);
}
.project-media.is-clickable {
  cursor: pointer;
}
.project-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
.project-card:hover .project-img {
  transform: scale(1.08);
}
.project-noimg {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: rgba(255, 255, 255, 0.3);
  font-size: 2.4rem;
}
.media-badge {
  position: absolute;
  top: 0.8rem;
  right: 0.8rem;
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: rgba(23, 20, 15, 0.6);
  color: #fff;
  font-size: 0.95rem;
  backdrop-filter: blur(4px);
  z-index: 2;
}
.media-zoom {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 1.6rem;
  background: rgba(23, 20, 15, 0.35);
  opacity: 0;
  transition: opacity 0.3s ease;
}
.project-media:hover .media-zoom {
  opacity: 1;
}
.media-play {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 3rem;
  opacity: 0.9;
  transition: transform 0.3s ease, opacity 0.3s ease;
  pointer-events: none;
  text-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
}
.project-media:hover .media-play {
  transform: scale(1.12);
  opacity: 1;
}
.project-body {
  padding: 1.4rem 1.5rem 1.6rem;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}
.project-tag {
  align-self: flex-start;
  background: var(--lg-amber-soft);
  color: var(--lg-amber);
  font-size: 0.75rem;
  font-weight: 700;
  padding: 0.3rem 0.75rem;
  border-radius: 999px;
  margin-bottom: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}
.project-title {
  font-size: 1.15rem;
  line-height: 1.3;
  margin-bottom: 0.5rem;
}
.project-meta {
  font-size: 0.82rem;
  color: var(--lg-muted);
  margin-bottom: 0.6rem;
}
.project-meta-sep {
  margin: 0 0.4rem;
}
.project-desc {
  color: var(--lg-muted);
  font-size: 0.92rem;
  line-height: 1.65;
  flex-grow: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* ---------- ÉTAT VIDE ---------- */
.empty-state {
  border: 2px dashed var(--lg-line);
  border-radius: 1.5rem;
  background: var(--lg-surface);
}
.empty-icon {
  width: 76px;
  height: 76px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 20px;
  background: var(--lg-amber-soft);
  color: var(--lg-amber);
  font-size: 2rem;
}

/* ---------- SQUELETTES ---------- */
.skeleton {
  border-radius: 1.25rem;
  background: linear-gradient(100deg, #e8e3d5 30%, #f2eee4 50%, #e8e3d5 70%);
  background-size: 200% 100%;
  animation: shimmer 1.4s infinite;
}
.skeleton-card {
  height: 360px;
}
@keyframes shimmer {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

/* ---------- LIGHTBOX ---------- */
.lightbox {
  position: fixed;
  inset: 0;
  z-index: 1080;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
  background: rgba(15, 13, 9, 0.92);
  backdrop-filter: blur(6px);
}
.lightbox-inner {
  max-width: 960px;
  width: 100%;
  text-align: center;
  animation: lbZoom 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.lightbox-media {
  max-width: 100%;
  max-height: 78vh;
  border-radius: 1rem;
  box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
}
.lightbox-caption {
  color: rgba(255, 255, 255, 0.85);
  margin-top: 1rem;
  font-weight: 500;
}
.lightbox-close {
  position: absolute;
  top: 1.2rem;
  right: 1.2rem;
  width: 46px;
  height: 46px;
  border-radius: 50%;
  border: 0;
  background: rgba(255, 255, 255, 0.12);
  color: #fff;
  font-size: 1.2rem;
  cursor: pointer;
  transition: background 0.2s ease, transform 0.2s ease;
}
.lightbox-close:hover {
  background: var(--lg-amber);
  transform: rotate(90deg);
}
@keyframes lbZoom {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}
.lb-fade-enter-active,
.lb-fade-leave-active {
  transition: opacity 0.25s ease;
}
.lb-fade-enter-from,
.lb-fade-leave-to {
  opacity: 0;
}
</style>
