<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'

const services = [
  {
    icon: 'bi-water',
    title: 'Réseaux d\u2019assainissement',
    description: 'Construction et maintenance de caniveaux, égouts et réseaux de collecte des eaux.',
  },
  {
    icon: 'bi-droplet-half',
    title: 'Gestion des eaux usées',
    description: 'Solutions complètes de collecte et de traitement adaptées à chaque site.',
  },
  {
    icon: 'bi-buildings',
    title: 'Ouvrages d\u2019assainissement',
    description: 'Conception et réalisation d\u2019ouvrages d\u2019assainissement durables et conformes aux normes.',
  },
  {
    icon: 'bi-droplet-fill',
    title: 'Fosses septiques',
    description: 'Installation et rénovation de fosses septiques classiques pour particuliers et entreprises.',
  },
  {
    icon: 'bi-recycle',
    title: 'Fosses biodigesteurs',
    description: 'Installation de fosses septiques biodigesteurs modernes, écologiques et durables.',
  },
]

const realisations = ref([])
const chargement = ref(true)

const lightbox = ref(null) // { type: 'image'|'video', src, titre }

const estVideo = (url) => {
  if (!url) return false
  return /\/video\//.test(url) || /\.(mp4|webm|ogg|mov|m4v)(\?|$)/i.test(url)
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

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
}

const chargerRealisations = async () => {
  chargement.value = true
  try {
    const response = await api.get('/realisations', { params: { categorie: 'Assainissement' } })
    realisations.value = response.data
  } catch (error) {
    console.error(error)
  } finally {
    chargement.value = false
  }
}

onMounted(chargerRealisations)
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
      <div class="text-center mb-5" v-reveal>
        <span class="eyebrow">Nos prestations</span>
        <h2 class="mt-3">Des solutions complètes de gestion des eaux</h2>
      </div>
      <div class="row g-4" v-reveal>
        <div class="col-md-6 col-lg-4" v-for="service in services" :key="service.title">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="service-icon mb-3"><i :class="['bi', service.icon]"></i></div>
            <h5>{{ service.title }}</h5>
            <p class="text-muted mb-0">{{ service.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">

      <div class="text-center mb-5" v-reveal>
        <span class="eyebrow">Nos réalisations</span>
        <h2 class="mt-3">Projets d'assainissement réalisés</h2>
      </div>

      <div v-if="chargement" class="text-center text-muted py-5">
        <span class="spinner-border spinner-border-sm me-2"></span>Chargement...
      </div>

      <div v-else-if="realisations.length === 0" class="text-center text-muted py-5">
        <i class="bi bi-images fs-2 d-block mb-2"></i>
        Nos réalisations en eaux et assainissement seront bientôt visibles ici.
      </div>

      <div v-else class="row g-4">
        <div class="col-md-6 col-lg-4" v-for="projet in realisations" :key="projet.id">
          <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="ratio ratio-4x3 bg-secondary bg-opacity-10 media-clickable" @click="ouvrirLightbox(projet)">
              <video
                v-if="projet.video"
                :src="getImageUrl(projet.video)"
                :poster="projet.image ? getImageUrl(projet.image) : ''"
                controls
                class="w-100 h-100" style="object-fit:cover;"></video>
              <img
                v-else-if="projet.image"
                :src="getImageUrl(projet.image)"
                :alt="projet.titre"
                class="w-100 h-100 object-fit-contain p-2 bg-white">
              <div v-else class="d-flex align-items-center justify-content-center text-muted">
                <i class="bi bi-image fs-1"></i>
              </div>
            </div>
            <div class="p-4">
              <h5>{{ projet.titre }}</h5>
              <p class="text-muted small mb-2" v-if="projet.lieu || projet.date_realisation">
                <i class="bi bi-geo-alt me-1" v-if="projet.lieu"></i>{{ projet.lieu }}
                <span v-if="projet.lieu && projet.date_realisation"> · </span>
                <i class="bi bi-calendar3 me-1" v-if="projet.date_realisation"></i>{{ formatDate(projet.date_realisation) }}
              </p>
              <p class="text-muted">{{ projet.description }}</p>
            </div>
          </div>
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
