<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../../services/api'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'

const utilisateurs = ref([])
const recherche = ref('')
const page = ref(1)
const parPage = 10
const showModal = ref(false)
const utilisateurASupprimer = ref(null)
const nouveauUtilisateur = ref({ nom: '', email: '', password: '', role: 'admin' })
const showForm = ref(false)
const idEnEdition = ref(null)

const chargerUtilisateurs = async () => {
  try {
    const response = await api.get('/users')
    utilisateurs.value = response.data
  } catch (error) {
    console.error(error)
  }
}

const utilisateursFiltres = computed(() => {
  const texte = recherche.value.toLowerCase()
  return utilisateurs.value.filter((item) => {
    return (
      (item.nom ?? '').toLowerCase().includes(texte) ||
      (item.email ?? '').toLowerCase().includes(texte) ||
      (item.role ?? '').toLowerCase().includes(texte)
    )
  })
})

const totalPages = computed(() => Math.ceil(utilisateursFiltres.value.length / parPage))

const utilisateursPagine = computed(() => {
  const debut = (page.value - 1) * parPage
  return utilisateursFiltres.value.slice(debut, debut + parPage)
})

const ouvrirConfirmation = (id) => {
  utilisateurASupprimer.value = id
  showModal.value = true
}

const supprimer = async () => {
  try {
    await api.delete(`/users/${utilisateurASupprimer.value}`)
    showModal.value = false
    chargerUtilisateurs()
  } catch (error) {
    console.error(error)
  }
}

const ouvrirAjout = () => {
  idEnEdition.value = null
  nouveauUtilisateur.value = { nom: '', email: '', password: '', role: 'admin' }
  showForm.value = true
}

const ouvrirEdition = (item) => {
  idEnEdition.value = item.id
  nouveauUtilisateur.value = {
    nom: item.nom || '',
    email: item.email || '',
    password: '',
    role: item.role || 'admin',
  }
  showForm.value = true
}

const annulerForm = () => {
  showForm.value = false
  idEnEdition.value = null
  nouveauUtilisateur.value = { nom: '', email: '', password: '', role: 'admin' }
}

const enregistrerUtilisateur = async () => {
  try {
    if (idEnEdition.value) {
      // En modification : n'envoie le mot de passe que s'il a été renseigné
      const data = { ...nouveauUtilisateur.value }
      if (!data.password) delete data.password
      await api.put(`/users/${idEnEdition.value}`, data)
    } else {
      await api.post('/users', nouveauUtilisateur.value)
    }
    annulerForm()
    chargerUtilisateurs()
  } catch (error) {
    console.error(error)
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR')
}

onMounted(chargerUtilisateurs)
</script>

<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-1">
      <h2 class="fw-bold mb-0"><i class="bi bi-people text-amber me-2"></i>Gestion des utilisateurs</h2>
      <button class="btn btn-primary" @click="ouvrirAjout"><i class="bi bi-person-plus me-1"></i>Ajouter un utilisateur</button>
    </div>
    <p class="text-muted mb-4">Gérez les comptes ayant accès à l'administration.</p>

    <div v-if="showForm" class="card p-3 mb-4">
      <h5 class="mb-3">{{ idEnEdition ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' }}</h5>
      <div class="row g-3">
        <div class="col-md-4">
          <input v-model="nouveauUtilisateur.nom" class="form-control" placeholder="Nom" />
        </div>
        <div class="col-md-4">
          <input v-model="nouveauUtilisateur.email" class="form-control" placeholder="Email" />
        </div>
        <div class="col-md-2">
          <input
            v-model="nouveauUtilisateur.password"
            type="password"
            class="form-control"
            :placeholder="idEnEdition ? 'Laisser vide pour ne pas changer' : 'Mot de passe'" />
        </div>
        <div class="col-md-2">
          <select v-model="nouveauUtilisateur.role" class="form-select">
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
        </div>
      </div>
      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success" @click="enregistrerUtilisateur">Enregistrer</button>
        <button class="btn btn-outline-secondary" @click="annulerForm">Annuler</button>
      </div>
    </div>

    <div class="mb-3 position-relative" style="max-width: 420px;">
      <i class="bi bi-search position-absolute" style="left: 16px; top: 50%; transform: translateY(-50%); color: #b3ab99;"></i>
      <input
        v-model="recherche"
        class="form-control ps-5"
        placeholder="Rechercher un utilisateur..."
      />
    </div>

    <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in utilisateursPagine" :key="item.id">
          <td>{{ item.id }}</td>
          <td>{{ item.nom }}</td>
          <td>{{ item.email }}</td>
          <td>
            <span class="badge bg-primary">{{ item.role || 'admin' }}</span>
          </td>
          <td>{{ formatDate(item.created_at) }}</td>
          <td>
            <button class="btn btn-outline-primary btn-sm me-2" @click="ouvrirEdition(item)"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-danger btn-sm" @click="ouvrirConfirmation(item.id)"><i class="bi bi-trash"></i></button>
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
    title="Supprimer un utilisateur"
    message="Voulez-vous vraiment supprimer cet utilisateur ?"
    @confirm="supprimer"
    @cancel="showModal = false"
  />
</template>