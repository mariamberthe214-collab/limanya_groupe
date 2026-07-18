<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'
import { buildWhatsAppLink } from '../utils/whatsapp'

const atouts = [
  { icon: 'bi-droplet-half', label: 'Pompes solaires, électriques et hybrides' },
  { icon: 'bi-sun', label: 'Panneaux solaires et PVC hydrauliques' },
  { icon: 'bi-gear-wide-connected', label: 'Équipements et matériels de forage' },
  { icon: 'bi-tools', label: 'Accessoires et pièces pour vos installations' },
]

const equipements = ref([])
const chargement = ref(true)
const filtreActif = ref(null)
const lightbox = ref(null) // { src, titre }

const categoriesOrdre = [
  'Pompes solaires',
  'Pompes électriques',
  'Pompes hybrides',
  'Panneaux solaires',
  'PVC hydrauliques',
  'Équipements hydrauliques',
  'Matériels de forage',
  'Accessoires',
]

const demanderPrix = (nom) => {
  return buildWhatsAppLink(
    `Bonjour LIMANYA Groupe, je suis intéressé(e) par "${nom}". Pouvez-vous me donner plus d'informations ?`,
  )
}

const categoriesPresentes = computed(() => {
  const set = new Set(equipements.value.map((e) => e.categorie).filter(Boolean))
  return categoriesOrdre.filter((c) => set.has(c))
})

const equipementsFiltres = computed(() => {
  if (!filtreActif.value) return equipements.value
  return equipements.value.filter((e) => e.categorie === filtreActif.value)
})

const chargerEquipements = async () => {
  chargement.value = true
  try {
    const response = await api.get('/equipements')
    equipements.value = response.data
  } catch (error) {
    console.error(error)
  } finally {
    chargement.value = false
  }
}

const ouvrirLightbox = (equip) => {
  if (!equip.image) return
  lightbox.value = { src: getImageUrl(equip.image), titre: equip.nom }
  document.body.style.overflow = 'hidden'
}

const fermerLightbox = () => {
  lightbox.value = null
  document.body.style.overflow = ''
}

onMounted(chargerEquipements)
</script>

