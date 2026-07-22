<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'

const posts = ref([])
const chargement = ref(true)
const lightbox = ref(null) // { type: 'image'|'video', src, titre }

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })
}

// Détecte si un média Cloudinary est une vidéo (resource_type video ou extension connue)
const estVideo = (url) => {
  if (!url) return false
  return /\/video\//.test(url) || /\.(mp4|webm|ogg|mov|m4v)(\?|$)/i.test(url)
}

const chargerActualites = async () => {
  chargement.value = true
  try {
    const response = await api.get('/actualites?statut=Publié')
    // Tri du plus récent au plus ancien
    posts.value = [...response.data].sort(
      (a, b) => new Date(b.date_publication) - new Date(a.date_publication),
    )
  } catch (error) {
    console.error(error)
  } finally {
    chargement.value = false
  }
}

const aLaUne = computed(() => posts.value[0] || null)
const autresPosts = computed(() => posts.value.slice(1))

const ouvrirLightbox = (post) => {
  if (!post.image) return
  lightbox.value = {
    type: estVideo(post.image) ? 'video' : 'image',
    src: getImageUrl(post.image),
    titre: post.titre,
  }
  document.body.style.overflow = 'hidden'
}

const fermerLightbox = () => {
  lightbox.value = null
  document.body.style.overflow = ''
}

onMounted(chargerActualites)
</script>

