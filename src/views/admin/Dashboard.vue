<template>
  <div class="container py-4">

    <Breadcrumb titre="Tableau de bord" page="Dashboard" />
    <p class="text-muted mb-4 mt-n3">Bienvenue dans l'administration de LIMANYA GROUPE.</p>

    <div class="row">

      <div class="col-lg-3 col-md-6 mb-4">
        <CardStat titre="Actualités" :nombre="stats.actualites" icone="📰" couleur="primary" />
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <CardStat titre="Réalisations" :nombre="stats.realisations" icone="🏗" couleur="success" />
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <CardStat titre="Devis" :nombre="stats.devis" icone="📄" couleur="warning" />
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <CardStat titre="Messages" :nombre="stats.contacts" icone="📩" couleur="danger" />
      </div>

    </div>

    <div class="card shadow-sm border-0 mt-2">

      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="bi bi-newspaper text-amber me-2"></i>Dernières actualités
        </h5>
        <RouterLink to="/admin/actualites" class="small fw-semibold text-decoration-none">
          Voir tout <i class="bi bi-arrow-right ms-1"></i>
        </RouterLink>
      </div>

      <div class="card-body p-0">

        <div v-if="chargementActualites" class="text-center text-muted py-5">
          <span class="spinner-border spinner-border-sm me-2"></span>Chargement...
        </div>

        <div v-else-if="dernieresActualites.length === 0" class="text-center text-muted py-5">
          <i class="bi bi-newspaper fs-3 d-block mb-2"></i>
          Aucune actualité pour le moment.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr>
                <th>Date</th>
                <th>Titre</th>
                <th>Statut</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in dernieresActualites" :key="item.id">
                <td>{{ formatDate(item.date_publication) }}</td>
                <td>{{ item.titre }}</td>
                <td>
                  <span class="badge" :class="item.statut === 'Publié' ? 'bg-success' : 'bg-secondary'">
                    {{ item.statut || 'Publié' }}
                  </span>
                </td>
                <td>
                  <RouterLink class="btn btn-sm btn-primary" :to="'/admin/actualites/modifier/' + item.id">
                    Modifier
                  </RouterLink>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">

      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="bi bi-envelope text-amber me-2"></i>Derniers messages
        </h5>
        <RouterLink to="/admin/contacts" class="small fw-semibold text-decoration-none">
          Voir tout <i class="bi bi-arrow-right ms-1"></i>
        </RouterLink>
      </div>

      <div class="card-body p-0">

        <div v-if="chargementContacts" class="text-center text-muted py-5">
          <span class="spinner-border spinner-border-sm me-2"></span>Chargement...
        </div>

        <div v-else-if="derniersContacts.length === 0" class="text-center text-muted py-5">
          <i class="bi bi-envelope fs-3 d-block mb-2"></i>
          Aucun message pour le moment.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr>
                <th>Date</th>
                <th>Nom</th>
                <th>Sujet</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in derniersContacts" :key="item.id">
                <td>{{ formatDate(item.created_at) }}</td>
                <td>{{ item.nom }}</td>
                <td>{{ item.sujet || '—' }}</td>
                <td>{{ item.email }}</td>
                <td>
                  <span class="badge" :class="item.lu ? 'bg-secondary' : 'bg-warning text-dark'">
                    {{ item.lu ? 'Lu' : 'Non lu' }}
                  </span>
                </td>
                <td>
                  <RouterLink class="btn btn-sm btn-primary" :to="'/admin/contacts/' + item.id">
                    Voir
                  </RouterLink>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import CardStat from '../../components/admin/CardStat.vue'
import Breadcrumb from '../../components/admin/Breadcrumb.vue'

const dernieresActualites = ref([])
const derniersContacts = ref([])
const chargementActualites = ref(true)
const chargementContacts = ref(true)

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('fr-FR')
}

const stats = ref({
  actualites: 0,
  realisations: 0,
  devis: 0,
  contacts: 0
})

const chargerStatistiques = async () => {
  try {
    const response = await api.get('/dashboard')
    stats.value = response.data
  } catch (error) {
    console.error(error)
  }
}

const chargerDernieresActualites = async () => {
  chargementActualites.value = true
  try {
    const response = await api.get('/actualites?limit=5')
    dernieresActualites.value = response.data
  } catch (error) {
    console.error(error)
  } finally {
    chargementActualites.value = false
  }
}

const chargerDerniersContacts = async () => {
  chargementContacts.value = true
  try {
    const response = await api.get('/contacts?limit=5')
    derniersContacts.value = response.data
  } catch (error) {
    console.error(error)
  } finally {
    chargementContacts.value = false
  }
}

onMounted(() => {
  chargerStatistiques()
  chargerDernieresActualites()
  chargerDerniersContacts()
})
</script>
