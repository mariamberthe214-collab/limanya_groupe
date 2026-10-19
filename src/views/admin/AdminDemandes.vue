<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'

const route = useRoute()
const router = useRouter()
const demandes = ref([])
const filtre = ref(['message', 'devis'].includes(route.query.type) ? route.query.type : 'tout')
const recherche = ref('')
const page = ref(1)
const parPage = 10
const demandeASupprimer = ref(null)
const afficherModal = ref(false)
const chargement = ref(true)
const erreur = ref('')

watch(() => route.query.type, (type) => {
  filtre.value = ['message', 'devis'].includes(type) ? type : 'tout'
  page.value = 1
})

watch([recherche, filtre], () => {
  page.value = 1
})

const chargerDemandes = async () => {
  chargement.value = true
  erreur.value = ''

  const [messagesResult, devisResult] = await Promise.allSettled([
    api.get('/contacts'),
    api.get('/devis'),
  ])

  const messages = messagesResult.status === 'fulfilled' && Array.isArray(messagesResult.value.data)
    ? messagesResult.value.data.map((item) => ({ ...item, type: 'message' }))
    : []
  const devis = devisResult.status === 'fulfilled' && Array.isArray(devisResult.value.data)
    ? devisResult.value.data.map((item) => ({ ...item, type: 'devis' }))
    : []

  demandes.value = [...messages, ...devis].sort((a, b) => new Date(b.created_at) - new Date(a.created_at))

  if (messagesResult.status === 'rejected' || devisResult.status === 'rejected') {
    erreur.value = demandes.value.length
      ? 'Certaines demandes n’ont pas pu être chargées.'
      : 'Impossible de charger les demandes clients.'
  }

  chargement.value = false
}

const demandesFiltrees = computed(() => {
  const terme = recherche.value.trim().toLocaleLowerCase('fr')
  return demandes.value.filter((item) => {
    if (filtre.value !== 'tout' && item.type !== filtre.value) return false
    if (!terme) return true

    return [item.nom, item.prenom, item.email, item.telephone, item.sujet, item.service, item.message]
      .some((valeur) => String(valeur || '').toLocaleLowerCase('fr').includes(terme))
  })
})

const totalPages = computed(() => Math.ceil(demandesFiltrees.value.length / parPage))
const demandesPaginees = computed(() => {
  const debut = (page.value - 1) * parPage
  return demandesFiltrees.value.slice(debut, debut + parPage)
})
const nombreMessages = computed(() => demandes.value.filter((item) => item.type === 'message').length)
const nombreDevis = computed(() => demandes.value.filter((item) => item.type === 'devis').length)

const changerFiltre = (valeur) => {
  filtre.value = valeur
  router.replace({
    query: {
      ...route.query,
      type: valeur === 'tout' ? undefined : valeur,
    },
  })
}

const cheminDetail = (item) => item.type === 'message'
  ? `/admin/contacts/${item.id}`
  : `/admin/devis/${item.id}`

const ouvrirConfirmation = (item) => {
  demandeASupprimer.value = item
  afficherModal.value = true
}

const supprimerDemande = async () => {
  const demande = demandeASupprimer.value
  if (!demande) return

  try {
    await api.delete(`/${demande.type === 'message' ? 'contacts' : 'devis'}/${demande.id}`)
    afficherModal.value = false
    demandeASupprimer.value = null
    await chargerDemandes()
  } catch (error) {
    console.error(error)
    erreur.value = error.response?.data?.message || 'Impossible de supprimer cette demande.'
    afficherModal.value = false
  }
}

const changerStatutDevis = async (item) => {
  const statutEnAttente = ['nouveau', 'En attente', 'en attente'].includes(item.statut)
  const statut = statutEnAttente ? 'traité' : 'nouveau'

  try {
    await api.put(`/devis/${item.id}`, {
      nom: item.nom,
      email: item.email,
      telephone: item.telephone,
      service: item.service,
      message: item.message,
      statut,
    })
    item.statut = statutEnAttente ? 'Traité' : 'En attente'
  } catch (error) {
    console.error(error)
    erreur.value = error.response?.data?.message || 'Impossible de modifier le statut du devis.'
  }
}

const dateLisible = (date) => {
  if (!date) return '—'
  const valeur = new Date(date)
  return Number.isNaN(valeur.getTime()) ? date : valeur.toLocaleDateString('fr-FR')
}

onMounted(chargerDemandes)
</script>

