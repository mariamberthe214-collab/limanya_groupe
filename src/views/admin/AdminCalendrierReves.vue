
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const annee = ref(2026)
const questions = ref([])
const chargement = ref(false)
const enregistrement = ref(false)
const afficherModal = ref(false)
const message = ref('')
const erreur = ref('')
const questionEnModification = ref(null)
const questionASupprimer = ref(null)
const suppressionEnCours = ref(false)

function ouvrirConfirmationSuppression(question) {
    questionASupprimer.value = question
}

function fermerConfirmationSuppression() {
    if (suppressionEnCours.value) return

    questionASupprimer.value = null
}
async function supprimerQuestion() {
    if (!questionASupprimer.value) return

    suppressionEnCours.value = true
    erreur.value = ''
    message.value = ''

    try {
        await api.delete(
            `calendrier-reves/${questionASupprimer.value.id}`
        )

        message.value = 'Question supprimée avec succès.'

        questionASupprimer.value = null

        await chargerQuestions()

    } catch (error) {
        erreur.value =
            error.response?.data?.message ||
            'Une erreur est survenue lors de la suppression.'

    } finally {
        suppressionEnCours.value = false
    }
}

const formulaireVide = () => ({
  jour: '',
  date_jeu: '',
  cadeau: '',
  question: '',
  choix_a: '',
  choix_b: '',
  choix_c: '',
  bonne_reponse: 'A',
  est_grand_lot: false,
  statut: 'brouillon',
})

const formulaire = ref(formulaireVide())

const rubriques = [
  { titre: 'Gestion des jours', description: 'Préparer et programmer les 31 journées.', icone: 'bi-calendar-event' },
  { titre: 'Questions et cadeaux', description: 'Définir les questions, réponses et cadeaux.', icone: 'bi-gift' },
  { titre: 'Participants', description: 'Consulter les personnes ayant participé.', icone: 'bi-people' },
  { titre: 'Gagnants', description: 'Enregistrer et consulter les gagnants.', icone: 'bi-trophy' },
  { titre: 'Exports', description: 'Exporter les participations pour les tirages.', icone: 'bi-file-earmark-excel' },
]

const chargerQuestions = async () => {
  chargement.value = true
  erreur.value = ''

  try {
    const response = await api.get('calendrier-reves/admin')
    questions.value = Array.isArray(response.data) ? response.data : []
  } catch (error) {
    erreur.value = error.response?.data?.message || 'Impossible de charger les questions.'
  } finally {
    chargement.value = false
  }
}

const ouvrirModal = () => {
  formulaire.value = formulaireVide()
  message.value = ''
  erreur.value = ''
  afficherModal.value = true
}

const ouvrirModification = (item) => {
  questionEnModification.value = item.id

  formulaire.value = {
    jour: item.jour,
    date_jeu: item.date_jeu,
    cadeau: item.cadeau,
    question: item.question,
    choix_a: item.choix_a,
    choix_b: item.choix_b,
    choix_c: item.choix_c,
    bonne_reponse: item.bonne_reponse,
    est_grand_lot: Number(item.est_grand_lot) === 1,
    statut: item.statut,
  }

  message.value = ''
  erreur.value = ''
  afficherModal.value = true
}

const fermerModal = () => {
  afficherModal.value = false
}

const enregistrerQuestion = async () => {
  message.value = ''
  erreur.value = ''

  if (!formulaire.value.jour || !formulaire.value.date_jeu) {
    erreur.value = 'Veuillez renseigner le jour et la date.'
    return
  }

  enregistrement.value = true

  try {
    const donnees = {
      ...formulaire.value,
      jour: Number(formulaire.value.jour),
      est_grand_lot: formulaire.value.est_grand_lot,
    }

    let response

    if (questionEnModification.value) {
      response = await api.put(
        `calendrier-reves/${questionEnModification.value}`,
        donnees
      )
    } else {
      response = await api.post('calendrier-reves', donnees)
    }

    message.value = response.data.message
    afficherModal.value = false
    formulaire.value = formulaireVide()
    questionEnModification.value = null

    await chargerQuestions()
  } catch (error) {
    erreur.value = error.response?.data?.message ||
      'Une erreur est survenue.'
  } finally {
    enregistrement.value = false
  }
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(`${date}T00:00:00`).toLocaleDateString('fr-FR')
}

onMounted(chargerQuestions)
</script>