<template>
  <!-- HERO -->
  <section class="mat-hero contour-field-dark text-white">
    <div class="container position-relative py-5">
      <div class="row align-items-center g-4 py-4">
        <div class="col-lg-7">
          <span class="hero-eyebrow" v-reveal>
            <i class="bi bi-box-seam me-2"></i>Vente de Matériels
          </span>
          <h1 class="mat-hero-title mt-4" v-reveal="80">
            Le matériel adapté à chaque projet
            <span class="text-amber">hydraulique</span>
          </h1>
          <p class="lead text-white-75 mt-3" v-reveal="160" style="max-width: 620px">
            Pompes, panneaux solaires, PVC hydrauliques, équipements et matériels de forage
            pour tous vos besoins en eau.
          </p>
          <ul class="mat-atouts mt-4" v-reveal="220">
            <li v-for="a in atouts" :key="a.label">
              <i class="bi" :class="a.icon"></i>{{ a.label }}
            </li>
          </ul>
          <router-link to="/contacts" class="btn btn-primary btn-lg mt-4" v-reveal="300">
            Demander un devis
          </router-link>
        </div>
        <div class="col-lg-5">
          <div class="mat-hero-media" v-reveal="200">
            <img
              src="/images/service-materiel.png"
              alt="Matériel et pompes de forage"
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="mat-hero-wave" aria-hidden="true"></div>
  </section>

  <!-- CATALOGUE -->
  <section class="page-section py-5">
    <div class="container">
      <div class="text-center mb-4" v-reveal>
        <span class="section-eyebrow">Catalogue</span>
        <h2 class="section-title mt-3">Nos produits disponibles à la vente</h2>
        <p class="text-muted mx-auto" style="max-width: 620px">
          Tout notre catalogue au même endroit — filtrez par catégorie pour trouver
          rapidement ce qu'il vous faut.
        </p>
      </div>

      <!-- FILTRES -->
      <div
        v-if="categoriesPresentes.length > 1"
        class="filter-bar d-flex flex-wrap justify-content-center gap-2 mb-5"
        v-reveal="80"
      >
        <button
          class="filter-chip"
          :class="{ active: filtreActif === null }"
          @click="filtreActif = null"
        >
          Tout voir
        </button>
        <button
          v-for="cat in categoriesPresentes"
          :key="cat"
          class="filter-chip"
          :class="{ active: filtreActif === cat }"
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
      <div v-else-if="equipementsFiltres.length === 0" class="empty-state text-center py-5" v-reveal>
        <div class="empty-icon mb-3"><i class="bi bi-box-seam"></i></div>
        <h4>Catalogue en cours de mise à jour</h4>
        <p class="text-muted mb-0">Contactez-nous pour connaître nos disponibilités.</p>
      </div>

      <!-- GRILLE PRODUITS -->
      <div v-else class="row g-4">
        <div
          class="col-md-6 col-lg-4"
          v-for="(equip, i) in equipementsFiltres"
          :key="equip.id"
          v-reveal="i * 80"
        >
          <article class="product-card h-100">
            <div
              class="product-media"
              :class="{ 'is-clickable': equip.image }"
              @click="ouvrirLightbox(equip)"
            >
              <img
                v-if="equip.image"
                :src="getImageUrl(equip.image)"
                :alt="equip.nom"
                class="product-img"
                loading="lazy"
              />
              <div v-else class="product-noimg"><i class="bi bi-image"></i></div>

              <span
                class="stock-badge"
                :class="equip.disponible == 1 ? 'is-in' : 'is-out'"
              >
                <i class="bi" :class="equip.disponible == 1 ? 'bi-check-circle-fill' : 'bi-slash-circle'"></i>
                {{ equip.disponible == 1 ? 'Disponible' : 'Indisponible' }}
              </span>

              <span v-if="equip.image" class="media-zoom">
                <i class="bi bi-arrows-fullscreen"></i>
              </span>
            </div>

            <div class="product-body">
              <span v-if="equip.categorie" class="product-cat">{{ equip.categorie }}</span>
              <h5 class="product-title">{{ equip.nom }}</h5>
              <p class="product-desc">{{ equip.description }}</p>
              <div class="product-footer">
                <span v-if="equip.prix" class="product-price">
                  {{ Number(equip.prix).toLocaleString('fr-FR') }}
                  <small>FCFA</small>
                </span>
                <span v-else class="product-price product-price--muted">Sur devis</span>
                <a
                  :href="demanderPrix(equip.nom)"
                  target="_blank"
                  rel="noopener"
                  class="btn-whatsapp"
                >
                  <i class="bi bi-whatsapp me-1"></i>Demander
                </a>
              </div>
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
        <img :src="lightbox.src" :alt="lightbox.titre" class="lightbox-media" />
        <p class="lightbox-caption">{{ lightbox.titre }}</p>
      </div>
    </div>
  </transition>
</template>

<style scoped>
/* ---------- HERO ---------- */
.mat-hero {
  position: relative;
  background: linear-gradient(135deg, #17140f 0%, #2c281f 100%);
  overflow: hidden;
}
.mat-hero::before {
  content: '';
  position: absolute;
  top: -140px;
  right: -60px;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(191, 87, 28, 0.4), transparent 70%);
  pointer-events: none;
  animation: floatGlow 9s ease-in-out infinite;
}
@keyframes floatGlow {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(-24px, 26px); }
}
.mat-hero .container {
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
.mat-hero-title {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  color: #fff;
  font-size: clamp(2rem, 4.2vw, 3.2rem);
  line-height: 1.1;
  letter-spacing: -0.02em;
}
.mat-atouts {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 0.75rem;
}
.mat-atouts li {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  color: rgba(255, 255, 255, 0.82);
  font-size: 0.98rem;
}
.mat-atouts li i {
  flex-shrink: 0;
  width: 34px;
  height: 34px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background: var(--lg-amber-soft, #f6e7da);
  color: var(--lg-amber);
  font-size: 1rem;
}
.mat-hero-media {
  position: relative;
  border-radius: 1.5rem;
  overflow: hidden;
  box-shadow: 0 30px 70px rgba(0, 0, 0, 0.45);
  border: 1px solid rgba(255, 255, 255, 0.12);
}
.mat-hero-media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.9s cubic-bezier(0.16, 1, 0.3, 1);
}
.mat-hero-media:hover img {
  transform: scale(1.05);
}
.mat-hero-wave {
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 40px;
  background: var(--lg-cream);
  clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 55%);
}

