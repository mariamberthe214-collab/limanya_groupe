<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'
import { buildWhatsAppLink } from '../utils/whatsapp'

const whatsappLink = buildWhatsAppLink('Bonjour LIMANYA Groupe, je souhaite un devis pour un projet.')

const projets = ref([])
const videos = ref([])
const chargement = ref(true)
const chargementVideos = ref(true)

const portfolioExamples = [
  {
    categorie: 'Forage hydraulique',
    titre: 'Forage villageois & agricole',
    lieu: 'Abidjan / Intérieur du pays',
    image: '/images/hero-construction.png',
    action: '/forage',
  },
  {
    categorie: 'Études géophysiques',
    titre: 'Recherche d’eau & implantation',
    lieu: 'Sites industriels et agricoles',
    image: '/images/hero-chateau-eau.jpg',
    action: '/etudes-geophysiques',
  },
  {
    categorie: 'Assainissement',
    titre: 'Réseaux & gestion des eaux usées',
    lieu: 'Zones urbaines et rurales',
    image: '/images/assainisement.png',
    action: '/assainissement',
  },
  {
    categorie: 'BTP & génie civil',
    titre: 'Construction & ouvrages publics',
    lieu: 'Chantiers civils',
    image: '/images/btp3.png',
    action: '/btp',
  },
]

const chargerDonnees = async () => {
  chargement.value = true
  chargementVideos.value = true

  try {
    const response = await api.get('/realisations')

    const data = Array.isArray(response.data)
      ? response.data
      : []

    projets.value = data
    videos.value = data.filter((r) => r.video).slice(0, 3)
  } catch (error) {
    console.error('Erreur lors du chargement des réalisations :', error)
    projets.value = []
    videos.value = []
  } finally {
    chargement.value = false
    chargementVideos.value = false
  }
}

onMounted(chargerDonnees)

const stats = [
  { value: '15+', label: "Années d'expertise" },
  { value: '200+', label: 'Projets livrés' },
  { value: '100%', label: 'Satisfaction client' },
  { value: '24/7', label: 'Accompagnement' },
]

const categories = [
  {
    icon: 'bi-water',
    title: 'Forages Hydrauliques',
    description: 'Forages villageois, agricoles, industriels, essais de pompage, réhabilitation et maintenance.',
    action: '/forage',
  },
  {
    icon: 'bi-globe2',
    title: 'Études Géophysiques',
    description: "Recherche d'eau, études géophysiques et implantation des forages avant tout chantier.",
    action: '/etudes-geophysiques',
  },
  {
    icon: 'bi-recycle',
    title: 'Assainissement',
    description: 'Réseaux d\u2019assainissement, gestion des eaux usées, fosses septiques et fosses biodigesteurs.',
    action: '/assainissement',
  },
  {
    icon: 'bi-building',
    title: 'BTP & Génie Civil',
    description: 'Construction, génie civil, VRD et travaux publics.',
    action: '/btp',
  },
  {
    icon: 'bi-tools',
    title: 'Vente de Matériels',
    description: 'Pompes solaires, électriques et hybrides, panneaux solaires, PVC hydrauliques, matériels de forage et accessoires.',
    action: '/materiels-de-forages',
  },
]

const highlights = [
  { icon: 'bi-patch-check', title: 'Spécialiste du forage', text: "Forages hydrauliques, villageois, agricoles et industriels, réalisés avec rigueur." },
  { icon: 'bi-diagram-3', title: 'Solutions complètes', text: 'Études géophysiques, assainissement, BTP et vente de matériels réunis.' },
  { icon: 'bi-clock-history', title: 'Respect des délais', text: 'Engagement qualité rigoureux sur chaque chantier.' },
  { icon: 'bi-tree', title: 'Approche durable', text: 'Bâtir un avenir solide et respectueux de l’environnement.' },
]
</script>

