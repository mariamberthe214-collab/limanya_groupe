<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'
import { getImageUrl } from '../utils/images'
import { buildWhatsAppLink } from '../utils/whatsapp'

const route = useRoute()
const projet = ref(null)
const chargement = ref(true)
const erreur = ref('')

const galerieRestante = computed(() => {
  if (!projet.value?.medias?.length) return []
  return projet.value.medias.filter((media) =>
    media.url !== projet.value.image && media.url !== projet.value.video,
  )
})

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
}

const chargerProjet = async () => {
  try {
    const response = await api.get(`/realisations/${route.params.id}`)
    projet.value = response.data
  } catch (error) {
    console.error(error)
    erreur.value = error.response?.status === 404
      ? 'Cette réalisation est introuvable.'
      : 'Impossible de charger cette réalisation pour le moment.'
  } finally {
    chargement.value = false
  }
}

onMounted(chargerProjet)
</script>

<template>
  <div v-if="chargement" class="text-center py-5">
    <span class="spinner-border spinner-border-sm me-2"></span>Chargement du projet...
  </div>

  <section v-else-if="erreur" class="project-detail-empty text-center">
    <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
    <h1 class="h3 mt-3">{{ erreur }}</h1>
    <RouterLink to="/realisations-btp-services-de-construction" class="btn btn-primary mt-3">
      Retour aux réalisations
    </RouterLink>
  </section>

  <template v-else-if="projet">
    <section class="project-detail-hero">
      <div class="container py-5">
        <RouterLink to="/realisations-btp-services-de-construction" class="back-link">
          <i class="bi bi-arrow-left me-2"></i>Retour aux réalisations
        </RouterLink>
        <span class="eyebrow mt-4">{{ projet.categorie }}</span>
        <h1 class="mt-3">{{ projet.titre }}</h1>
        <p v-if="projet.lieu || projet.date_realisation" class="project-meta mb-0">
          <i v-if="projet.lieu" class="bi bi-geo-alt me-1"></i>{{ projet.lieu }}
          <span v-if="projet.lieu && projet.date_realisation"> · </span>
          <i v-if="projet.date_realisation" class="bi bi-calendar3 me-1"></i>{{ formatDate(projet.date_realisation) }}
        </p>
      </div>
    </section>

    <main class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-9">
          <div v-if="projet.image" class="project-detail-image mb-4">
            <img :src="getImageUrl(projet.image)" :alt="projet.titre" loading="lazy" />
          </div>
          <div v-if="projet.video" class="project-detail-video mb-4">
            <video :src="getImageUrl(projet.video)" controls preload="metadata" class="w-100"></video>
          </div>
          <div v-if="galerieRestante.length" class="project-detail-gallery mb-4">
            <h2 class="h4 mb-3">Galerie du projet</h2>
            <div class="row g-3">
              <div v-for="media in galerieRestante" :key="media.id || media.url" class="col-6 col-md-4">
                <video v-if="media.type === 'video'" :src="getImageUrl(media.url)" controls preload="metadata" class="w-100 rounded"></video>
                <img v-else :src="getImageUrl(media.url)" :alt="projet.titre" loading="lazy" class="w-100 rounded" />
              </div>
            </div>
          </div>
          <article class="project-detail-content">
            <h2>À propos de cette réalisation</h2>
            <p>{{ projet.description || 'Découvrez le savoir-faire de LIMANYA Groupe à travers cette réalisation.' }}</p>
          </article>
          <div class="d-flex flex-wrap gap-2 mt-4">
            <RouterLink :to="{ path: '/demande-de-devis', query: { service: projet.categorie, message: `Je souhaite un devis concernant votre réalisation : ${projet.titre}` } }" class="btn btn-primary">
              <i class="bi bi-file-earmark-text me-2"></i>Demander un devis
            </RouterLink>
            <a :href="buildWhatsAppLink(`Bonjour LIMANYA Groupe, je souhaite parler de la réalisation : ${projet.titre}`)" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success">
              <i class="bi bi-whatsapp me-2"></i>Nous contacter sur WhatsApp
            </a>
          </div>
        </div>
      </div>
    </main>
  </template>
</template>

<style scoped>
.project-detail-hero {
  background: var(--lg-ink);
  color: #fff;
}

.project-detail-hero h1 {
  color: #fff;
  max-width: 800px;
  font-size: clamp(2.2rem, 5vw, 4.5rem);
}

.project-detail-hero .eyebrow {
  color: var(--lg-amber-soft);
}

.back-link,
.project-meta {
  color: rgba(255, 255, 255, 0.78);
}

.back-link:hover {
  color: #fff;
}

.project-detail-image {
  overflow: hidden;
  border-radius: 1rem;
  background: var(--lg-cream);
}

.project-detail-video {
  overflow: hidden;
  border-radius: 1rem;
  background: var(--lg-ink);
}

.project-detail-video video {
  display: block;
  width: 100%;
  max-height: 620px;
}

.project-detail-image img {
  display: block;
  width: 100%;
  max-height: 620px;
  object-fit: cover;
}

.project-detail-content {
  max-width: 760px;
}

.project-detail-content h2 {
  font-size: clamp(1.6rem, 3vw, 2.4rem);
}

.project-detail-content p {
  font-size: 1.08rem;
  line-height: 1.8;
}

.project-detail-empty {
  min-height: 60vh;
  display: grid;
  place-content: center;
  padding: 3rem 1rem;
}

@media (max-width: 767.98px) {
  .project-detail-hero h1 {
    font-size: 2.25rem;
  }

  .project-detail-image img {
    max-height: 360px;
  }

  .project-detail-content + .d-flex .btn {
    width: 100%;
  }
}
</style>
