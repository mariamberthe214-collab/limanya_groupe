<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'

const services = [
  {
    icon: 'bi-building',
    title: 'Construction',
    description: 'Plans 2D et 3D, devis, gros-œuvre et second-œuvre pour donner vie à vos bâtiments.',
  },
  {
    icon: 'bi-diagram-3',
    title: 'Génie Civil',
    description: 'Fondations, ouvrages d\u2019art et structures techniques conçus pour durer.',
  },
  {
    icon: 'bi-signpost-split',
    title: 'VRD',
    description: 'Voirie et Réseaux Divers : voirie, assainissement, réseaux d\u2019eau et d\u2019électricité.',
  },
  {
    icon: 'bi-cone-striped',
    title: 'Travaux Publics',
    description: 'Aménagements et infrastructures publiques réalisés avec rigueur et sécurité.',
  },
]

const realisations = ref([])
const chargement = ref(true)

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
}

const chargerRealisations = async () => {
  chargement.value = true
  try {
    const response = await api.get('/realisations', { params: { categorie: 'BTP & Génie Civil' } })
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
          <span class="eyebrow">BTP &amp; Génie Civil</span>
          <h1 class="display-5 fw-bold mt-3">Construction, génie civil et travaux publics</h1>
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

  <section class="py-5 bg-light">
    <div class="container">

      <div class="text-center mb-5">
        <span class="eyebrow">Nos réalisations</span>
        <h2 class="mt-3">Projets BTP réalisés par LIMANYA Groupe</h2>
      </div>

      <div v-if="chargement" class="text-center text-muted py-5">
        <span class="spinner-border spinner-border-sm me-2"></span>Chargement...
      </div>

      <div v-else-if="realisations.length === 0" class="text-center text-muted py-5">
        <i class="bi bi-images fs-2 d-block mb-2"></i>
        Nos réalisations BTP seront bientôt visibles ici.
      </div>

      <div v-else class="row g-4">
        <div class="col-md-6 col-lg-4" v-for="projet in realisations" :key="projet.id">
          <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="ratio ratio-4x3 bg-secondary bg-opacity-10">
              <img
                v-if="projet.image"
                :src="getImageUrl(projet.image)"
                :alt="projet.titre"
                class="w-100 h-100 object-fit-cover">
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
</template>