<template>
    <!-- HERO -->
    <!-- HERO PREMIUM -->
  <section class="home-hero">
    <div class="home-hero-bg">
      <img
        src="/images/hero-construction.png"
        alt="Projet de forage et travaux de construction LIMANYA Groupe"
        class="home-hero-image"
      />
      <div class="home-hero-overlay"></div>
    </div>

    <div class="container position-relative home-hero-content">
      <div class="row align-items-center min-vh-75">

        <div class="col-lg-8">

          <!-- BADGE -->
          <div class="home-hero-badge" v-reveal>
            <span class="home-hero-badge-dot"></span>
            LIMANYA GROUPE
            <span class="home-hero-badge-line"></span>
            Côte d'Ivoire
          </div>

          <!-- TITRE -->
          <h1 class="home-hero-title mt-4" v-reveal="100">
            Des solutions fiables
            pour vos projets
            <span>de l'eau au BTP.</span>
          </h1>

          <!-- DESCRIPTION -->
          <p class="home-hero-description mt-4" v-reveal="180">
            Forages hydrauliques, études géophysiques, assainissement,
            BTP et équipements : nous vous accompagnons de l'étude
            jusqu'à la réalisation de vos projets.
          </p>

          <!-- ACTIONS -->
          <div class="home-hero-actions mt-4" v-reveal="260">

            <router-link to="/contacts" class="hero-btn hero-btn-primary">
              Demander un devis
              <i class="bi bi-arrow-up-right"></i>
            </router-link>

            <router-link to="/realisations-btp-services-de-construction" class="hero-btn hero-btn-outline">
              Découvrir nos réalisations
              <i class="bi bi-arrow-right"></i>
            </router-link>

          </div>

          <!-- DOMAINES -->
          <div class="home-hero-services mt-5" v-reveal="340">

            <span>
              <i class="bi bi-droplet"></i>
              Forages
            </span>

            <span>
              <i class="bi bi-globe2"></i>
              Géophysique
            </span>

            <span>
              <i class="bi bi-recycle"></i>
              Assainissement
            </span>

            <span>
              <i class="bi bi-buildings"></i>
              BTP
            </span>

          </div>

        </div>

      </div>
    </div>

    <!-- INDICATEUR BAS -->
    <div class="home-hero-scroll">
      <span>Découvrir LIMANYA</span>
      <i class="bi bi-arrow-down"></i>
    </div>

  </section>
  <!-- STATS -->
  <section class="stats-bar bg-ink">
    <div class="container">
      <div class="row text-center py-4">
        <div class="col-6 col-md-3 py-3" v-for="(stat, i) in stats" :key="stat.label" v-reveal="i * 80">
          <div class="stat-value">{{ stat.value }}</div>
          <div class="stat-label">{{ stat.label }}</div>
        </div>
      </div>
    </div>
  </section>

  <!-- EXPERTISES PREMIUM -->
  <section class="expertises-section page-section">
    <div class="container">

      <!-- EN-TÊTE -->
      <div class="row align-items-end g-4 mb-5">
        <div class="col-lg-7">
          <span class="section-eyebrow" v-reveal>
            Nos expertises
          </span>

          <h2 class="expertises-title mt-3" v-reveal="80">
            Des compétences complémentaires
            <span>pour un projet maîtrisé.</span>
          </h2>
        </div>

        <div class="col-lg-5">
          <p class="expertises-intro mb-0" v-reveal="160">
            De l'étude du terrain à la réalisation, LIMANYA Groupe
            mobilise son savoir-faire pour proposer des solutions
            adaptées aux réalités de chaque projet.
          </p>
        </div>
      </div>

      <!-- CARTES -->
      <div class="expertises-grid">

        <!-- FORAGES -->
        <router-link
          to="/forage"
          class="expertise-card expertise-card-featured"
          v-reveal
        >
          <div class="expertise-card-number">01</div>

          <div class="expertise-card-icon">
            <i class="bi bi-droplet-half"></i>
          </div>

          <div class="expertise-card-content">
            <span class="expertise-card-label">
              Eau & hydraulique
            </span>

            <h3>
              Forages
              hydrauliques
            </h3>

            <p>
              Forages villageois, agricoles et industriels,
              essais de pompage, réhabilitation et maintenance.
            </p>

            <span class="expertise-card-link">
              Découvrir
              <i class="bi bi-arrow-up-right"></i>
            </span>
          </div>
        </router-link>

        <!-- GEOPHYSIQUE -->
        <router-link
          to="/etudes-geophysiques"
          class="expertise-card"
          v-reveal="80"
        >
          <div class="expertise-card-number">02</div>

          <div class="expertise-card-icon">
            <i class="bi bi-graph-up-arrow"></i>
          </div>

          <div class="expertise-card-content">
            <span class="expertise-card-label">
              Études & analyse
            </span>

            <h3>
              Études
              géophysiques
            </h3>

            <p>
              Recherche d'eau, études géophysiques et
              implantation des forages.
            </p>

            <span class="expertise-card-link">
              Découvrir
              <i class="bi bi-arrow-up-right"></i>
            </span>
          </div>
        </router-link>

        <!-- ASSAINISSEMENT -->
        <router-link
          to="/assainissement"
          class="expertise-card"
          v-reveal="160"
        >
          <div class="expertise-card-number">03</div>

          <div class="expertise-card-icon">
            <i class="bi bi-recycle"></i>
          </div>

          <div class="expertise-card-content">
            <span class="expertise-card-label">
              Environnement
            </span>

            <h3>
              Assainissement
            </h3>

            <p>
              Gestion des eaux usées, réseaux d'assainissement,
              fosses septiques et biodigesteurs.
            </p>

            <span class="expertise-card-link">
              Découvrir
              <i class="bi bi-arrow-up-right"></i>
            </span>
          </div>
        </router-link>

        <!-- BTP -->
        <router-link
          to="/btp"
          class="expertise-card"
          v-reveal="240"
        >
          <div class="expertise-card-number">04</div>

          <div class="expertise-card-icon">
            <i class="bi bi-buildings"></i>
          </div>

          <div class="expertise-card-content">
            <span class="expertise-card-label">
              Construction
            </span>

            <h3>
              BTP &
              génie civil
            </h3>

            <p>
              Construction, travaux publics, VRD et
              réalisation d'ouvrages.
            </p>

            <span class="expertise-card-link">
              Découvrir
              <i class="bi bi-arrow-up-right"></i>
            </span>
          </div>
        </router-link>

        <!-- MATERIELS -->
        <router-link
          to="/materiels-de-forages"
          class="expertise-card"
          v-reveal="320"
        >
          <div class="expertise-card-number">05</div>

          <div class="expertise-card-icon">
            <i class="bi bi-tools"></i>
          </div>

          <div class="expertise-card-content">
            <span class="expertise-card-label">
              Équipements
            </span>

            <h3>
              Vente de
              matériels
            </h3>

            <p>
              Pompes, panneaux solaires, PVC hydrauliques,
              équipements et accessoires.
            </p>

            <span class="expertise-card-link">
              Découvrir
              <i class="bi bi-arrow-up-right"></i>
            </span>
          </div>
        </router-link>

      </div>

      <!-- PETIT CTA -->
      <div class="expertises-bottom" v-reveal="400">
        <div>
          <span class="expertises-bottom-label">
            Un projet en préparation ?
          </span>

          <strong>
            Parlons de vos besoins.
          </strong>
        </div>

        <router-link to="/contacts" class="expertises-bottom-btn">
          Demander un devis
          <i class="bi bi-arrow-right"></i>
        </router-link>
      </div>

    </div>
  </section>

  <!-- NOTRE MÉTHODE -->
  <section class="method-section page-section">
    <div class="container">

      <div class="row align-items-end g-4 mb-5">
        <div class="col-lg-7">
          <span class="section-eyebrow" v-reveal>
            Notre méthode
          </span>

          <h2 class="method-title mt-3" v-reveal="80">
            Une démarche claire,
            <span>du diagnostic à la réalisation.</span>
          </h2>
        </div>

        <div class="col-lg-5">
          <p class="method-intro mb-0" v-reveal="160">
            Chaque projet est étudié avec attention afin de proposer
            une solution adaptée aux besoins du client, aux contraintes
            du terrain et aux objectifs fixés.
          </p>
        </div>
      </div>

      <!-- ÉTAPES -->
      <div class="method-timeline">

        <!-- ÉTAPE 01 -->
        <div class="method-step" v-reveal>
          <div class="method-step-top">
            <span class="method-number">01</span>

            <div class="method-icon">
              <i class="bi bi-chat-square-text"></i>
            </div>
          </div>

          <div class="method-line"></div>

          <div class="method-content">
            <span class="method-label">
              Comprendre
            </span>

            <h3>
              Écoute & analyse
            </h3>

            <p>
              Nous échangeons avec le client afin de comprendre
              précisément ses besoins, ses contraintes et les
              objectifs de son projet.
            </p>
          </div>
        </div>

        <!-- ÉTAPE 02 -->
        <div class="method-step" v-reveal="100">
          <div class="method-step-top">
            <span class="method-number">02</span>

            <div class="method-icon">
              <i class="bi bi-search"></i>
            </div>
          </div>

          <div class="method-line"></div>

          <div class="method-content">
            <span class="method-label">
              Étudier
            </span>

            <h3>
              Étude & diagnostic
            </h3>

            <p>
              Nos équipes analysent le terrain et les contraintes
              techniques pour définir la solution la plus adaptée.
            </p>
          </div>
        </div>

        <!-- ÉTAPE 03 -->
        <div class="method-step" v-reveal="200">
          <div class="method-step-top">
            <span class="method-number">03</span>

            <div class="method-icon">
              <i class="bi bi-pencil-square"></i>
            </div>
          </div>

          <div class="method-line"></div>

          <div class="method-content">
            <span class="method-label">
              Préparer
            </span>

            <h3>
              Planification
            </h3>

            <p>
              Nous définissons les moyens techniques, les ressources
              nécessaires et les différentes étapes d'intervention.
            </p>
          </div>
        </div>

        <!-- ÉTAPE 04 -->
        <div class="method-step" v-reveal="300">
          <div class="method-step-top">
            <span class="method-number">04</span>

            <div class="method-icon">
              <i class="bi bi-cone-striped"></i>
            </div>
          </div>

          <div class="method-line"></div>

          <div class="method-content">
            <span class="method-label">
              Réaliser
            </span>

            <h3>
              Exécution & suivi
            </h3>

            <p>
              Le projet est réalisé avec un suivi rigoureux afin
              d'assurer la qualité des travaux et le respect des
              engagements.
            </p>
          </div>
        </div>

      </div>

      <!-- ENGAGEMENT -->
      <div class="method-footer" v-reveal="380">

        <div class="method-footer-icon">
          <i class="bi bi-shield-check"></i>
        </div>

        <div class="method-footer-text">
          <span>
            Notre engagement
          </span>

          <strong>
            Qualité, sérieux et accompagnement à chaque étape.
          </strong>
        </div>

        <router-link
          to="/contacts"
          class="method-footer-btn"
        >
          Démarrer un projet
          <i class="bi bi-arrow-right"></i>
        </router-link>

      </div>

    </div>
  </section>

  <!-- NOS RÉALISATIONS -->
  <section class="home-projects page-section">
    <div class="container">

      <div class="row align-items-end g-4 mb-5">
        <div class="col-lg-7">
          <span class="section-eyebrow" v-reveal>
            Notre portfolio
          </span>

          <h2 class="projects-title mt-3" v-reveal="80">
            Un exemple par domaine
            <span>de notre expertise.</span>
          </h2>
        </div>

        <div class="col-lg-5">
          <p class="projects-intro mb-0" v-reveal="160">
            Nous mettons en avant un exemple représentatif de chaque
            spécialité pour montrer la diversité de nos interventions.
          </p>
        </div>
      </div>

      <div class="projects-showcase" v-if="portfolioExamples.length">
        <router-link
          v-for="(projet, index) in portfolioExamples"
          :key="projet.categorie"
          :to="projet.action"
          class="featured-project portfolio-example"
          v-reveal="index * 100"
        >
          <div class="featured-project-media">
            <img :src="projet.image" :alt="projet.titre" loading="lazy" />
            <div class="featured-project-overlay"></div>

            <div class="featured-project-content">
              <span class="project-category">{{ projet.categorie }}</span>
              <h3>{{ projet.titre }}</h3>
              <p v-if="projet.lieu">
                <i class="bi bi-geo-alt me-1"></i>
                {{ projet.lieu }}
              </p>
            </div>
          </div>
        </router-link>
      </div>

      <div class="projects-videos mt-4" v-if="!chargementVideos && videos.length">
        <div class="row g-4">
          <div
            v-for="(video, index) in videos"
            :key="video.id"
            class="col-md-4"
            v-reveal="index * 100"
          >
            <div class="project-video-card">
              <video
                :src="getImageUrl(video.video)"
                :poster="video.image ? getImageUrl(video.image) : ''"
                preload="metadata"
                controls
                class="w-100"
              ></video>
              <div class="project-video-caption">
                <strong>{{ video.titre }}</strong>
                <span v-if="video.lieu" class="d-block">{{ video.lieu }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="projects-footer" v-reveal="300">
        <div class="projects-counter">
          <strong>{{ portfolioExamples.length }}</strong>
          <span>domaines illustrés</span>
        </div>

        <router-link
          to="/realisations-btp-services-de-construction"
          class="projects-button"
        >
          Voir plus
          <i class="bi bi-arrow-up-right"></i>
        </router-link>
      </div>
    </div>
  </section>
  

  <!-- WHY -->
  <!-- POURQUOI LIMANYA -->
  <section class="trust-section page-section">
    <div class="container">

      <!-- EN-TÊTE -->
      <div class="row align-items-end g-4 mb-5">

        <div class="col-lg-7">
          <span class="section-eyebrow" v-reveal>
            Pourquoi LIMANYA Groupe ?
          </span>

          <h2 class="trust-title mt-3" v-reveal="80">
            Une expertise pensée pour
            <span>des projets qui durent.</span>
          </h2>
        </div>

        <div class="col-lg-5">
          <p class="trust-intro mb-0" v-reveal="160">
            De l'étude à la réalisation, nous mettons notre
            savoir-faire et notre exigence au service de chaque
            projet qui nous est confié.
          </p>
        </div>

      </div>


      <!-- POINTS FORTS -->
      <div class="row g-4">

        <div
          v-for="(highlight, index) in highlights"
          :key="highlight.title"
          class="col-md-6 col-lg-3"
          v-reveal="index * 100"
        >

          <article class="trust-card">

            <div class="trust-number">
              0{{ index + 1 }}
            </div>

            <div class="trust-icon">
              <i
                class="bi"
                :class="highlight.icon"
              ></i>
            </div>

            <h3>
              {{ highlight.title }}
            </h3>

            <p>
              {{ highlight.text }}
            </p>

          </article>

        </div>

      </div>


      <!-- BANDEAU -->
      <div
        class="trust-banner mt-5"
        v-reveal="300"
      >

        <div class="trust-banner-icon">
          <i class="bi bi-shield-check"></i>
        </div>

        <div class="trust-banner-content">

          <strong>
            Une approche professionnelle à chaque étape.
          </strong>

          <span>
            Analyse du besoin, étude, planification,
            réalisation et accompagnement.
          </span>

        </div>

        <router-link
          to="/contacts"
          class="trust-banner-button"
        >
          Parlons de votre projet
          <i class="bi bi-arrow-up-right"></i>
        </router-link>

      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section text-white">
    <div class="container">
      <div class="cta-card text-center">
        <h2 class="text-white mb-3">Prêt à bâtir votre projet ?</h2>
        <p class="lead text-white-75 mb-4 mx-auto" style="max-width: 640px;">Contactez-nous pour un accompagnement personnalisé en BTP, forage hydraulique et énergies renouvelables.</p>
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
          <router-link to="/contacts" class="btn btn-primary btn-lg">Contactez-nous</router-link>
          <a :href="whatsappLink" target="_blank" class="btn btn-outline-light btn-lg"><i class="bi bi-whatsapp me-2"></i>Discutons sur WhatsApp</a>
        </div>
      </div>
    </div>
  </section>


</template>

<style scoped>

/* =========================================================
   POURQUOI LIMANYA
========================================================= */

.trust-section {
  background: var(--lg-surface);
  position: relative;
  overflow: hidden;
}

.trust-section::before {
  content: '';

  position: absolute;

  width: 420px;
  height: 420px;

  left: -280px;
  top: 100px;

  border-radius: 50%;

  background: rgba(191, 87, 28, 0.055);

  pointer-events: none;
}


/* TITRE */

.trust-title {
  max-width: 700px;

  font-family: 'Space Grotesk', sans-serif;

  font-size: clamp(2rem, 4vw, 3.1rem);

  font-weight: 700;

  line-height: 1.08;

  letter-spacing: -0.035em;

  color: var(--lg-ink);
}

.trust-title span {
  display: block;

  color: var(--lg-amber);
}

.trust-intro {
  max-width: 500px;

  color: var(--lg-muted);

  font-size: 0.98rem;

  line-height: 1.8;
}


/* =========================================================
   CARTES
========================================================= */

.trust-card {
  position: relative;

  height: 100%;

  padding: 1.7rem;

  border: 1px solid var(--lg-line);

  border-radius: 1.25rem;

  background: var(--lg-cream);

  overflow: hidden;

  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease,
    border-color 0.3s ease;
}

.trust-card:hover {
  transform: translateY(-6px);

  border-color: var(--lg-amber);

  box-shadow:
    0 20px 45px rgba(23, 20, 15, 0.09);
}


/* NUMÉRO */

.trust-number {
  position: absolute;

  top: 1rem;
  right: 1.2rem;

  font-family: 'Space Grotesk', sans-serif;

  font-size: 0.7rem;

  font-weight: 700;

  color: rgba(191, 87, 28, 0.35);

  letter-spacing: 0.08em;
}


/* ICÔNE */

.trust-icon {
  width: 52px;
  height: 52px;

  display: flex;

  align-items: center;
  justify-content: center;

  margin-bottom: 1.4rem;

  border-radius: 14px;

  background: var(--lg-amber-soft);

  color: var(--lg-amber);

  font-size: 1.35rem;

  transition:
    transform 0.3s ease,
    background 0.3s ease;
}

.trust-card:hover .trust-icon {
  transform: rotate(-5deg) scale(1.06);

  background: var(--lg-amber);

  color: #fff;
}


/* TEXTE */

.trust-card h3 {
  margin-bottom: 0.65rem;

  font-family: 'Space Grotesk', sans-serif;

  font-size: 1.05rem;

  font-weight: 700;

  color: var(--lg-ink);
}

.trust-card p {
  margin: 0;

  color: var(--lg-muted);

  font-size: 0.86rem;

  line-height: 1.7;
}


/* =========================================================
   BANDEAU
========================================================= */

.trust-banner {
  display: flex;

  align-items: center;

  gap: 1.25rem;

  padding: 1.25rem 1.4rem;

  border-radius: 1rem;

  background: var(--lg-ink);

  color: #fff;

  box-shadow:
    0 18px 40px rgba(23, 20, 15, 0.12);
}

.trust-banner-icon {
  flex: 0 0 auto;

  width: 48px;
  height: 48px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 12px;

  background: rgba(191, 87, 28, 0.18);

  color: var(--lg-amber);

  font-size: 1.25rem;
}

.trust-banner-content {
  display: flex;

  flex-direction: column;

  gap: 0.2rem;

  flex-grow: 1;
}

.trust-banner-content strong {
  font-family: 'Space Grotesk', sans-serif;

  font-size: 0.95rem;
}

.trust-banner-content span {
  color: rgba(255, 255, 255, 0.62);

  font-size: 0.78rem;
}

.trust-banner-button {
  flex: 0 0 auto;

  display: inline-flex;

  align-items: center;

  gap: 0.5rem;

  padding: 0.7rem 1rem;

  border-radius: 0.65rem;

  background: var(--lg-amber);

  color: #fff;

  font-size: 0.78rem;

  font-weight: 700;

  text-decoration: none;

  transition:
    transform 0.25s ease,
    filter 0.25s ease;
}

.trust-banner-button:hover {
  color: #fff;

  transform: translateY(-2px);

  filter: brightness(1.08);
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767.98px) {

  .trust-banner {
    align-items: flex-start;

    flex-wrap: wrap;
  }

  .trust-banner-content {
    min-width: 0;
  }

  .trust-banner-button {
    width: 100%;

    justify-content: center;

    margin-top: 0.4rem;
  }

}

@media (max-width: 575.98px) {

  .trust-card {
    padding: 1.4rem;
  }

  .trust-banner {
    padding: 1.1rem;
  }

}

/* =========================================================
   NOS RÉALISATIONS
========================================================= */

.home-projects {
  position: relative;

  background: var(--lg-cream);

  overflow: hidden;
}

.home-projects::before {
  content: '';

  position: absolute;

  width: 500px;
  height: 500px;

  right: -300px;
  bottom: -300px;

  border-radius: 50%;

  background: rgba(191, 87, 28, 0.055);

  pointer-events: none;
}


/* TITRE */

.projects-title {
  max-width: 720px;

  font-family: 'Space Grotesk', sans-serif;

  font-size: clamp(2rem, 4vw, 3.2rem);

  font-weight: 700;

  line-height: 1.08;

  letter-spacing: -0.035em;

  color: var(--lg-ink);
}

.projects-title span {
  display: block;

  color: var(--lg-amber);
}

.projects-intro {
  max-width: 500px;

  color: var(--lg-muted);

  font-size: 1rem;

  line-height: 1.8;
}


/* =========================================================
   SHOWCASE
========================================================= */

.projects-showcase {

  display: grid;

  grid-template-columns: repeat(2, minmax(0, 1fr));

  gap: 1rem;

  min-height: auto;
}

.portfolio-example {
  display: block;
  min-height: 360px;
  text-decoration: none;
  color: inherit;
}


/* =========================================================
   PROJET PRINCIPAL
========================================================= */

.featured-project {

  position: relative;

  min-height: 600px;

  overflow: hidden;

  border-radius: 1.5rem;

  background: var(--lg-ink);
}

.featured-project-media {

  position: absolute;

  inset: 0;
}

.featured-project-media img {

  width: 100%;
  height: 100%;

  object-fit: cover;

  transition:
    transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.featured-project:hover img {

  transform: scale(1.06);
}


/* OVERLAY */

.featured-project-overlay {

  position: absolute;

  inset: 0;

  background:
    linear-gradient(
      180deg,
      rgba(0, 0, 0, 0.05) 25%,
      rgba(0, 0, 0, 0.8) 100%
    );
}


/* CONTENU */

.featured-project-content {

  position: absolute;

  left: 2rem;
  right: 2rem;
  bottom: 2rem;

  color: #fff;

  z-index: 2;
}

.project-category {

  display: inline-flex;

  padding: 0.35rem 0.75rem;

  margin-bottom: 0.8rem;

  border-radius: 999px;

  background: rgba(191, 87, 28, 0.9);

  color: #fff;

  font-size: 0.68rem;

  font-weight: 700;

  text-transform: uppercase;

  letter-spacing: 0.08em;
}

.featured-project-content h3 {

  max-width: 650px;

  margin-bottom: 0.5rem;

  font-family: 'Space Grotesk', sans-serif;

  font-size: clamp(1.8rem, 3vw, 2.6rem);

  font-weight: 700;

  line-height: 1.1;

  letter-spacing: -0.025em;
}

.featured-project-content p {

  margin: 0;

  color: rgba(255, 255, 255, 0.75);

  font-size: 0.9rem;
}


/* =========================================================
   PROJETS SECONDAIRES
========================================================= */

.secondary-projects {

  display: grid;

  grid-template-rows: repeat(2, 1fr);

  gap: 1rem;
}

.secondary-project {

  position: relative;

  min-height: 0;

  overflow: hidden;

  border-radius: 1.5rem;

  background: var(--lg-ink);
}

.secondary-project-media {

  position: absolute;

  inset: 0;
}

.secondary-project-media img {

  width: 100%;
  height: 100%;

  object-fit: cover;

  transition:
    transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}

.secondary-project:hover img {

  transform: scale(1.08);
}

.secondary-project-overlay {

  position: absolute;

  inset: 0;

  background:
    linear-gradient(
      180deg,
      rgba(0, 0, 0, 0.05),
      rgba(0, 0, 0, 0.82)
    );
}

.secondary-project-info {

  position: absolute;

  left: 1.4rem;
  right: 1.4rem;
  bottom: 1.4rem;

  color: #fff;

  z-index: 2;
}

.secondary-project-info h3 {

  margin: 0.15rem 0 0.35rem;

  font-family: 'Space Grotesk', sans-serif;

  font-size: 1.3rem;

  font-weight: 700;

  line-height: 1.15;
}

.secondary-project-info > span:last-child {

  color: rgba(255, 255, 255, 0.7);

  font-size: 0.78rem;
}


/* =========================================================
   FOOTER
========================================================= */

.projects-footer {

  display: flex;

  align-items: center;

  justify-content: space-between;

  gap: 1rem;

  margin-top: 1rem;

  padding: 1.25rem 1.5rem;

  border: 1px solid var(--lg-line);

  border-radius: 1rem;

  background: var(--lg-surface);
}

.projects-counter {

  display: flex;

  align-items: baseline;

  gap: 0.6rem;
}

.projects-counter strong {

  font-family: 'Space Grotesk', sans-serif;

  font-size: 1.8rem;

  color: var(--lg-amber);
}

.projects-counter span {

  color: var(--lg-muted);

  font-size: 0.85rem;
}

.projects-button {

  display: inline-flex;

  align-items: center;

  gap: 0.6rem;

  padding: 0.75rem 1.1rem;

  border-radius: 0.7rem;

  background: var(--lg-ink);

  color: #fff;

  font-size: 0.82rem;

  font-weight: 700;

  text-decoration: none;

  transition:
    transform 0.25s ease,
    background 0.25s ease;
}

.projects-button:hover {

  color: #fff;

  background: var(--lg-amber);

  transform: translateY(-2px);
}


/* =========================================================
   ÉTAT VIDE
========================================================= */

.projects-empty {

  padding: 5rem 2rem;

  text-align: center;

  border: 2px dashed var(--lg-line);

  border-radius: 1.5rem;

  background: var(--lg-surface);
}

.projects-empty i {

  display: block;

  margin-bottom: 1rem;

  color: var(--lg-amber);

  font-size: 2.5rem;
}

.projects-empty h4 {

  margin-bottom: 0.4rem;

  font-family: 'Space Grotesk', sans-serif;
}

.projects-empty p {

  margin: 0;

  color: var(--lg-muted);
}


/* =========================================================
   SQUELETTES
========================================================= */

.projects-loading {

  display: grid;

  grid-template-columns: 1.5fr 1fr;

  gap: 1rem;

  min-height: 600px;
}

.project-skeleton {

  min-height: 280px;

  border-radius: 1.5rem;

  background:
    linear-gradient(
      100deg,
      #e8e3d5 30%,
      #f5f1e8 50%,
      #e8e3d5 70%
    );

  background-size: 200% 100%;

  animation: projectShimmer 1.5s infinite;
}

.project-skeleton:first-child {

  min-height: 600px;
}

@keyframes projectShimmer {

  0% {
    background-position: 200% 0;
  }

  100% {
    background-position: -200% 0;
  }
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 991.98px) {

  .projects-showcase {

    grid-template-columns: 1fr;

    min-height: auto;
  }

  .featured-project {

    min-height: 500px;
  }

  .secondary-projects {

    grid-template-columns: repeat(2, 1fr);

    grid-template-rows: 280px;
  }

  .projects-loading {

    grid-template-columns: 1fr;

    min-height: auto;
  }

  .project-skeleton:first-child {

    min-height: 500px;
  }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 575.98px) {

  .featured-project {

    min-height: 430px;

    border-radius: 1.2rem;
  }

  .featured-project-content {

    left: 1.25rem;
    right: 1.25rem;
    bottom: 1.25rem;
  }

  .featured-project-content h3 {

    font-size: 1.7rem;
  }

  .secondary-projects {

    grid-template-columns: 1fr;

    grid-template-rows: repeat(2, 250px);
  }

  .secondary-project {

    border-radius: 1.2rem;
  }

  .projects-footer {

    flex-direction: column;

    align-items: stretch;
  }

  .projects-counter {

    justify-content: center;
  }

  .projects-button {

    justify-content: center;
  }

}

/* =========================================================
   NOTRE MÉTHODE
========================================================= */

.method-section {
  position: relative;
  background: var(--lg-surface);
  overflow: hidden;
}

.method-section::before {
  content: '';

  position: absolute;

  width: 420px;
  height: 420px;

  left: -260px;
  bottom: -260px;

  border-radius: 50%;

  background: rgba(191, 87, 28, 0.055);

  pointer-events: none;
}

.method-title {
  max-width: 760px;

  font-family: 'Space Grotesk', sans-serif;

  font-size: clamp(2rem, 4vw, 3.2rem);

  font-weight: 700;

  line-height: 1.08;

  letter-spacing: -0.035em;

  color: var(--lg-ink);
}

.method-title span {
  display: block;

  color: var(--lg-amber);
}

.method-intro {
  max-width: 500px;

  color: var(--lg-muted);

  font-size: 1rem;

  line-height: 1.8;
}

/* TIMELINE */

.method-timeline {
  position: relative;

  display: grid;

  grid-template-columns: repeat(4, 1fr);

  gap: 0;
}

/* LIGNE CENTRALE */

.method-timeline::before {
  content: '';

  position: absolute;

  top: 38px;
  left: 12.5%;
  right: 12.5%;

  height: 1px;

  background: var(--lg-line);

  z-index: 0;
}

/* ÉTAPE */

.method-step {
  position: relative;

  padding: 0 1.2rem;

  z-index: 1;
}

/* HAUT */

.method-step-top {
  position: relative;

  display: flex;

  align-items: center;

  justify-content: center;

  height: 78px;
}

/* NUMÉRO */

.method-number {
  position: absolute;

  left: 0;

  top: -5px;

  font-family: 'Space Grotesk', sans-serif;

  font-size: 0.7rem;

  font-weight: 700;

  color: var(--lg-muted);

  letter-spacing: 0.08em;
}

/* ICÔNE */

.method-icon {
  width: 76px;
  height: 76px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: var(--lg-cream);

  border: 1px solid var(--lg-line);

  color: var(--lg-amber);

  font-size: 1.35rem;

  box-shadow:
    0 0 0 8px var(--lg-surface);

  transition:
    transform 0.3s ease,
    background 0.3s ease,
    color 0.3s ease,
    border-color 0.3s ease;
}

.method-step:hover .method-icon {
  transform: translateY(-5px);

  background: var(--lg-amber);

  border-color: var(--lg-amber);

  color: #fff;
}

/* LIGNE SOUS ICÔNE */

.method-line {
  display: none;
}

/* CONTENU */

.method-content {
  padding-top: 2.2rem;

  text-align: center;
}

.method-label {
  display: block;

  margin-bottom: 0.55rem;

  color: var(--lg-amber);

  font-size: 0.7rem;

  font-weight: 700;

  text-transform: uppercase;

  letter-spacing: 0.12em;
}

.method-content h3 {
  margin-bottom: 0.7rem;

  font-family: 'Space Grotesk', sans-serif;

  font-size: 1.25rem;

  font-weight: 700;

  color: var(--lg-ink);
}

.method-content p {
  max-width: 260px;

  margin: 0 auto;

  color: var(--lg-muted);

  font-size: 0.88rem;

  line-height: 1.7;
}

/* FOOTER / ENGAGEMENT */

.method-footer {
  margin-top: 4rem;

  padding: 1.35rem 1.5rem;

  display: flex;

  align-items: center;

  gap: 1rem;

  background: var(--lg-cream);

  border: 1px solid var(--lg-line);

  border-radius: 1rem;
}

.method-footer-icon {
  flex: 0 0 auto;

  width: 48px;
  height: 48px;

  display: flex;

  align-items: center;
  justify-content: center;

  border-radius: 14px;

  background: var(--lg-amber-soft);

  color: var(--lg-amber);

  font-size: 1.2rem;
}

.method-footer-text {
  flex-grow: 1;
}

.method-footer-text span {
  display: block;

  margin-bottom: 0.15rem;

  color: var(--lg-muted);

  font-size: 0.7rem;

  font-weight: 700;

  text-transform: uppercase;

  letter-spacing: 0.1em;
}

.method-footer-text strong {
  font-family: 'Space Grotesk', sans-serif;

  font-size: 1rem;

  color: var(--lg-ink);
}

.method-footer-btn {
  flex: 0 0 auto;

  display: inline-flex;

  align-items: center;

  gap: 0.6rem;

  padding: 0.75rem 1rem;

  border-radius: 0.7rem;

  background: var(--lg-amber);

  color: #fff;

  font-size: 0.82rem;

  font-weight: 700;

  text-decoration: none;

  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease;
}

.method-footer-btn:hover {
  color: #fff;

  transform: translateY(-2px);

  box-shadow:
    0 10px 24px rgba(191, 87, 28, 0.25);
}

/* TABLET */

@media (max-width: 991.98px) {

  .method-timeline {
    grid-template-columns: repeat(2, 1fr);

    gap: 3rem 1rem;
  }

  .method-timeline::before {
    display: none;
  }

  .method-step {
    padding: 0 1rem;
  }

  .method-footer {
    flex-wrap: wrap;
  }

}

/* MOBILE */

@media (max-width: 575.98px) {

  .method-timeline {
    grid-template-columns: 1fr;

    gap: 2.5rem;
  }

  .method-step {
    display: grid;

    grid-template-columns: 76px 1fr;

    column-gap: 1rem;

    padding: 0;
  }

  .method-step-top {
    height: 76px;

    grid-row: span 2;
  }

  .method-number {
    top: -8px;
    left: 0;
  }

  .method-content {
    padding-top: 0;

    text-align: left;
  }

  .method-content p {
    margin: 0;

    max-width: none;
  }

  .method-footer {
    align-items: flex-start;
  }

  .method-footer-btn {
    width: 100%;

    justify-content: center;
  }

}
/* =========================================================
   EXPERTISES PREMIUM
========================================================= */

.expertises-section {
  position: relative;
  background: var(--lg-cream);
  overflow: hidden;
}

.expertises-section::before {
  content: '';
  position: absolute;
  width: 420px;
  height: 420px;
  top: -220px;
  right: -180px;
  border-radius: 50%;
  background: rgba(191, 87, 28, 0.06);
  pointer-events: none;
}

.expertises-title {
  max-width: 760px;

  font-family: 'Space Grotesk', sans-serif;
  font-size: clamp(2rem, 4vw, 3.3rem);
  font-weight: 700;
  line-height: 1.08;
  letter-spacing: -0.035em;

  color: var(--lg-ink);
}

.expertises-title span {
  display: block;
  color: var(--lg-amber);
}

.expertises-intro {
  max-width: 500px;

  color: var(--lg-muted);

  font-size: 1rem;
  line-height: 1.8;
}

/* GRILLE */

.expertises-grid {
  display: grid;

  grid-template-columns: repeat(12, 1fr);

  gap: 1rem;
}

/* CARTE */

.expertise-card {
  position: relative;

  grid-column: span 4;

  min-height: 360px;

  display: flex;
  flex-direction: column;

  padding: 2rem;

  overflow: hidden;

  text-decoration: none;

  color: var(--lg-ink);

  background: var(--lg-surface);

  border: 1px solid var(--lg-line);

  border-radius: 1.25rem;

  box-shadow: 0 12px 30px rgba(23, 20, 15, 0.04);

  transition:
    transform 0.35s ease,
    box-shadow 0.35s ease,
    border-color 0.35s ease;
}

/* PREMIÈRE CARTE PLUS GRANDE */

.expertise-card-featured {
  grid-column: span 6;
  min-height: 430px;

  background:
    linear-gradient(
      145deg,
      #211c15 0%,
      #30291f 100%
    );

  color: #fff;
}

/* NUMÉRO */

.expertise-card-number {
  position: absolute;

  top: 1.25rem;
  right: 1.5rem;

  font-family: 'Space Grotesk', sans-serif;

  font-size: 0.75rem;
  font-weight: 700;

  letter-spacing: 0.12em;

  color: var(--lg-amber);
}

/* ICÔNE */

.expertise-card-icon {
  width: 58px;
  height: 58px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: auto;

  border-radius: 16px;

  background: var(--lg-amber-soft);

  color: var(--lg-amber);

  font-size: 1.5rem;

  transition:
    transform 0.35s ease,
    background 0.35s ease;
}

.expertise-card-featured .expertise-card-icon {
  background: rgba(191, 87, 28, 0.18);
}

/* CONTENU */

.expertise-card-content {
  margin-top: 3rem;
}

.expertise-card-label {
  display: block;

  margin-bottom: 0.65rem;

  color: var(--lg-amber);

  font-size: 0.7rem;
  font-weight: 700;

  letter-spacing: 0.12em;
  text-transform: uppercase;
}

.expertise-card h3 {
  max-width: 280px;

  margin-bottom: 0.8rem;

  font-family: 'Space Grotesk', sans-serif;

  font-size: 1.55rem;
  font-weight: 700;

  line-height: 1.15;

  letter-spacing: -0.025em;
}

.expertise-card-featured h3 {
  font-size: clamp(1.8rem, 3vw, 2.4rem);
}

.expertise-card p {
  max-width: 430px;

  margin-bottom: 1.25rem;

  color: var(--lg-muted);

  font-size: 0.9rem;

  line-height: 1.7;
}

.expertise-card-featured p {
  color: rgba(255, 255, 255, 0.68);
}

/* LIEN */

.expertise-card-link {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;

  color: var(--lg-ink);

  font-size: 0.82rem;
  font-weight: 700;

  transition: gap 0.25s ease;
}

.expertise-card-featured .expertise-card-link {
  color: #fff;
}

.expertise-card-link i {
  color: var(--lg-amber);
}

/* HOVER */

.expertise-card:hover {
  transform: translateY(-7px);

  border-color: var(--lg-amber);

  box-shadow:
    0 24px 55px rgba(23, 20, 15, 0.1);

  color: var(--lg-ink);
}

.expertise-card-featured:hover {
  color: #fff;
}

.expertise-card:hover .expertise-card-icon {
  transform: translateY(-4px) rotate(-5deg) scale(1.05);
}

.expertise-card:hover .expertise-card-link {
  gap: 0.75rem;
}

/* EFFET DÉCORATIF */

.expertise-card::after {
  content: '';

  position: absolute;

  width: 180px;
  height: 180px;

  right: -80px;
  bottom: -80px;

  border-radius: 50%;

  border: 1px solid rgba(191, 87, 28, 0.1);

  transition:
    transform 0.5s ease,
    border-color 0.5s ease;
}

.expertise-card:hover::after {
  transform: scale(1.5);

  border-color: rgba(191, 87, 28, 0.22);
}

/* CTA BAS */

.expertises-bottom {
  margin-top: 1rem;

  padding: 1.5rem 1.75rem;

  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.5rem;

  background: var(--lg-surface);

  border: 1px solid var(--lg-line);

  border-radius: 1rem;
}

.expertises-bottom-label {
  display: block;

  margin-bottom: 0.2rem;

  color: var(--lg-muted);

  font-size: 0.75rem;
  font-weight: 600;

  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.expertises-bottom strong {
  font-family: 'Space Grotesk', sans-serif;

  font-size: 1.15rem;
}

.expertises-bottom-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.65rem;

  padding: 0.75rem 1rem;

  border-radius: 0.7rem;

  background: var(--lg-amber);

  color: #fff;

  font-size: 0.82rem;
  font-weight: 700;

  text-decoration: none;

  white-space: nowrap;

  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease;
}

.expertises-bottom-btn:hover {
  color: #fff;

  transform: translateY(-2px);

  box-shadow:
    0 10px 25px rgba(191, 87, 28, 0.25);
}

/* TABLET */

@media (max-width: 991.98px) {

  .expertise-card,
  .expertise-card-featured {
    grid-column: span 6;
    min-height: 350px;
  }

  .expertise-card-featured {
    min-height: 390px;
  }

}

/* MOBILE */

@media (max-width: 767.98px) {

  .expertises-grid {
    grid-template-columns: 1fr;
  }

  .expertise-card,
  .expertise-card-featured {
    grid-column: auto;

    min-height: 330px;
  }

  .expertise-card-featured {
    min-height: 370px;
  }

  .expertises-title {
    font-size: 2rem;
  }

  .expertises-bottom {
    flex-direction: column;
    align-items: flex-start;
  }

  .expertises-bottom-btn {
    width: 100%;
    justify-content: center;
  }

}
/* =========================================================
   HERO PREMIUM
========================================================= */

.home-hero {
  position: relative;
  min-height: 78vh;
  display: flex;
  align-items: center;
  overflow: hidden;
  background: #17140f;
  color: #fff;
}

.home-hero-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
}

.home-hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transform: scale(1.02);
}

