<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'
import { buildWhatsAppLink } from '../utils/whatsapp'

const items = [
  'Panneaux solaires pour une alimentation en énergie autonome et durable',
  'Pompes électriques, solaires et hybrides pour tous vos besoins en eau',
  'PVC hydrauliques pour vos installations et réseaux de distribution',
  'Conseil et installation adaptés à votre site',
]

const categoriesVentes = [
  'Panneaux solaires',
  'Pompes électriques, solaires et hybrides',
  'PVC hydrauliques',
]

const produits = ref([])
const chargement = ref(true)

const demanderPrix = (nom) => {
  return buildWhatsAppLink(`Bonjour LIMANYA Groupe, je suis intéressé(e) par "${nom}". Pouvez-vous me donner plus d'informations ?`)
}

const chargerProduits = async () => {
  chargement.value = true
  try {
    const response = await api.get('/equipements')
    produits.value = response.data.filter((e) => categoriesVentes.includes(e.categorie))
  } catch (error) {
    console.error(error)
  } finally {
    chargement.value = false
  }
}

onMounted(chargerProduits)
</script>

<template>
  <section class="page-hero py-5">
    <div class="container py-4">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <span class="eyebrow">Ventes</span>
          <h1 class="display-5 fw-bold mt-3">Panneaux solaires, pompes et équipements hydrauliques</h1>
          <p class="lead mt-4">LIMANYA Groupe vend et installe des panneaux solaires, des pompes électriques, solaires et hybrides, ainsi que des PVC hydrauliques pour garantir la disponibilité permanente de l'eau sur vos sites.</p>
          <ul class="list-unstyled mt-4">
            <li class="d-flex align-items-center mb-3" v-for="item in items" :key="item">
              <i class="bi bi-check-circle-fill text-amber me-2"></i>{{ item }}
            </li>
          </ul>
          <router-link to="/contacts" class="btn btn-primary btn-lg mt-2">Demander un devis</router-link>
        </div>
        <div class="col-lg-6">
          <div class="hero-media ratio ratio-4x3">
            <img src="/images/service-materiel.png" alt="Panneaux solaires, pompes et équipements hydrauliques" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">

      <div class="text-center mb-5">
        <span class="eyebrow text-uppercase">Catalogue</span>
        <h2 class="fw-bold mt-3">Nos produits disponibles à la vente</h2>
        <p class="text-muted">Panneaux solaires, pompes et équipements hydrauliques.</p>
      </div>

      <div v-if="chargement" class="text-center text-muted py-5">
        <span class="spinner-border spinner-border-sm me-2"></span>Chargement du catalogue...
      </div>

      <div v-else-if="produits.length === 0" class="text-center text-muted py-5">
        <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
        Catalogue en cours de mise à jour. Contactez-nous pour connaître nos disponibilités.
      </div>

      <div v-else class="row g-4">
        <div class="col-md-6 col-lg-4" v-for="produit in produits" :key="produit.id">
          <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="ratio ratio-4x3 bg-secondary bg-opacity-10">
              <img
                v-if="produit.image"
                :src="getImageUrl(produit.image)"
                :alt="produit.nom"
                class="w-100 h-100 object-fit-cover">
              <div v-else class="d-flex align-items-center justify-content-center text-muted">
                <i class="bi bi-image fs-1"></i>
              </div>
            </div>
            <div class="p-4">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="mb-0">{{ produit.nom }}</h5>
                <span class="badge" :class="produit.disponible == 1 ? 'bg-success' : 'bg-secondary'">
                  {{ produit.disponible == 1 ? 'Disponible' : 'Indisponible' }}
                </span>
              </div>
              <span class="badge bg-amber-soft text-amber mb-2">{{ produit.categorie }}</span>
              <p class="text-muted small">{{ produit.description }}</p>
              <p class="fw-bold text-amber mb-3" v-if="produit.prix">{{ Number(produit.prix).toLocaleString('fr-FR') }} FCFA</p>
              <a :href="demanderPrix(produit.nom)" target="_blank" class="btn btn-outline-success btn-sm w-100">
                <i class="bi bi-whatsapp me-1"></i>Demander via WhatsApp
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<style scoped>
.bg-amber-soft {
  background: #f6e7da;
}
</style>
