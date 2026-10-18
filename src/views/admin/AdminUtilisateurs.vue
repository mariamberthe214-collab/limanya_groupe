<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import api from '../../services/api'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'
import { uploadImage, getImageUrl } from '../../utils/images'

const utilisateurs = ref([])
const recherche = ref('')
const page = ref(1)
const parPage = 10
const showModal = ref(false)
const utilisateurASupprimer = ref(null)
const nouveauUtilisateur = ref({ nom: '', prenom: '', email: '', telephone: '', photo: '', password: '', role: '' })
const showForm = ref(false)
const idEnEdition = ref(null)
const erreur = ref('')
const succes = ref('')
const envoiEnCours = ref(false)
const photoFichier = ref(null)
const photoApercu = ref('')

watch(recherche, () => {
  page.value = 1
})

const chargerUtilisateurs = async () => {
  try {
    const response = await api.get('/users')
    utilisateurs.value = response.data
  } catch (error) {
    console.error(error)
    erreur.value = error.response?.data?.message || 'Impossible de charger les utilisateurs.'
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
  nouveauUtilisateur.value = { nom: '', prenom: '', email: '', telephone: '', photo: '', password: '', role: '' }
  photoFichier.value = null
  photoApercu.value = ''
  erreur.value = ''
  succes.value = ''
  showForm.value = true
}

const ouvrirEdition = (item) => {
  idEnEdition.value = item.id
  nouveauUtilisateur.value = {
    nom: item.nom || '',
    prenom: item.prenom || '',
    email: item.email || '',
    telephone: item.telephone || '',
    photo: item.photo || '',
    password: '',
    role: item.role === 'editeur' ? 'gestionnaire_contenu' : item.role || 'admin',
  }
  photoFichier.value = null
  photoApercu.value = item.photo ? getImageUrl(item.photo) : ''
  erreur.value = ''
  succes.value = ''
  showForm.value = true
}

const annulerForm = () => {
  if (photoApercu.value.startsWith('blob:')) URL.revokeObjectURL(photoApercu.value)
  showForm.value = false
  idEnEdition.value = null
  nouveauUtilisateur.value = { nom: '', prenom: '', email: '', telephone: '', photo: '', password: '', role: '' }
  photoFichier.value = null
  photoApercu.value = ''
}

const choisirPhoto = (event) => {
  const fichier = event.target.files?.[0] || null
  photoFichier.value = fichier
  if (photoApercu.value.startsWith('blob:')) URL.revokeObjectURL(photoApercu.value)
  photoApercu.value = fichier ? URL.createObjectURL(fichier) : (nouveauUtilisateur.value.photo ? getImageUrl(nouveauUtilisateur.value.photo) : '')
}

const enregistrerUtilisateur = async () => {
  const edition = Boolean(idEnEdition.value)
  erreur.value = ''
  succes.value = ''
  envoiEnCours.value = true
  try {
    const data = { ...nouveauUtilisateur.value }
    if (photoFichier.value) data.photo = await uploadImage(photoFichier.value)

    if (idEnEdition.value) {
      if (!data.password) delete data.password
      await api.put(`/users/${idEnEdition.value}`, data)
    } else {
      await api.post('/users', data)
    }
    annulerForm()
    succes.value = edition ? 'Utilisateur modifié.' : 'Utilisateur créé.'
    await chargerUtilisateurs()
  } catch (error) {
    console.error(error)
    erreur.value = error.response?.data?.message || "Impossible d'enregistrer cet utilisateur. Vérifiez les informations et réessayez."
  } finally {
    envoiEnCours.value = false
  }
}

const roleLibelle = (role) => ({
  admin: 'Admin',
  gestionnaire_contenu: 'Gestionnaire contenu',
  gestionnaire_commercial: 'Gestionnaire commercial',
  editeur: 'Gestionnaire contenu',
}[role] || role)

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR')
}

onMounted(chargerUtilisateurs)
</script>