.home-hero-overlay {
  position: absolute;
  inset: 0;

  background:
    linear-gradient(
      90deg,
      rgba(18, 15, 11, 0.96) 0%,
      rgba(18, 15, 11, 0.86) 42%,
      rgba(18, 15, 11, 0.55) 72%,
      rgba(18, 15, 11, 0.35) 100%
    );
}

.home-hero-content {
  z-index: 2;
  padding-top: 6rem;
  padding-bottom: 6rem;
}

.min-vh-75 {
  min-height: 75vh;
}

/* BADGE */

.home-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.65rem;

  padding: 0.55rem 0.9rem;

  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 999px;

  background: rgba(255, 255, 255, 0.07);

  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);

  color: rgba(255, 255, 255, 0.88);

  font-size: 0.75rem;
  font-weight: 700;

  letter-spacing: 0.12em;
  text-transform: uppercase;
}

.home-hero-badge-dot {
  width: 7px;
  height: 7px;

  border-radius: 50%;

  background: var(--lg-amber);

  box-shadow: 0 0 0 5px rgba(191, 87, 28, 0.15);
}

.home-hero-badge-line {
  width: 24px;
  height: 1px;

  background: rgba(255, 255, 255, 0.3);
}

/* TITRE */

.home-hero-title {
  max-width: 850px;

  font-family: 'Space Grotesk', sans-serif;

  font-size: clamp(2.8rem, 6vw, 5.5rem);

  font-weight: 700;

  line-height: 0.98;

  letter-spacing: -0.045em;

  color: #fff;
}

