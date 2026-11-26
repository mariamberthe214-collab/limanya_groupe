<template>
  <div v-if="role === 'gestionnaire_contenu'" class="container py-4 admin-dashboard-page">
    <Breadcrumb titre="Tableau de bord" page="Contenu" />
    <p class="text-muted mb-4 mt-n3">Suivez les actualités et les réalisations du site.</p>

    <div class="row">
      <div class="col-lg-4 col-md-6 mb-4">
        <CardStat titre="Actualités publiées" :nombre="actualitesContenu.filter((item) => item.statut === 'Publié').length" icone="📰" couleur="primary" />
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <CardStat titre="Brouillons" :nombre="actualitesContenu.filter((item) => item.statut !== 'Publié').length" icone="✏️" couleur="warning" />
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <CardStat titre="Réalisations" :nombre="realisationsContenu.length" icone="🏗" couleur="success" />
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-auto"><RouterLink to="/admin/actualites" class="btn btn-primary"><i class="bi bi-newspaper me-2"></i>Gérer les actualités</RouterLink></div>
      <div class="col-auto"><RouterLink to="/admin/realisations" class="btn btn-outline-primary"><i class="bi bi-building me-2"></i>Gérer les réalisations</RouterLink></div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0"><i class="bi bi-newspaper text-amber me-2"></i>Actualités récentes</h2>
        <RouterLink to="/admin/actualites" class="small fw-semibold text-decoration-none">Voir tout <i class="bi bi-arrow-right ms-1"></i></RouterLink>
      </div>
      <div v-if="chargementContenu" class="text-center text-muted py-5"><span class="spinner-border spinner-border-sm me-2"></span>Chargement...</div>
      <div v-else-if="!actualitesContenu.length" class="text-center text-muted py-5">Aucune actualité pour le moment.</div>
      <div v-else class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-dark"><tr><th>Date</th><th>Titre</th><th>Statut</th><th>Action</th></tr></thead>
          <tbody>
            <tr v-for="item in actualitesContenu.slice(0, 5)" :key="item.id">
              <td>{{ formatDate(item.date_publication) }}</td>
              <td>{{ item.titre }}</td>
              <td><span class="badge" :class="item.statut === 'Publié' ? 'bg-success' : 'bg-secondary'">{{ item.statut || 'Publié' }}</span></td>
              <td><RouterLink class="btn btn-sm btn-primary" :to="`/admin/actualites/modifier/${item.id}`">Modifier</RouterLink></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div v-else-if="role === 'gestionnaire_commercial'" class="container py-4 admin-dashboard-page">
    <Breadcrumb titre="Tableau de bord" page="Commercial" />
    <p class="text-muted mb-4 mt-n3">Suivez les demandes des clients et le catalogue d’équipements.</p>

    <div class="row">
      <div class="col-lg-4 col-md-6 mb-4">
        <CardStat titre="Devis en attente" :nombre="devisCommerciaux.filter((item) => ['nouveau', 'En attente', 'en attente'].includes(item.statut)).length" icone="📄" couleur="warning" />
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <CardStat titre="Messages non lus" :nombre="messagesCommerciaux.filter((item) => !item.lu).length" icone="📩" couleur="danger" />
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <CardStat titre="Équipements disponibles" :nombre="equipementsCommerciaux.filter((item) => Number(item.disponible) === 1).length" icone="📦" couleur="success" />
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-auto"><RouterLink to="/admin/demandes" class="btn btn-primary"><i class="bi bi-inboxes me-2"></i>Ouvrir les demandes clients</RouterLink></div>
      <div class="col-auto"><RouterLink to="/admin/equipements" class="btn btn-outline-primary"><i class="bi bi-truck me-2"></i>Gérer les équipements</RouterLink></div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0"><i class="bi bi-inboxes text-amber me-2"></i>Demandes récentes</h2>
        <RouterLink to="/admin/demandes" class="small fw-semibold text-decoration-none">Voir tout <i class="bi bi-arrow-right ms-1"></i></RouterLink>
      </div>
      <div v-if="chargementCommercial" class="text-center text-muted py-5"><span class="spinner-border spinner-border-sm me-2"></span>Chargement...</div>
      <div v-else-if="!demandesCommerciales.length" class="text-center text-muted py-5">Aucune demande pour le moment.</div>
      <div v-else class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-dark"><tr><th>Type</th><th>Date</th><th>Client</th><th>Sujet / Service</th><th>État</th><th>Action</th></tr></thead>
          <tbody>
            <tr v-for="item in demandesCommerciales.slice(0, 5)" :key="`${item.type}-${item.id}`">
              <td><span class="badge" :class="item.type === 'devis' ? 'bg-primary' : 'bg-secondary'">{{ item.type === 'devis' ? 'Devis' : 'Message' }}</span></td>
              <td>{{ formatDate(item.created_at) }}</td>
              <td>{{ item.nom }}</td>
              <td>{{ item.type === 'devis' ? item.service || 'Devis général' : item.sujet || 'Message général' }}</td>
              <td>
                <span v-if="item.type === 'message'" class="badge" :class="item.lu ? 'bg-success' : 'bg-warning text-dark'">{{ item.lu ? 'Lu' : 'Non lu' }}</span>
                <span v-else class="badge" :class="['nouveau', 'En attente', 'en attente'].includes(item.statut) ? 'bg-warning text-dark' : 'bg-success'">{{ item.statut || 'En attente' }}</span>
              </td>
              <td><RouterLink class="btn btn-sm btn-primary" :to="item.type === 'devis' ? `/admin/devis/${item.id}` : `/admin/contacts/${item.id}`">Ouvrir</RouterLink></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div v-else class="container py-4 admin-dashboard-page">

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