<template>
  <div class="container py-4 admin-requests-page">
    <div class="requests-heading mb-1">
      <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-inboxes text-amber me-2"></i>Demandes clients</h2>
        <p class="text-muted mb-0">Messages et demandes de devis réunis dans une seule boîte.</p>
      </div>
      <button class="btn btn-outline-secondary" type="button" title="Actualiser" aria-label="Actualiser" @click="chargerDemandes">
        <i class="bi bi-arrow-clockwise"></i>
      </button>
    </div>

    <div v-if="erreur" class="alert alert-warning mt-3" role="alert">{{ erreur }}</div>

    <div class="request-toolbar mt-4 mb-3">
      <div class="btn-group" role="group" aria-label="Filtrer les demandes">
        <button type="button" class="btn" :class="filtre === 'tout' ? 'btn-primary' : 'btn-outline-primary'" @click="changerFiltre('tout')">
          Toutes <span class="ms-1">{{ demandes.length }}</span>
        </button>
        <button type="button" class="btn" :class="filtre === 'message' ? 'btn-primary' : 'btn-outline-primary'" @click="changerFiltre('message')">
          Messages <span class="ms-1">{{ nombreMessages }}</span>
        </button>
        <button type="button" class="btn" :class="filtre === 'devis' ? 'btn-primary' : 'btn-outline-primary'" @click="changerFiltre('devis')">
          Devis <span class="ms-1">{{ nombreDevis }}</span>
        </button>
      </div>

      <div class="request-search">
        <i class="bi bi-search" aria-hidden="true"></i>
        <input v-model="recherche" class="form-control" placeholder="Rechercher une demande..." aria-label="Rechercher une demande" />
      </div>
    </div>

    <div v-if="chargement" class="text-center py-5 text-muted">
      <span class="spinner-border spinner-border-sm me-2"></span>Chargement des demandes...
    </div>

    <div v-else-if="!demandesFiltrees.length" class="empty-requests py-5 text-center">
      <i class="bi bi-inbox fs-2 d-block mb-2"></i>
      <p class="mb-0">Aucune demande ne correspond à votre recherche.</p>
    </div>

    <div v-else class="table-responsive">
      <table class="table table-hover align-middle requests-table">
        <thead class="table-dark">
          <tr>
            <th>Type</th>
            <th>Client</th>
            <th>Sujet / Service</th>
            <th>Contact</th>
            <th>Date</th>
            <th>État</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in demandesPaginees" :key="`${item.type}-${item.id}`">
            <td>
              <span class="badge" :class="item.type === 'devis' ? 'bg-primary' : 'bg-secondary'">
                {{ item.type === 'devis' ? 'Devis' : 'Message' }}
              </span>
            </td>
            <td>
              <strong>{{ item.nom || 'Client' }}</strong>
              <small v-if="item.email" class="d-block text-muted">{{ item.email }}</small>
            </td>
            <td>{{ item.type === 'devis' ? item.service || 'Devis général' : item.sujet || 'Message général' }}</td>
            <td>{{ item.telephone || '—' }}</td>
            <td>{{ dateLisible(item.created_at) }}</td>
            <td>
              <span v-if="item.type === 'message'" class="badge" :class="item.lu ? 'bg-success' : 'bg-warning text-dark'">
                {{ item.lu ? 'Lu' : 'Non lu' }}
              </span>
              <span v-else class="badge" :class="['nouveau', 'En attente', 'en attente'].includes(item.statut) ? 'bg-warning text-dark' : 'bg-success'">
                {{ item.statut || 'En attente' }}
              </span>
            </td>
            <td>
              <div class="request-actions">
                <RouterLink :to="cheminDetail(item)" class="btn btn-outline-primary btn-sm" :title="item.type === 'devis' ? 'Voir le devis' : 'Voir le message'" :aria-label="item.type === 'devis' ? 'Voir le devis' : 'Voir le message'">
                  <i class="bi bi-eye"></i>
                </RouterLink>
                <button v-if="item.type === 'devis'" type="button" class="btn btn-outline-success btn-sm" title="Changer le statut" aria-label="Changer le statut" @click="changerStatutDevis(item)">
                  <i class="bi bi-arrow-repeat"></i>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" title="Supprimer" aria-label="Supprimer" @click="ouvrirConfirmation(item)">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="totalPages > 1" class="d-flex justify-content-center align-items-center gap-3 mt-4">
      <button class="btn btn-outline-primary btn-sm" :disabled="page === 1" @click="page--">Précédent</button>
      <span>Page {{ page }} / {{ totalPages }}</span>
      <button class="btn btn-outline-primary btn-sm" :disabled="page >= totalPages" @click="page++">Suivant</button>
    </div>
  </div>

  <ConfirmModal
    :show="afficherModal"
    title="Supprimer la demande"
    :message="demandeASupprimer?.type === 'devis' ? 'Voulez-vous vraiment supprimer ce devis ?' : 'Voulez-vous vraiment supprimer ce message ?'"
    @confirm="supprimerDemande"
    @cancel="afficherModal = false"
  />
</template>

<style scoped>
.requests-heading,
.request-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
}

.request-toolbar {
  flex-wrap: wrap;
}

.request-search {
  position: relative;
  width: min(100%, 360px);
}

.request-search i {
  position: absolute;
  left: 0.85rem;
  top: 50%;
  transform: translateY(-50%);
  color: #867d6d;
}

.request-search input {
  padding-left: 2.4rem;
}

.requests-table {
  min-width: 860px;
}

.request-actions {
  display: flex;
  gap: 0.4rem;
}

.empty-requests {
  color: #746d61;
  border-block: 1px solid #e8e3da;
}

@media (max-width: 767.98px) {
  .requests-heading {
    align-items: flex-start;
  }

  .requests-heading h2 {
    font-size: 1.4rem;
  }

  .request-toolbar {
    align-items: stretch;
  }

  .request-toolbar .btn-group {
    width: 100%;
  }

  .request-toolbar .btn-group .btn {
    padding-inline: 0.5rem;
  }

  .request-search {
    width: 100%;
  }
}
</style>