.home-hero-title span {
  display: block;
  color: var(--lg-amber);
}

/* DESCRIPTION */

.home-hero-description {
  max-width: 650px;

  color: rgba(255, 255, 255, 0.76);

  font-size: clamp(1rem, 1.4vw, 1.15rem);

  line-height: 1.75;
}

/* BOUTONS */

.home-hero-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.8rem;
}

.hero-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.65rem;

  min-height: 52px;

  padding: 0.8rem 1.25rem;

  border-radius: 0.75rem;

  font-size: 0.9rem;
  font-weight: 700;

  text-decoration: none;

  transition:
    transform 0.25s ease,
    background 0.25s ease,
    border-color 0.25s ease,
    box-shadow 0.25s ease;
}

.hero-btn i {
  transition: transform 0.25s ease;
}

.hero-btn:hover {
  transform: translateY(-3px);
}

.hero-btn:hover i {
  transform: translate(2px, -2px);
}

.hero-btn-primary {
  background: var(--lg-amber);
  color: #fff;

  box-shadow:
    0 12px 28px rgba(191, 87, 28, 0.3);
}

.hero-btn-primary:hover {
  background: #d36a28;
  color: #fff;

  box-shadow:
    0 18px 38px rgba(191, 87, 28, 0.4);
}

.hero-btn-outline {
  border: 1px solid rgba(255, 255, 255, 0.28);

  background: rgba(255, 255, 255, 0.06);

  color: #fff;

  backdrop-filter: blur(8px);
}