<template>
  <div class="container py-4 admin-users-page">
    <div class="admin-page-header mb-1">
      <h2 class="fw-bold mb-0"><i class="bi bi-people text-amber me-2"></i>Gestion des utilisateurs</h2>
      <button class="btn btn-primary" @click="ouvrirAjout"><i class="bi bi-person-plus me-1"></i>Ajouter un utilisateur</button>
    </div>
    <p class="text-muted mb-4">Attribuez à chaque compte les espaces qu’il est autorisé à gérer.</p>
    <div v-if="erreur" class="alert alert-danger" role="alert">
      <i class="bi bi-exclamation-triangle me-2"></i>{{ erreur }}
    </div>
    <div v-if="succes" class="alert alert-success" role="status">{{ succes }}</div>

    <div v-if="showForm" class="card p-3 mb-4">
      <h5 class="mb-3">{{ idEnEdition ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' }}</h5>
      <form @submit.prevent="enregistrerUtilisateur">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label" for="utilisateur-prenom">Prénom</label>
          <input id="utilisateur-prenom" v-model="nouveauUtilisateur.prenom" class="form-control" autocomplete="given-name" />
        </div>
        <div class="col-md-6">
          <label class="form-label" for="utilisateur-nom">Nom <span class="text-danger">*</span></label>
          <input id="utilisateur-nom" v-model="nouveauUtilisateur.nom" class="form-control" autocomplete="family-name" required />
        </div>
        <div class="col-md-6">
          <label class="form-label" for="utilisateur-email">Adresse e-mail <span class="text-danger">*</span></label>
          <input id="utilisateur-email" v-model="nouveauUtilisateur.email" type="email" class="form-control" autocomplete="email" required />
        </div>
        <div class="col-md-6">
          <label class="form-label" for="utilisateur-telephone">Téléphone</label>
          <input id="utilisateur-telephone" v-model="nouveauUtilisateur.telephone" type="tel" class="form-control" autocomplete="tel" />
        </div>
        <div class="col-md-6">
          <label class="form-label" for="utilisateur-password">{{ idEnEdition ? 'Nouveau mot de passe' : 'Mot de passe *' }}</label>
          <input
            id="utilisateur-password"
            v-model="nouveauUtilisateur.password"
            type="password"
            class="form-control"
            :required="!idEnEdition"
            :minlength="nouveauUtilisateur.password ? 8 : undefined"
            autocomplete="new-password"
            :placeholder="idEnEdition ? 'Laisser vide pour conserver l’actuel' : '8 caractères minimum'" />
        </div>
        <div class="col-md-6">
          <label class="form-label" for="utilisateur-role">Rôle <span class="text-danger">*</span></label>
          <select id="utilisateur-role" v-model="nouveauUtilisateur.role" class="form-select" required>
            <option disabled value="">Choisir un rôle</option>
            <option value="admin">Admin · accès complet</option>
            <option value="gestionnaire_contenu">Gestionnaire contenu · actualités et réalisations</option>
            <option value="gestionnaire_commercial">Gestionnaire commercial · messages et devis</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label" for="utilisateur-photo">Photo de profil</label>
          <input id="utilisateur-photo" type="file" accept="image/*" class="form-control" @change="choisirPhoto" />
          <img v-if="photoApercu" :src="photoApercu" alt="Aperçu de la photo de profil" class="user-photo-preview mt-2" />
        </div>
        <div class="col-12">
          <button class="btn btn-success" type="submit" :disabled="envoiEnCours">
            <span v-if="envoiEnCours" class="spinner-border spinner-border-sm me-2"></span>
            {{ envoiEnCours ? 'Enregistrement...' : 'Enregistrer' }}
          </button>
          <button class="btn btn-outline-secondary ms-2" type="button" @click="annulerForm">Annuler</button>
        </div>
      </div>
      </form>
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
          <th>Photo</th>
          <th>Nom complet</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Rôle</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in utilisateursPagine" :key="item.id">
          <td>{{ item.id }}</td>
          <td>
            <img v-if="item.photo" :src="getImageUrl(item.photo)" alt="" class="user-photo-thumb" />
            <i v-else class="bi bi-person-circle fs-4 text-muted"></i>
          </td>
          <td>{{ [item.prenom, item.nom].filter(Boolean).join(' ') }}</td>
          <td>{{ item.email }}</td>
          <td>{{ item.telephone || '—' }}</td>
          <td><span class="badge bg-primary">{{ roleLibelle(item.role || 'admin') }}</span></td>
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

<style scoped>
.admin-page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.admin-users-page .table {
  min-width: 900px;
}

.user-photo-thumb,
.user-photo-preview {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  object-fit: cover;
}

.user-photo-preview {
  width: 72px;
  height: 72px;
}

@media (max-width: 767.98px) {
  .admin-page-header {
    align-items: flex-start;
    gap: 0.75rem;
  }

  .admin-users-page h2 {
    font-size: 1.45rem;
    line-height: 1.2;
  }

  .admin-users-page .admin-page-header .btn {
    flex-shrink: 0;
    font-size: 0.8rem;
    padding: 0.55rem 0.75rem;
  }

  .admin-users-page .card .row > [class*='col-'] {
    width: 100%;
  }
}
</style>