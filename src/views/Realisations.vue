<template>
  <section class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <span class="eyebrow text-uppercase">Services & Réalisations</span>
        <h1 class="display-6 fw-bold mt-3">Forages Hydrauliques, Études Géophysiques, Assainissement et BTP</h1>
        <p class="text-muted mt-3">Spécialiste des forages hydrauliques en Côte d'Ivoire, LIMANYA Groupe accompagne aussi vos projets d'études géophysiques, d'assainissement, de BTP et de vente d'équipements.</p>
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

  <section class="py-5 bg-light">
    <div class="container">

      <div class="text-center mb-4">
        <span class="eyebrow text-uppercase">Portfolio</span>
        <h2 class="fw-bold mt-3">Nos projets réalisés</h2>
        <p class="text-muted">Un aperçu de nos chantiers menés à bien sur le terrain.</p>
      </div>

      <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" v-if="categories.length > 1">
        <button
          class="btn btn-sm"
          :class="filtreActif === null ? 'btn-primary' : 'btn-outline-secondary'"
          @click="filtreActif = null">
          Toutes
        </button>
        <button
          v-for="cat in categories"
          :key="cat"
          class="btn btn-sm"
          :class="filtreActif === cat ? 'btn-primary' : 'btn-outline-secondary'"
          @click="filtreActif = cat">
          {{ cat }}
        </button>
      </div>

      <div v-if="chargement" class="text-center text-muted py-5">
        <span class="spinner-border spinner-border-sm me-2"></span>Chargement des projets...
      </div>

      <div v-else-if="projetsFiltres.length === 0" class="text-center text-muted py-5">
        <i class="bi bi-images fs-2 d-block mb-2"></i>
        Aucun projet publié pour le moment.
      </div>

      <div v-else class="row g-4">
        <div class="col-md-6 col-lg-4" v-for="projet in projetsFiltres" :key="projet.id">
          <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="ratio ratio-4x3 bg-secondary bg-opacity-10">
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
                class="w-100 h-100 object-fit-cover">
              <div v-else class="d-flex align-items-center justify-content-center text-muted">
                <i class="bi bi-image fs-1"></i>
              </div>
            </div>
            <div class="p-4">
              <span v-if="projet.categorie" class="badge bg-amber-soft text-amber mb-2">{{ projet.categorie }}</span>
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
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'

const sections = [
  {
    title: 'Forages Hydrauliques',
    description: 'Forages villageois, agricoles, industriels, essais de pompage, réhabilitation et maintenance.',
    link: '/forage',
  },
  {
    title: 'Études Géophysiques',
    description: 'Recherche d\u2019eau, études géophysiques et implantation des forages.',
    link: '/etudes-geophysiques',
  },
  {
    title: 'Assainissement',
    description: 'Réseaux d\u2019assainissement, gestion des eaux usées, fosses septiques et fosses biodigesteurs.',
    link: '/assainissement',
  },
  {
    title: 'BTP & Génie Civil',
    description: 'Construction, génie civil, VRD et travaux publics.',
    link: '/btp',
  },
  {
    title: 'Vente de matériels',
    description: 'Pompes, panneaux solaires, PVC hydrauliques, équipements et accessoires.',
    link: '/materiels-de-forages',
  },
]

const projets = ref([])
const chargement = ref(true)
const filtreActif = ref(null)

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
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

onMounted(chargerProjets)
</script>

<style scoped>
.bg-amber-soft {
  background: #f6e7da;
}
</style>