.hero-btn-outline:hover {
  border-color: rgba(255, 255, 255, 0.5);

  background: rgba(255, 255, 255, 0.12);

  color: #fff;
}

/* SERVICES */

.home-hero-services {
  display: flex;
  flex-wrap: wrap;
  gap: 0.65rem;
}

.home-hero-services span {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;

  padding: 0.5rem 0.75rem;

  border-radius: 999px;

  background: rgba(255, 255, 255, 0.07);

  border: 1px solid rgba(255, 255, 255, 0.12);

  color: rgba(255, 255, 255, 0.72);

  font-size: 0.78rem;
  font-weight: 600;

  backdrop-filter: blur(8px);
}

.home-hero-services i {
  color: var(--lg-amber);
}

/* SCROLL */

.home-hero-scroll {
  position: absolute;

  bottom: 1.5rem;
  right: 2rem;

  z-index: 3;

  display: flex;
  align-items: center;
  gap: 0.6rem;

  color: rgba(255, 255, 255, 0.6);

  font-size: 0.72rem;
  font-weight: 600;

  letter-spacing: 0.12em;
  text-transform: uppercase;

  writing-mode: vertical-rl;
}

.home-hero-scroll i {
  color: var(--lg-amber);

  animation: heroScroll 1.8s ease-in-out infinite;
}