<style scoped>
.admin-dashboard-page .table {
  min-width: 620px;
}

@media (max-width: 767.98px) {
  .admin-dashboard-page {
    padding-top: 1.25rem !important;
  }

  .admin-dashboard-page .card-header {
    align-items: flex-start !important;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  .admin-dashboard-page .card-header h5 {
    font-size: 1rem;
  }
}
</style>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import CardStat from '../../components/admin/CardStat.vue'
import Breadcrumb from '../../components/admin/Breadcrumb.vue'

const dernieresActualites = ref([])
const derniersContacts = ref([])
const chargementActualites = ref(true)
const chargementContacts = ref(true)
const role = ref('admin')
const chargementContenu = ref(true)
const chargementCommercial = ref(true)
const actualitesContenu = ref([])
const realisationsContenu = ref([])
const messagesCommerciaux = ref([])
const devisCommerciaux = ref([])
const equipementsCommerciaux = ref([])
const demandesCommerciales = ref([])

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

const chargerDashboardContenu = async () => {
  chargementContenu.value = true
  const [actualitesResult, realisationsResult] = await Promise.allSettled([
    api.get('/actualites'),
    api.get('/realisations'),
  ])
  if (actualitesResult.status === 'fulfilled' && Array.isArray(actualitesResult.value.data)) {
    actualitesContenu.value = actualitesResult.value.data
  }
  if (realisationsResult.status === 'fulfilled' && Array.isArray(realisationsResult.value.data)) {
    realisationsContenu.value = realisationsResult.value.data
  }
  chargementContenu.value = false
}

const chargerDashboardCommercial = async () => {
  chargementCommercial.value = true
  const [messagesResult, devisResult, equipementsResult] = await Promise.allSettled([
    api.get('/contacts'),
    api.get('/devis'),
    api.get('/equipements'),
  ])
  if (messagesResult.status === 'fulfilled' && Array.isArray(messagesResult.value.data)) {
    messagesCommerciaux.value = messagesResult.value.data
  }
  if (devisResult.status === 'fulfilled' && Array.isArray(devisResult.value.data)) {
    devisCommerciaux.value = devisResult.value.data
  }
  if (equipementsResult.status === 'fulfilled' && Array.isArray(equipementsResult.value.data)) {
    equipementsCommerciaux.value = equipementsResult.value.data
  }
  demandesCommerciales.value = [
    ...messagesCommerciaux.value.map((item) => ({ ...item, type: 'message' })),
    ...devisCommerciaux.value.map((item) => ({ ...item, type: 'devis' })),
  ].sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
  chargementCommercial.value = false
}

onMounted(() => {
  try {
    const utilisateur = JSON.parse(localStorage.getItem('admin_user') || 'null')
    role.value = utilisateur?.role === 'editeur' ? 'gestionnaire_contenu' : utilisateur?.role || 'admin'
  } catch {
    role.value = 'admin'
  }

  if (role.value === 'gestionnaire_contenu') {
    chargerDashboardContenu()
  } else if (role.value === 'gestionnaire_commercial') {
    chargerDashboardCommercial()
  } else {
    chargerStatistiques()
    chargerDernieresActualites()
    chargerDerniersContacts()
  }
})
</script>
