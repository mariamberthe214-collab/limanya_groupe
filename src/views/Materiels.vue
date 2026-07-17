<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'
import { buildWhatsAppLink } from '../utils/whatsapp'

const items = [
  'Pompes solaires, électriques et hybrides',
  'Panneaux solaires et PVC hydrauliques',
  'Équipements hydrauliques et matériels de forage',
  'Accessoires et pièces pour vos installations',
]

const equipements = ref([])
const chargement = ref(true)
const filtreActif = ref(null)

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
  return buildWhatsAppLink(`Bonjour LIMANYA Groupe, je suis intéressé(e) par "${nom}". Pouvez-vous me donner plus d'informations ?`)
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

onMounted(chargerEquipements)
</script>

<template>
  <section class="page-hero py-5">
    <div class="container py-4">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <span class="eyebrow">Vente de Matériels</span>
          <h1 class="display-5 fw-bold mt-3">Le matériel adapté à chaque projet hydraulique</h1>
          <p class="lead mt-4">LIMANYA Groupe vend pompes, panneaux solaires, PVC hydrauliques, équipements et matériels de forage pour tous vos besoins en eau.</p>
          <ul class="list-unstyled mt-4">
            <li class="d-flex align-items-center mb-3" v-for="item in items" :key="item">
              <i class="bi bi-check-circle-fill text-amber me-2"></i>{{ item }}
            </li>
          </ul>
          <router-link to="/contacts" class="btn btn-primary btn-lg mt-2">Demander un devis</router-link>
        </div>
        <div class="col-lg-6">
          <div class="hero-media ratio ratio-4x3">
            <img src="/images/service-materiel.png" alt="Matériel et pompes de forage" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">

      <div class="text-center mb-4">
        <span class="eyebrow text-uppercase">Catalogue</span>
        <h2 class="fw-bold mt-3">Nos produits disponibles à la vente</h2>
        <p class="text-muted">Cette page réunit tout notre catalogue — filtrez par catégorie pour trouver rapidement ce qu'il vous faut.</p>
      </div>

      <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" v-if="categoriesPresentes.length > 1">
        <button
          class="btn btn-sm"
          :class="filtreActif === null ? 'btn-primary' : 'btn-outline-secondary'"
          @click="filtreActif = null">
          Tout voir
        </button>
        <button
          v-for="cat in categoriesPresentes"
          :key="cat"
          class="btn btn-sm"
          :class="filtreActif === cat ? 'btn-primary' : 'btn-outline-secondary'"
          @click="filtreActif = cat">
          {{ cat }}
        </button>
      </div>

      <div v-if="chargement" class="text-center text-muted py-5">
        <span class="spinner-border spinner-border-sm me-2"></span>Chargement du catalogue...
      </div>

      <div v-else-if="equipementsFiltres.length === 0" class="text-center text-muted py-5">
        <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
        Catalogue en cours de mise à jour. Contactez-nous pour connaître nos disponibilités.
      </div>

      <div v-else class="row g-4">
        <div class="col-md-6 col-lg-4" v-for="equip in equipementsFiltres" :key="equip.id">
          <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="ratio ratio-4x3 bg-secondary bg-opacity-10">
              <img
                v-if="equip.image"
                :src="getImageUrl(equip.image)"
                :alt="equip.nom"
                class="w-100 h-100 object-fit-cover">
              <div v-else class="d-flex align-items-center justify-content-center text-muted">
                <i class="bi bi-image fs-1"></i>
              </div>
            </div>
            <div class="p-4">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="mb-0">{{ equip.nom }}</h5>
                <span class="badge" :class="equip.disponible == 1 ? 'bg-success' : 'bg-secondary'">
                  {{ equip.disponible == 1 ? 'Disponible' : 'Indisponible' }}
                </span>
              </div>
              <span class="badge bg-amber-soft text-amber mb-2" v-if="equip.categorie">{{ equip.categorie }}</span>
              <p class="text-muted small">{{ equip.description }}</p>
              <p class="fw-bold text-amber mb-3" v-if="equip.prix">{{ Number(equip.prix).toLocaleString('fr-FR') }} FCFA</p>
              <a :href="demanderPrix(equip.nom)" target="_blank" class="btn btn-outline-success btn-sm w-100">
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