@keyframes heroScroll {

  0%,
  100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(7px);
  }
}

/* MOBILE */

@media (max-width: 991.98px) {

  .home-hero {
    min-height: auto;
  }

  .home-hero-content {
    padding-top: 7rem;
    padding-bottom: 6rem;
  }

  .home-hero-overlay {
    background:
      linear-gradient(
        180deg,
        rgba(18, 15, 11, 0.7) 0%,
        rgba(18, 15, 11, 0.88) 60%,
        rgba(18, 15, 11, 0.97) 100%
      );
  }

  .home-hero-title {
    font-size: clamp(2.6rem, 10vw, 4rem);
  }

  .home-hero-scroll {
    display: none;
  }
}

@media (max-width: 575.98px) {

  .home-hero-content {
    padding-top: 6rem;
    padding-bottom: 4.5rem;
  }

  .home-hero-badge {
    font-size: 0.65rem;
  }

  .home-hero-title {
    font-size: 2.7rem;
  }

  .home-hero-description {
    font-size: 0.95rem;
  }

  .home-hero-actions {
    flex-direction: column;
  }

  .hero-btn {
    width: 100%;
  }

  .home-hero-services {
    gap: 0.45rem;
  }

  .home-hero-services span {
    font-size: 0.72rem;
  }
}
.home-hero {
  min-height: 92vh;
  background-image: linear-gradient(120deg, rgba(23, 20, 15, 0.85) 0%, rgba(23, 20, 15, 0.55) 60%, rgba(23, 20, 15, 0.4) 100%), url('/images/hero-construction.png');
  background-size: cover;
  background-position: center;
  position: relative;
}
.home-hero .position-relative {
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
.hero-title {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  color: #fff;
  font-size: clamp(2.4rem, 5vw, 4.2rem);
  line-height: 1.05;
  letter-spacing: -0.02em;
}
.hero-panel {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.16);
  border-radius: 1.25rem;
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}
.depth-caption {
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.82rem;
}
.depth-scale {
  display: flex;
  flex-direction: column;
  gap: 1.1rem;
}
.depth-row {
  display: grid;
  grid-template-columns: 3.2rem 1fr 5rem;
  align-items: center;
  gap: 0.85rem;
}
.depth-row .depth-marker {
  color: #f6e7da;
}
.depth-track {
  height: 6px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.14);
  overflow: hidden;
}
.depth-fill {
  display: block;
  height: 100%;
  border-radius: 999px;
  background: linear-gradient(90deg, var(--lg-amber), #f0a15c);
  animation: fillBar 1.4s cubic-bezier(0.16, 1, 0.3, 1) both;
}
@keyframes fillBar {
  from { width: 0 !important; }
}
.depth-place {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.75);
  text-align: right;
}
.method-mini {
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1.1rem;
  padding: 1.75rem;
  height: 100%;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.method-mini:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(23, 20, 15, 0.08);
}

