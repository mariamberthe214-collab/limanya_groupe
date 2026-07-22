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

const estVideo = (url) => {
  if (!url) return false
  return /\/video\//.test(url) || /\.(mp4|webm|ogg|mov|m4v)(\?|$)/i.test(url)
}

const paragraphes = computed(() => {
  if (!article.value?.contenu) return []
  return article.value.contenu.split(/\n+/).filter((p) => p.trim() !== '')
})

const lienPartage = computed(() => {
  if (!article.value) return '#'
  return buildWhatsAppLink(`Bonjour, j'ai vu cet article de LIMANYA Groupe : "${article.value.titre}" — ${window.location.href}`)
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

            <div v-if="article.image" class="article-media mb-5" v-reveal>
              <video
                v-if="estVideo(article.image)"
                :src="getImageUrl(article.image)"
                controls
                class="w-100 rounded-4"></video>
              <img
                v-else
                :src="getImageUrl(article.image)"
                :alt="article.titre"
                class="w-100 rounded-4" />
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
                      v-if="post.image && !estVideo(post.image)"
                      :src="getImageUrl(post.image)"
                      :alt="post.titre"
                      class="w-100 h-100 object-fit-cover">
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
.article-media img,
.article-media video {
  display: block;
  max-height: 480px;
  object-fit: cover;
  width: 100%;
}
.article-body p {
  font-size: 1.05rem;
  line-height: 1.85;
  color: var(--lg-ink-soft);
  margin-bottom: 1.4rem;
}
</style>