<template>
  <div class="container-fluid p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
      <div>
        <p class="text-uppercase text-muted small mb-1">Administration / Promotion</p>
        <h2 class="fw-bold mb-1">Calendrier des Rêves</h2>
        <p class="text-muted mb-0">Gérez la grande promotion de décembre de LIMANYA GROUPE.</p>
      </div>

      <span class="badge bg-warning text-dark fs-6 px-3 py-2">
        Décembre {{ annee }}
      </span>
    </div>

    <div class="p-4 rounded-4 mb-4 text-white"
      style="background: linear-gradient(120deg, #262016, #80602b);">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <h4 class="fw-bold">Le Calendrier des Rêves Limanya</h4>
          <p class="mb-0 opacity-75">
            Un jour, une question, un cadeau : préparez et suivez
            votre campagne promotionnelle du 1er au 31 décembre.
          </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
          <i class="bi bi-calendar-heart" style="font-size: 4rem;"></i>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-6 col-xl-4" v-for="rubrique in rubriques" :key="rubrique.titre">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4">
            <div class="rounded-3 p-3 bg-light d-inline-block mb-3">
              <i :class="['bi', rubrique.icone, 'fs-4']"></i>
            </div>
            <h5 class="fw-bold">{{ rubrique.titre }}</h5>
            <p class="text-muted small mb-0">{{ rubrique.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
          <div>
            <h4 class="fw-bold mb-1">Questions du calendrier</h4>
            <p class="text-muted small mb-0">Préparez les 31 journées de la promotion.</p>
          </div>

          <button class="btn btn-warning fw-semibold px-4" @click="ouvrirModal">
            <i class="bi bi-plus-lg me-2"></i>Ajouter une question
          </button>
        </div>

        <div v-if="message" class="alert alert-success">{{ message }}</div>
        <div v-if="erreur" class="alert alert-danger">{{ erreur }}</div>

        <div v-if="chargement" class="text-center py-5">
          <div class="spinner-border text-warning"></div>
          <p class="text-muted mt-2">Chargement des questions...</p>
        </div>

        <div v-else-if="questions.length === 0" class="text-center py-5">
          <i class="bi bi-calendar2-plus text-muted" style="font-size: 3rem;"></i>
          <h5 class="mt-3">Aucune question enregistrée</h5>
          <p class="text-muted">Commencez par préparer la première journée du calendrier.</p>
        </div>

        <div v-else class="table-responsive">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Jour</th>
                <th>Date</th>
                <th>Cadeau</th>
                <th>Question</th>
                <th>Statut</th>
                <th>Type</th>
                <th>Actions</th>
              </tr>
            </thead>
           <tbody>
              <tr v-for="item in questions" :key="item.id">
                <td class="fw-bold">Jour {{ item.jour }}</td>

                <td>{{ formatDate(item.date_jeu) }}</td>

                <td>{{ item.cadeau }}</td>

                <td>{{ item.question }}</td>

                <td>
                  <span :class="item.statut === 'publie'
                    ? 'badge bg-success'
                    : 'badge bg-secondary'">
                    {{ item.statut }}
                  </span>
                </td>

                <td>
                  <span v-if="Number(item.est_grand_lot)"
                    class="badge bg-warning text-dark">
                    Grand lot
                  </span>
                  <span v-else class="text-muted small">
                    Cadeau classique
                  </span>
                </td>

                <td>
                  <button
                    class="btn btn-sm btn-outline-warning"
                    @click="ouvrirModification(item)">
                    <i class="bi bi-pencil-square me-1"></i>
                    Modifier
                  </button>

                  <button
                    type="button"
                    class="btn-supprimer"
                    @click="ouvrirConfirmationSuppression(item)"
                    >
                    <i class="fas fa-trash"></i>
                    Supprimer
                </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Fenêtre modale -->
    <div v-if="afficherModal" class="modal-backdrop-custom">
      <div class="modal-dialog modal-dialog-centered modal-lg modal-custom">
        <div class="modal-content border-0 rounded-4 shadow">
          <div class="modal-header border-0 p-4">
            <div>
              <h4 class="fw-bold mb-1">
                {{ questionEnModification ? 'Modifier la question' : 'Nouvelle question' }}
              </h4>
              <p class="text-muted small mb-0">Préparez une journée du Calendrier des Rêves.</p>
            </div>
            <button class="btn-close" @click="fermerModal"></button>
          </div>

          <form @submit.prevent="enregistrerQuestion">
            <div class="modal-body px-4">
              <div v-if="erreur" class="alert alert-danger">{{ erreur }}</div>

              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label fw-semibold">Numéro du jour</label>
                  <input v-model="formulaire.jour" type="number" min="1" max="31"
                    class="form-control" required>
                </div>

                <div class="col-md-8">
                  <label class="form-label fw-semibold">Date du jeu</label>
                  <input v-model="formulaire.date_jeu" type="date"
                    class="form-control" required>
                </div>

                <div class="col-12">
                  <label class="form-label fw-semibold">Cadeau du jour</label>
                  <input v-model="formulaire.cadeau" type="text"
                    class="form-control" placeholder="Ex : Un bon de réduction" required>
                </div>

                <div class="col-12">
                  <label class="form-label fw-semibold">Question</label>
                  <textarea v-model="formulaire.question" class="form-control"
                    rows="3" placeholder="Saisissez votre question..." required></textarea>
                </div>

                <div class="col-md-4">
                  <label class="form-label fw-semibold">Choix A</label>
                  <input v-model="formulaire.choix_a" class="form-control" required>
                </div>

                <div class="col-md-4">
                  <label class="form-label fw-semibold">Choix B</label>
                  <input v-model="formulaire.choix_b" class="form-control" required>
                </div>

                <div class="col-md-4">
                  <label class="form-label fw-semibold">Choix C</label>
                  <input v-model="formulaire.choix_c" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold">Bonne réponse</label>
                  <select v-model="formulaire.bonne_reponse" class="form-select" required>
                    <option value="A">Réponse A</option>
                    <option value="B">Réponse B</option>
                    <option value="C">Réponse C</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold">Statut</label>
                  <select v-model="formulaire.statut" class="form-select" required>
                    <option value="brouillon">Brouillon</option>
                    <option value="publie">Publié</option>
                  </select>
                </div>

                <div class="col-12">
                  <div class="form-check">
                    <input v-model="formulaire.est_grand_lot" class="form-check-input"
                      type="checkbox" id="grandLot">
                    <label class="form-check-label fw-semibold" for="grandLot">
                      Cette journée correspond à un grand lot
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4">
              <button type="button" class="btn btn-light" @click="fermerModal">
                Annuler
              </button>
              <button type="submit" class="btn btn-warning fw-semibold px-4"
                :disabled="enregistrement">
                <span v-if="enregistrement" class="spinner-border spinner-border-sm me-2"></span>
                  {{ enregistrement ? 'Enregistrement...' :
                    questionEnModification
                      ? 'Enregistrer les modifications'
                      : 'Enregistrer la question' }}              
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


<div
    v-if="questionASupprimer"
    class="modal-backdrop-custom"
    @click.self="fermerConfirmationSuppression"
>
    <div class="modal-content-custom confirmation-modal">
        <div class="confirmation-icon">
            <i class="fas fa-trash-alt"></i>
        </div>

        <h3>Supprimer cette question ?</h3>

        <p>
            Voulez-vous vraiment supprimer la question du jour
            {{ questionASupprimer.jour }} ?
        </p>

        <p class="text-muted">
            Cette action est irréversible.
        </p>

        <div class="confirmation-actions">
            <button
                type="button"
                class="btn-annuler"
                @click="fermerConfirmationSuppression"
                :disabled="suppressionEnCours"
            >
                Annuler
            </button>

            <button
                type="button"
                class="btn-confirmer-suppression"
                @click="supprimerQuestion"
                :disabled="suppressionEnCours"
            >
                {{ suppressionEnCours ? 'Suppression...' : 'Oui, supprimer' }}
            </button>
        </div>
    </div>
</div>

</template>

<style scoped>

.confirmation-modal {
    max-width: 420px;
    text-align: center;
    padding: 30px;
    border-radius: 16px;
}

.confirmation-icon {
    width: 65px;
    height: 65px;
    margin: 0 auto 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #fff1f0;
    color: #dc3545;
    font-size: 26px;
}

.confirmation-modal h3 {
    font-size: 21px;
    margin-bottom: 12px;
}

.confirmation-modal p {
    margin-bottom: 10px;
}

.confirmation-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 25px;
}

.btn-annuler,
.btn-confirmer-suppression {
    border: none;
    padding: 11px 18px;
    border-radius: 8px;
    cursor: pointer;
}

.btn-annuler {
    background: #f1f1f1;
    color: #333;
}

.btn-confirmer-suppression,
.btn-supprimer {
    background: #dc3545;
    color: white;
}

.btn-supprimer {
    border: none;
    padding: 8px 12px;
    border-radius: 7px;
    cursor: pointer;
}

.btn-annuler:disabled,
.btn-confirmer-suppression:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.modal-backdrop-custom {
  position: fixed;
  inset: 0;
  z-index: 1050;
  background: rgba(0, 0, 0, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 15px;
  overflow-y: auto;
}

.modal-custom {
  width: 100%;
  max-width: 800px;
  margin: auto;
}

.modal-content {
  max-height: 90vh;
  overflow-y: auto;
}

.form-control:focus,
.form-select:focus {
  border-color: #c69a36;
  box-shadow: 0 0 0 0.2rem rgba(198, 154, 54, 0.15);
}
</style>
