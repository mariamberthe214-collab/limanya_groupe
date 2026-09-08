<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import api from '../../services/api'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'

const devis = ref([])
const recherche = ref('')
const page = ref(1)
const parPage = 10
const showModal = ref(false)
const devisASupprimer = ref(null)
const erreur = ref('')

watch(recherche, () => {
  page.value = 1
})

const chargerDevis = async () => {
  try {
    const response = await api.get('/devis')
    const data = Array.isArray(response?.data) ? response.data : []
    devis.value = data
  } catch (error) {
    console.error(error)
    devis.value = []
    erreur.value = error.response?.data?.message || 'Impossible de charger les devis.'
  }
}

const devisFiltres = computed(() => {
  const texte = recherche.value.toLowerCase()
  return devis.value.filter((item) => {
    return (
      (item.nom ?? '').toLowerCase().includes(texte) ||
      (item.email ?? '').toLowerCase().includes(texte) ||
      (item.service ?? '').toLowerCase().includes(texte)
    )
  })
})

const totalPages = computed(() => Math.ceil(devisFiltres.value.length / parPage))

const devisPagine = computed(() => {
  const debut = (page.value - 1) * parPage
  return devisFiltres.value.slice(debut, debut + parPage)
})

const ouvrirConfirmation = (id) => {
  devisASupprimer.value = id
  showModal.value = true
}

const changerStatut = async (item) => {
  try {
    const enAttente = ['nouveau', 'En attente', 'en attente'].includes(item.statut)
    const nouveauStatut = enAttente ? 'traité' : 'nouveau'
    await api.put(`/devis/${item.id}`, { ...item, statut: nouveauStatut })
    item.statut = nouveauStatut
  } catch (error) {
    console.error(error)
  }
}

const supprimer = async () => {
  try {
    await api.delete(`/devis/${devisASupprimer.value}`)
    showModal.value = false
    chargerDevis()
  } catch (error) {
    console.error(error)
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR')
}

onMounted(chargerDevis)
</script>

<template>
  <div class="container py-4 admin-devis-page">
    <div class="admin-page-header mb-1">
      <h2 class="fw-bold mb-0">
        <i class="bi bi-file-earmark-text text-amber me-2"></i>Gestion des devis
      </h2>
    </div>
    <p class="text-muted mb-4">Suivez et traitez les demandes de devis reçues.</p>
    <div v-if="erreur" class="alert alert-danger" role="alert">
      <i class="bi bi-exclamation-triangle me-2"></i>{{ erreur }}
    </div>

    <div class="mb-3 position-relative" style="max-width: 420px;">
      <i class="bi bi-search position-absolute" style="left: 16px; top: 50%; transform: translateY(-50%); color: #b3ab99;"></i>
      <input
        v-model="recherche"
        class="form-control ps-5"
        placeholder="Rechercher un devis..."
      />
    </div>

    <div class="table-responsive">
    <table class="table table-hover align-middle devis-table">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Téléphone</th>
          <th>Service</th>
          <th>Message</th>
          <th>Statut</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in devisPagine" :key="item.id" style="cursor:pointer" @click="$router.push(`/admin/devis/${item.id}`)">
          <td>{{ item.id }}</td>
          <td>{{ item.nom }}</td>
          <td>{{ item.telephone || '—' }}</td>
          <td>{{ item.service }}</td>
          <td class="text-truncate" style="max-width:220px;">{{ item.message || '—' }}</td>
          <td>
            <span class="badge" :class="['nouveau', 'En attente', 'en attente'].includes(item.statut) ? 'bg-warning text-dark' : 'bg-success'">
              {{ item.statut || 'nouveau' }}
            </span>
          </td>
          <td>{{ formatDate(item.created_at) }}</td>
          <td class="devis-actions" @click.stop>
            <div class="devis-actions-inner">
              <RouterLink :to="`/admin/devis/${item.id}`" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-eye me-1"></i><span>Voir</span>
              </RouterLink>
              <button class="btn btn-outline-success btn-sm" @click="changerStatut(item)">
                <i class="bi bi-arrow-repeat me-1"></i><span>Statut</span>
              </button>
              <button class="btn btn-danger btn-sm" @click="ouvrirConfirmation(item.id)">
                <i class="bi bi-trash me-1"></i><span>Supprimer</span>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
      <button class="btn btn-outline-primary me-2" :disabled="page === 1" @click="page--">← Précédent</button>
      <span class="align-self-center">Page {{ page }} / {{ totalPages || 1 }}</span>
      <button class="btn btn-outline-primary ms-2" :disabled="page >= totalPages" @click="page++">Suivant →</button>
    </div>
  </div>

  <ConfirmModal
    :show="showModal"
    title="Supprimer un devis"
    message="Voulez-vous vraiment supprimer ce devis ?"
    @confirm="supprimer"
    @cancel="showModal = false"
  />
</template>

<style scoped>
.devis-table {
  min-width: 920px;
}

.admin-page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.devis-actions {
  min-width: 250px;
}

.devis-actions-inner {
  display: flex;
  flex-wrap: wrap;
  gap: 0.45rem;
}

.devis-actions .btn {
  white-space: nowrap;
}

@media (max-width: 767.98px) {
  .admin-page-header {
    align-items: flex-start;
  }

  .admin-devis-page h2 {
    font-size: 1.45rem;
    line-height: 1.2;
  }

  .devis-table {
    min-width: 780px;
  }

  .devis-actions {
    min-width: 210px;
  }

  .devis-actions-inner {
    flex-direction: column;
    align-items: stretch;
  }

  .devis-actions .btn {
    width: 100%;
  }
}
</style>