/* ---------- SECTION HEADER ---------- */
.section-eyebrow {
  display: inline-block;
  letter-spacing: 0.18em;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  color: var(--lg-amber);
}
.section-title {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
}

/* ---------- FILTRES ---------- */
.filter-chip {
  border: 1px solid var(--lg-line);
  background: var(--lg-surface);
  color: var(--lg-ink, #2c281f);
  border-radius: 999px;
  padding: 0.5rem 1.2rem;
  font-size: 0.88rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.22s ease;
}
.filter-chip:hover {
  border-color: var(--lg-amber);
  color: var(--lg-amber);
  transform: translateY(-2px);
}
.filter-chip.active {
  background: var(--lg-amber);
  border-color: var(--lg-amber);
  color: #fff;
  box-shadow: 0 8px 20px rgba(191, 87, 28, 0.3);
}

/* ---------- CARTES PRODUITS ---------- */
.product-card {
  display: flex;
  flex-direction: column;
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1.25rem;
  overflow: hidden;
  box-shadow: 0 14px 34px rgba(23, 20, 15, 0.05);
  transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
}
.product-card:hover {
  transform: translateY(-6px);
  border-color: var(--lg-amber);
  box-shadow: 0 26px 54px rgba(191, 87, 28, 0.14);
}
.product-media {
  position: relative;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  background: #fff;
}
.product-media.is-clickable {
  cursor: pointer;
}
.product-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 1rem;
  transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}
.product-card:hover .product-img {
  transform: scale(1.07);
}
.product-noimg {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: var(--lg-line);
  font-size: 2.6rem;
  background: var(--lg-cream);
}
.stock-badge {
  position: absolute;
  top: 0.8rem;
  left: 0.8rem;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  font-size: 0.72rem;
  font-weight: 600;
  padding: 0.35rem 0.7rem;
  border-radius: 999px;
  z-index: 2;
  backdrop-filter: blur(4px);
}
.stock-badge.is-in {
  background: rgba(31, 122, 60, 0.92);
  color: #fff;
}
.stock-badge.is-out {
  background: rgba(23, 20, 15, 0.6);
  color: #fff;
}
.media-zoom {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 1.6rem;
  background: rgba(23, 20, 15, 0.28);
  opacity: 0;
  transition: opacity 0.3s ease;
}
.product-media:hover .media-zoom {
  opacity: 1;
}
.product-body {
  padding: 1.25rem 1.35rem 1.4rem;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}
.product-cat {
  align-self: flex-start;
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--lg-amber);
  background: var(--lg-amber-soft, #f6e7da);
  padding: 0.25rem 0.65rem;
  border-radius: 999px;
  margin-bottom: 0.6rem;
}
.product-title {
  font-size: 1.1rem;
  line-height: 1.3;
  margin-bottom: 0.5rem;
}
.product-desc {
  color: var(--lg-muted);
  font-size: 0.9rem;
  line-height: 1.6;
  flex-grow: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  margin-bottom: 1rem;
}
.product-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  border-top: 1px solid var(--lg-line);
  padding-top: 1rem;
}
.product-price {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  font-size: 1.1rem;
  color: var(--lg-ink, #2c281f);
  line-height: 1.1;
}
.product-price small {
  font-size: 0.7rem;
  font-weight: 600;
  color: var(--lg-muted);
}
.product-price--muted {
  font-size: 0.95rem;
  color: var(--lg-muted);
}
.btn-whatsapp {
  display: inline-flex;
  align-items: center;
  white-space: nowrap;
  font-size: 0.85rem;
  font-weight: 600;
  padding: 0.5rem 0.9rem;
  border-radius: 999px;
  background: #1f7a3c;
  color: #fff;
  text-decoration: none;
  transition: background 0.2s ease, transform 0.2s ease;
}
.btn-whatsapp:hover {
  background: #196330;
  transform: translateY(-2px);
  color: #fff;
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
  background: var(--lg-amber-soft, #f6e7da);
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
  max-width: 820px;
  width: 100%;
  text-align: center;
  animation: lbZoom 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.lightbox-media {
  max-width: 100%;
  max-height: 78vh;
  border-radius: 1rem;
  background: #fff;
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
  .mat-hero-media {
    max-width: 460px;
  }
}
</style>