/* Stats */
.stat-value {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  font-size: 2.4rem;
  color: var(--lg-amber);
  line-height: 1;
}
.stat-label {
  color: #cfc7b6;
  font-size: 0.9rem;
  margin-top: 0.4rem;
}

/* Expertise */
.expertise-img {
  aspect-ratio: 4 / 3;
}
.feature-list li {
  color: var(--lg-ink-soft);
  margin-bottom: 0.75rem;
  font-weight: 500;
}

/* Services */
.service-card {
  display: flex;
  flex-direction: column;
}
.service-card:hover {
  transform: translateY(-6px);
  border-color: var(--lg-amber) !important;
  box-shadow: 0 24px 48px rgba(191, 87, 28, 0.14);
}
.service-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: var(--lg-amber-soft);
  color: var(--lg-amber);
  font-size: 1.5rem;
}
.service-link {
  font-weight: 600;
  color: var(--lg-amber);
}
.service-link:hover {
  color: var(--lg-amber-dark);
}

/* Why */
.why-card {
  background: var(--lg-cream-2);
}
.why-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 52px;
  height: 52px;
  border-radius: 14px;
  background: var(--lg-ink);
  color: #fff;
  font-size: 1.3rem;
}

/* Vidéos du portfolio */
.projects-videos {
  margin-top: 1.5rem;
}
.project-video-card {
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1.1rem;
  overflow: hidden;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.project-video-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(23, 20, 15, 0.1);
}
.project-video-card video {
  display: block;
  aspect-ratio: 4 / 3;
  object-fit: cover;
  background: #000;
}
.project-video-caption {
  padding: 1rem 1.1rem;
}
.project-video-caption strong {
  display: block;
  color: var(--lg-ink);
  font-size: 1rem;
}
.project-video-caption span {
  margin-top: 0.25rem;
  color: var(--lg-muted);
  font-size: 0.8rem;
}

/* CTA */
.cta-section {
  padding: 4rem 0 5rem;
}
.cta-card {
  background: linear-gradient(135deg, #17140f 0%, #2c281f 100%);
  border-radius: 2rem;
  padding: 4rem 2rem;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(191, 87, 28, 0.3);
}
.cta-card::before {
  content: '';
  position: absolute;
  top: -60px;
  right: -60px;
  width: 240px;
  height: 240px;
  background: radial-gradient(circle, rgba(191, 87, 28, 0.35), transparent 70%);
}


</style>