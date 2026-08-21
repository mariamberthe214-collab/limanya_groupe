<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'
import { getImageUrl } from '../utils/images'
import { buildWhatsAppLink } from '../utils/whatsapp'

const route = useRoute()

const article = ref(null)
const autres = ref([])
const chargement = ref(true)
const introuvable = ref(false)

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const paragraphes = computed(() => {
  if (!article.value?.contenu) return []
  return article.value.contenu.split(/\n+/).filter((p) => p.trim() !== '')
})

const lienPartage = computed(() => {
  if (!article.value) return '#'
  return buildWhatsAppLink(`Bonjour, j'ai vu cet article de LIMANYA Groupe : "${article.value.titre}" — ${window.location.href}`)
})

const lightbox = ref(null)

const ouvrirLightbox = (media) => {
  lightbox.value = { type: media.type, src: getImageUrl(media.url), titre: article.value?.titre }
  document.body.style.overflow = 'hidden'
}

const fermerLightbox = () => {
  lightbox.value = null
  document.body.style.overflow = ''
}

// Médias additionnels de la galerie (au-delà de la couverture déjà affichée en haut)
const galerieRestante = computed(() => {
  if (!article.value?.medias?.length) return []
  const couverture = article.value.video ? article.value.video : article.value.image
  return article.value.medias.filter((m) => m.url !== couverture)
})

const charger = async () => {
  chargement.value = true
  introuvable.value = false
  window.scrollTo({ top: 0 })
  try {
    const response = await api.get(`/actualites/${route.params.id}`)
    article.value = response.data

    const autresRes = await api.get('/actualites?statut=Publié&limit=4')
    autres.value = autresRes.data.filter((a) => a.id !== article.value.id).slice(0, 3)
  } catch (error) {
    console.error(error)
    introuvable.value = true
  } finally {
    chargement.value = false
  }
}

onMounted(charger)
</script>

<template>
  <div v-if="chargement" class="text-center text-muted py-5">
    <span class="spinner-border spinner-border-sm me-2"></span>Chargement de l'article...
  </div>

  <div v-else-if="introuvable" class="container py-5 text-center">
    <i class="bi bi-emoji-frown fs-1 text-muted d-block mb-3"></i>
    <h4>Cet article est introuvable</h4>
    <router-link to="/actualites" class="btn btn-primary mt-3">Retour aux actualités</router-link>
  </div>

  <template v-else-if="article">

    <section class="page-hero py-5">
      <div class="container py-3">
        <router-link to="/actualites" class="back-link">
          <i class="bi bi-arrow-left me-2"></i>Retour aux actualités
        </router-link>
        <span class="badge-date mt-2">
          <i class="bi bi-calendar3 me-1"></i>{{ formatDate(article.date_publication) }}
        </span>
        <h1 class="mt-3" v-reveal>{{ article.titre }}</h1>
        <p class="article-meta mt-2" v-if="article.auteur">
          <i class="bi bi-person-circle me-1"></i>{{ article.auteur }}
        </p>
      </div>
    </section>

    <section class="py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">

            <div v-if="article.image || article.video" class="article-media mb-4" v-reveal @click="ouvrirLightbox(article.video ? { type: 'video', url: article.video } : { type: 'image', url: article.image })">
              <video
                v-if="article.video"
                :src="getImageUrl(article.video)"
                :poster="article.image ? getImageUrl(article.image) : ''"
                controls
                class="w-100 rounded-4"></video>
              <img
                v-else
                :src="getImageUrl(article.image)"
                :alt="article.titre"
                class="w-100 rounded-4"
                loading="lazy"
                crossorigin="anonymous"
                referrerpolicy="no-referrer" />
            </div>

            <div v-if="galerieRestante.length" class="gallery-strip mb-5" v-reveal="80">
              <div
                class="gallery-thumb"
                v-for="(m, i) in galerieRestante"
                :key="i"
                @click="ouvrirLightbox(m)">
                <video v-if="m.type === 'video'" :src="getImageUrl(m.url)" muted preload="metadata"></video>
                <img v-else :src="getImageUrl(m.url)" :alt="article.titre" loading="lazy" crossorigin="anonymous" referrerpolicy="no-referrer" />
                <span v-if="m.type === 'video'" class="gallery-play"><i class="bi bi-play-circle-fill"></i></span>
              </div>
            </div>

            <div class="article-body" v-reveal="100">
              <p v-for="(p, i) in paragraphes" :key="i">{{ p }}</p>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-5 pt-4 border-top">
              <a :href="lienPartage" target="_blank" class="btn btn-outline-success btn-sm">
                <i class="bi bi-whatsapp me-1"></i>Partager sur WhatsApp
              </a>
              <router-link to="/contacts" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-envelope me-1"></i>Nous contacter
              </router-link>
            </div>

          </div>
        </div>

        <div v-if="autres.length" class="mt-5 pt-5 border-top">
          <h4 class="mb-4 text-center">À lire aussi</h4>
          <div class="row g-4">
            <div class="col-md-4" v-for="post in autres" :key="post.id">
              <router-link :to="`/actualites/${post.id}`" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                  <div class="ratio ratio-16x9 bg-secondary bg-opacity-10">
                    <img
                      v-if="post.image"
                      :src="getImageUrl(post.image)"
                      :alt="post.titre"
                      class="w-100 h-100 object-fit-cover"
                      loading="lazy"
                      crossorigin="anonymous"
                      referrerpolicy="no-referrer">
                    <div v-else class="d-flex align-items-center justify-content-center text-muted">
                      <i class="bi bi-newspaper fs-2"></i>
                    </div>
                  </div>
                  <div class="p-3">
                    <h6 class="mb-0">{{ post.titre }}</h6>
                  </div>
                </div>
              </router-link>
            </div>
          </div>
        </div>

      </div>
    </section>

  </template>

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
      </div>
    </div>
  </transition>
</template>

<style scoped>
.back-link {
  color: rgba(255, 255, 255, 0.75);
  font-size: 0.88rem;
  text-decoration: none;
  display: inline-block;
  margin-bottom: 1.25rem;
}
.back-link:hover {
  color: #fff;
}
.badge-date {
  display: inline-block;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: var(--lg-amber);
  background: rgba(246, 231, 218, 0.92);
  padding: 0.35rem 0.8rem;
  border-radius: 999px;
}
.page-hero h1 {
  color: #fff;
  font-size: clamp(1.6rem, 3.4vw, 2.4rem);
  max-width: 780px;
}
.article-meta {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
}
.article-media {
  position: relative;
  width: 100%;
  max-height: 480px;
  border-radius: 1rem;
  overflow: hidden;
  background: #000;
}
.article-media img,
.article-media video {
  display: block;
  width: 100%;
  max-height: 480px;
  height: auto;
  object-fit: cover;
}
.article-media {
  cursor: zoom-in;
}
.article-body p {
  font-size: 1.05rem;
  line-height: 1.85;
  color: var(--lg-ink-soft);
  margin-bottom: 1.4rem;
}

.gallery-strip {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(96px, 1fr));
  gap: 0.6rem;
}
.gallery-thumb {
  position: relative;
  aspect-ratio: 1 / 1;
  border-radius: 0.6rem;
  overflow: hidden;
  cursor: zoom-in;
  background: #000;
}
.gallery-thumb img,
.gallery-thumb video {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.25s ease;
}
.gallery-thumb:hover img,
.gallery-thumb:hover video {
  transform: scale(1.08);
}
.gallery-play {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 1.4rem;
  background: rgba(0, 0, 0, 0.25);
  pointer-events: none;
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
  max-height: 82vh;
  border-radius: 1rem;
  box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
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