<template>
  <!-- HERO -->
  <section class="news-hero contour-field-dark text-white">
    <div class="container position-relative py-5">
      <div class="row align-items-end g-4 py-4">
        <div class="col-lg-8">
          <span class="hero-eyebrow" v-reveal>
            <i class="bi bi-broadcast me-2"></i>Actualités
          </span>
          <h1 class="news-hero-title mt-4" v-reveal="80">
            Les dernières nouvelles de <span class="text-amber">LIMANYA</span> Groupe
          </h1>
          <p class="lead text-white-75 mt-3 mb-0" v-reveal="160" style="max-width: 640px">
            Suivez nos chantiers en cours, nos forages récents et nos initiatives en
            énergie renouvelable, en photos et en vidéos.
          </p>
        </div>
        <div class="col-lg-4">
          <div class="news-count-badge" v-reveal="220">
            <span class="news-count-value">{{ posts.length }}</span>
            <span class="news-count-label">publication{{ posts.length > 1 ? 's' : '' }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="news-hero-wave" aria-hidden="true"></div>
  </section>

  <section class="page-section py-5">
    <div class="container">
      <!-- CHARGEMENT (squelettes) -->
      <div v-if="chargement" class="row g-4">
        <div class="col-12 mb-2">
          <div class="skeleton skeleton-feature"></div>
        </div>
        <div class="col-md-6 col-lg-4" v-for="n in 3" :key="n">
          <div class="skeleton skeleton-card"></div>
        </div>
      </div>

      <!-- ÉTAT VIDE -->
      <div v-else-if="posts.length === 0" class="empty-state text-center py-5" v-reveal>
        <div class="empty-icon mb-3"><i class="bi bi-newspaper"></i></div>
        <h4>Aucune actualité pour le moment</h4>
        <p class="text-muted mb-0">Revenez bientôt : de nouvelles publications arrivent.</p>
      </div>

      <template v-else>
        <!-- ARTICLE À LA UNE -->
        <article v-if="aLaUne" class="feature-card mb-5" v-reveal>
          <div class="row g-0">
            <div class="col-lg-7">
              <div
                class="feature-media"
                :class="{ 'is-clickable': aLaUne.image }"
                @click="ouvrirLightbox(aLaUne)"
              >
                <template v-if="aLaUne.image">
                  <video
                    v-if="estVideo(aLaUne.image)"
                    :src="getImageUrl(aLaUne.image)"
                    controls
                    preload="metadata"
                    class="feature-video"
                    @click.stop
                  ></video>
                  <template v-else>
                    <img
                      :src="getImageUrl(aLaUne.image)"
                      :alt="aLaUne.titre"
                      class="feature-img"
                      loading="lazy"
                    />
                    <span class="media-zoom"><i class="bi bi-arrows-fullscreen"></i></span>
                  </template>
                </template>
                <div v-else class="feature-noimg">
                  <i class="bi bi-image"></i>
                </div>
                <span class="feature-flag"><i class="bi bi-star-fill me-1"></i>À la une</span>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="feature-body">
                <div class="post-meta mb-3">
                  <span class="post-date">
                    <i class="bi bi-calendar3 me-1"></i>{{ formatDate(aLaUne.date_publication) }}
                  </span>
                  <span v-if="aLaUne.auteur" class="post-author">
                    <i class="bi bi-person me-1"></i>{{ aLaUne.auteur }}
                  </span>
                </div>
                <h2 class="feature-title">{{ aLaUne.titre }}</h2>
                <p class="feature-excerpt">{{ aLaUne.contenu }}</p>
                <router-link :to="`/actualites/${aLaUne.id}`" class="read-more-link">
                  Lire l'article complet <i class="bi bi-arrow-right"></i>
                </router-link>
              </div>
            </div>
          </div>
        </article>

        <!-- GRILLE DES AUTRES ACTUALITÉS -->
        <div v-if="autresPosts.length" class="row g-4">
          <div
            class="col-md-6 col-lg-4"
            v-for="(post, i) in autresPosts"
            :key="post.id"
            v-reveal="i * 90"
          >
            <article class="news-card h-100">
              <div
                class="news-media"
                :class="{ 'is-clickable': post.image }"
                @click="ouvrirLightbox(post)"
              >
                <template v-if="post.image">
                  <template v-if="estVideo(post.image)">
                    <video
                      :src="getImageUrl(post.image)"
                      preload="metadata"
                      class="news-img"
                      muted
                    ></video>
                    <span class="media-badge"><i class="bi bi-play-fill"></i></span>
                    <span class="media-play"><i class="bi bi-play-circle-fill"></i></span>
                  </template>
                  <template v-else>
                    <img
                      :src="getImageUrl(post.image)"
                      :alt="post.titre"
                      class="news-img"
                      loading="lazy"
                    />
                    <span class="media-badge"><i class="bi bi-camera-fill"></i></span>
                    <span class="media-zoom"><i class="bi bi-arrows-fullscreen"></i></span>
                  </template>
                </template>
                <div v-else class="news-noimg"><i class="bi bi-image"></i></div>
              </div>
              <div class="news-body">
                <span class="news-date">
                  <i class="bi bi-calendar3 me-1"></i>{{ formatDate(post.date_publication) }}
                </span>
                <h5 class="news-title">{{ post.titre }}</h5>
                <p class="news-excerpt">{{ post.contenu }}</p>
                <span v-if="post.auteur" class="news-author">
                  <i class="bi bi-person-circle me-1"></i>{{ post.auteur }}
                </span>
                <router-link :to="`/actualites/${post.id}`" class="read-more-link">
                  Lire la suite <i class="bi bi-arrow-right"></i>
                </router-link>
              </div>
            </article>
          </div>
        </div>
      </template>
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
.news-hero {
  position: relative;
  background: linear-gradient(135deg, #17140f 0%, #2c281f 100%);
  overflow: hidden;
}
.news-hero::before {
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
.news-hero .container {
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
.news-hero-title {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  color: #fff;
  font-size: clamp(2rem, 4.4vw, 3.4rem);
  line-height: 1.08;
  letter-spacing: -0.02em;
}
.news-count-badge {
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
.news-count-value {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 2.8rem;
  font-weight: 700;
  color: var(--lg-amber);
  line-height: 1;
}
.news-count-label {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}
.news-hero-wave {
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 40px;
  background: var(--lg-cream);
  clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 55%);
}

/* ---------- ARTICLE À LA UNE ---------- */
.feature-card {
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1.5rem;
  overflow: hidden;
  box-shadow: 0 24px 60px rgba(23, 20, 15, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.feature-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 32px 70px rgba(23, 20, 15, 0.14);
}
.feature-media {
  position: relative;
  height: 100%;
  min-height: 340px;
  overflow: hidden;
  background: var(--lg-ink);
}
.feature-media.is-clickable {
  cursor: pointer;
}
.feature-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.9s cubic-bezier(0.16, 1, 0.3, 1);
}
.feature-media:hover .feature-img {
  transform: scale(1.06);
}
.feature-video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  background: #000;
}
.feature-noimg {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: rgba(255, 255, 255, 0.3);
  font-size: 3rem;
}
.feature-flag {
  position: absolute;
  top: 1rem;
  left: 1rem;
  background: var(--lg-amber);
  color: #fff;
  font-size: 0.78rem;
  font-weight: 600;
  padding: 0.4rem 0.9rem;
  border-radius: 999px;
  box-shadow: 0 8px 18px rgba(191, 87, 28, 0.35);
  z-index: 2;
}
.feature-body {
  padding: 2.5rem;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.feature-title {
  font-size: clamp(1.4rem, 2.4vw, 1.9rem);
  line-height: 1.2;
  margin-bottom: 1rem;
}
.feature-excerpt {
  color: var(--lg-muted);
  line-height: 1.7;
  display: -webkit-box;
  -webkit-line-clamp: 5;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.post-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  font-size: 0.85rem;
  color: var(--lg-muted);
}
.post-date {
  color: var(--lg-amber);
  font-weight: 600;
}

/* ---------- CARTES ACTUALITÉS ---------- */
.news-card {
  display: flex;
  flex-direction: column;
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1.25rem;
  overflow: hidden;
  box-shadow: 0 14px 34px rgba(23, 20, 15, 0.05);
  transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
}
.news-card:hover {
  transform: translateY(-6px);
  border-color: var(--lg-amber);
  box-shadow: 0 26px 54px rgba(191, 87, 28, 0.14);
}
.news-media {
  position: relative;
  aspect-ratio: 16 / 10;
  overflow: hidden;
  background: var(--lg-ink);
}
.news-media.is-clickable {
  cursor: pointer;
}
.news-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
.news-card:hover .news-img {
  transform: scale(1.08);
}
.news-noimg {
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
.news-media:hover .media-zoom,
.feature-media:hover .media-zoom {
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
.news-media:hover .media-play {
  transform: scale(1.12);
  opacity: 1;
}
.news-body {
  padding: 1.4rem 1.5rem 1.6rem;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}
.news-date {
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--lg-amber);
  margin-bottom: 0.6rem;
}
.news-title {
  font-size: 1.15rem;
  line-height: 1.3;
  margin-bottom: 0.6rem;
}
.news-excerpt {
  color: var(--lg-muted);
  font-size: 0.92rem;
  line-height: 1.65;
  flex-grow: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.news-author {
  margin-top: 1rem;
  font-size: 0.82rem;
  color: var(--lg-muted);
  border-top: 1px solid var(--lg-line);
  padding-top: 0.9rem;
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
.skeleton-feature {
  height: 340px;
  border-radius: 1.5rem;
}
.skeleton-card {
  height: 320px;
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

@media (max-width: 991px) {
  .feature-media {
    min-height: 260px;
  }
  .feature-body {
    padding: 1.75rem;
  }
}.read-more-link {
  display: inline-block;
  margin-top: 0.9rem;
  font-weight: 600;
  font-size: 0.88rem;
  color: var(--lg-amber, #bf571c);
  text-decoration: none;
}
.read-more-link:hover {
  text-decoration: underline;
}
</style>
