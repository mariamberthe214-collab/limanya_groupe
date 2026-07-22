<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'

const items = [
  'Recherche de fracture dans le sol pour localiser les points d\u2019eau souterraine',
  'Étude complète du sous-sol par méthodes électriques et sismiques',
  'Rapport de forage géophysique détaillé avant tout chantier',
  'Recommandations précises d\u2019implantation pour maximiser les chances de succès',
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
    const response = await api.get('/realisations', { params: { categorie: 'Études Géophysiques' } })
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
          <span class="eyebrow">Études Géophysiques</span>
          <h1 class="display-5 fw-bold mt-3">Localisez l'eau avec précision grâce à nos études géophysiques</h1>
          <p class="lead mt-4">Avant tout forage, LIMANYA Groupe réalise une étude approfondie du sous-sol afin de maximiser les chances de succès et d'optimiser vos investissements.</p>
          <ul class="list-unstyled mt-4">
            <li class="d-flex align-items-center mb-3" v-for="item in items" :key="item">
              <i class="bi bi-check-circle-fill text-amber me-2"></i>{{ item }}
            </li>
          </ul>
          <router-link to="/contacts" class="btn btn-primary btn-lg mt-2">Demander un devis</router-link>
        </div>
        <div class="col-lg-6">
          <div class="hero-media ratio ratio-4x3">
            <img src="/images/service-forage.png" alt="Étude géophysique et recherche d'eau" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="method-section py-5">

    <div class="container">

      <div class="text-center mb-5" v-reveal>
        <span class="section-tag">Notre méthode</span>
        <h2 class="mt-3">Les étapes de notre étude géophysique</h2>
        <p class="method-intro">
          Un processus rigoureux pour maximiser les chances de succès de votre forage.
        </p>
      </div>

      <div class="steps-container">

        <div class="step-item">
          <div class="step-number">01</div>
          <div class="step-content">
            <div class="step-icon">🧭</div>
            <h4>Visite du terrain</h4>
            <p>Reconnaissance du site et prise en compte des contraintes du terrain.</p>
          </div>
        </div>

        <div class="step-item">
          <div class="step-number">02</div>
          <div class="step-content">
            <div class="step-icon">📡</div>
            <h4>Mesures géophysiques</h4>
            <p>Relevés par méthodes électriques et sismiques sur les zones ciblées.</p>
          </div>
        </div>

        <div class="step-item">
          <div class="step-number">03</div>
          <div class="step-content">
            <div class="step-icon">🔎</div>
            <h4>Recherche de fracture dans le sol</h4>
            <p>Analyse des données pour localiser les fractures porteuses d'eau souterraine.</p>
          </div>
        </div>

        <div class="step-item">
          <div class="step-number">04</div>
          <div class="step-content">
            <div class="step-icon">📄</div>
            <h4>Rapport de forage géophysique</h4>
            <p>Remise d'un rapport détaillé avec recommandations précises d'implantation.</p>
          </div>
        </div>

      </div>

    </div>

  </section>

  <section class="py-5 bg-light">
    <div class="container">

      <div class="text-center mb-5" v-reveal>
        <span class="eyebrow">Nos réalisations</span>
        <h2 class="mt-3">Études géophysiques réalisées</h2>
      </div>

      <div v-if="chargement" class="text-center text-muted py-5">
        <span class="spinner-border spinner-border-sm me-2"></span>Chargement...
      </div>

      <div v-else-if="realisations.length === 0" class="text-center text-muted py-5">
        <i class="bi bi-images fs-2 d-block mb-2"></i>
        Nos études géophysiques seront bientôt visibles ici.
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
.method-section{
  background:#f8f8f8;
}
.method-section h2{
  font-size:36px;
  font-weight:700;
}
.method-intro{
  max-width:700px;
  margin:auto;
  color:#666;
}
.steps-container{
  display:flex;
  justify-content:space-between;
  gap:25px;
  position:relative;
}
.steps-container::before{
  content:"";
  position:absolute;
  top:45px;
  left:8%;
  right:8%;
  height:3px;
  background:#bf571c;
}
.step-item{
  flex:1;
  position:relative;
  text-align:center;
}
.step-number{
  width:90px;
  height:90px;
  background:#bf571c;
  color:white;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:28px;
  font-weight:700;
  margin:auto;
  position:relative;
  z-index:2;
}
.step-content{
  margin-top:30px;
  background:white;
  padding:25px 20px;
  border-radius:15px;
  min-height:220px;
  box-shadow:0 10px 30px rgba(0,0,0,.06);
}
.step-icon{
  font-size:40px;
  margin-bottom:15px;
}
.step-content h4{
  font-weight:700;
  font-size:18px;
}
@media(max-width:992px){
  .steps-container{
    flex-direction:column;
  }
  .steps-container::before{
    display:none;
  }
}
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
