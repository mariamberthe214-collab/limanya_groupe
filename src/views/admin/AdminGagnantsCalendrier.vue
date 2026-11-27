
<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../../services/api'

const gagnants = ref([])
const chargement = ref(false)
const erreur = ref('')
const message = ref('')
const recherche = ref('')
const filtreStatut = ref('')
const questions = ref([])
const jourSelectionne = ref('')
const tirageEnCours = ref(false)
const gagnantTire = ref(null)

const gagnantsFiltres = computed(() => {
  return gagnants.value.filter(gagnant => {
    const terme = recherche.value.toLowerCase().trim()

    const nomComplet = `${gagnant.nom || ''} ${gagnant.prenoms || ''}`.toLowerCase()
    const whatsapp = String(gagnant.whatsapp || '').toLowerCase()

    const correspondRecherche =
      !terme || nomComplet.includes(terme) || whatsapp.includes(terme)

    const correspondStatut =
      !filtreStatut.value || gagnant.statut === filtreStatut.value

    return correspondRecherche && correspondStatut
  })
})

const totalGagnants = computed(() => gagnants.value.length)

const totalEnAttente = computed(() =>
  gagnants.value.filter(g => g.statut === 'en_attente').length
)

const totalConfirmes = computed(() =>
  gagnants.value.filter(g => g.statut === 'confirme').length
)

const totalPublies = computed(() =>
  gagnants.value.filter(g => g.statut === 'publie').length
)

function libelleStatut(statut) {
  const libelles = {
    en_attente: 'En attente',
    confirme: 'Confirmé',
    publie: 'Publié',
    annule: 'Annulé'
  }

  return libelles[statut] || statut
}

function classeStatut(statut) {
  const classes = {
    en_attente: 'bg-warning text-dark',
    confirme: 'bg-info text-dark',
    publie: 'bg-success',
    annule: 'bg-danger'
  }

  return classes[statut] || 'bg-secondary'
}

function formatDate(date) {
  if (!date) return '—'

  return new Date(date.replace(' ', 'T')).toLocaleString('fr-FR')
}

async function chargerGagnants() {
  chargement.value = true
  erreur.value = ''

  try {
    const response = await api.get('calendrier-reves/gagnants')

    if (Array.isArray(response.data)) {
      gagnants.value = response.data.map(gagnant => ({
        ...gagnant,
        statutModifie: gagnant.statut,
        observationModifiee: gagnant.observation || '',
        enregistrement: false
      }))
    } else {
      gagnants.value = []
      erreur.value = "Les données reçues par l'API sont incorrectes."
    }
  } catch (error) {
    erreur.value =
      error.response?.data?.message ||
      'Impossible de charger les gagnants.'
  } finally {
    chargement.value = false
  }
}

async function chargerQuestions() {
  try {
    const response = await api.get('calendrier-reves/admin')
    questions.value = Array.isArray(response.data) ? response.data : []
  } catch (error) {
    erreur.value = 'Impossible de charger les journées du calendrier.'
  }
}

async function modifierStatut(gagnant) {
  message.value = ''
  erreur.value = ''

  gagnant.enregistrement = true

  try {
    const response = await api.put(
      `calendrier-reves/gagnants/${gagnant.id}`,
      {
        statut: gagnant.statutModifie,
        observation: gagnant.observationModifiee
      }
    )

    gagnant.statut = gagnant.statutModifie
    gagnant.observation = gagnant.observationModifiee

    message.value = response.data.message ||
      'Informations du gagnant mises à jour.'
  } catch (error) {
    erreur.value =
      error.response?.data?.message ||
      'Impossible de modifier le gagnant.'

    gagnant.statutModifie = gagnant.statut
    gagnant.observationModifiee = gagnant.observation || ''
  } finally {
    gagnant.enregistrement = false
  }
}

async function lancerTirage() {
  if (!jourSelectionne.value) return

  const confirmation = window.confirm(
    'Voulez-vous vraiment lancer le tirage au sort pour cette journée ? Cette opération ne pourra pas être répétée.'
  )

  if (!confirmation) return

  tirageEnCours.value = true
  erreur.value = ''
  message.value = ''
  gagnantTire.value = null

  try {
    const response = await api.post(
      'calendrier-reves/gagnants/tirage',
      {
        question_id: Number(jourSelectionne.value)
      }
    )

    gagnantTire.value = response.data.gagnant
    message.value = response.data.message || 'Tirage effectué avec succès.'

    await chargerGagnants()
  } catch (error) {
    erreur.value =
      error.response?.data?.message ||
      'Impossible d’effectuer le tirage au sort.'
  } finally {
    tirageEnCours.value = false
  }
}

onMounted(() => {
  chargerGagnants()
  chargerQuestions()
})
</script>

<template>
  <div class="container-fluid p-3 p-md-4">

    <div class="mb-4">
      <p class="text-uppercase text-muted small mb-1">
        Administration / Promotion
      </p>

      <h2 class="fw-bold mb-1">Gestion des gagnants</h2>

      <p class="text-muted mb-0">
        Vérifiez les gagnants du Calendrier des Rêves et gérez leur statut.
      </p>
    </div>

    <div
      class="p-4 rounded-4 mb-4 text-white"
      style="background: linear-gradient(120deg, #262016, #80602b);"
    >
      <div class="row align-items-center">
        <div class="col-lg-9">
          <h4 class="fw-bold">
            <i class="bi bi-trophy-fill me-2"></i>
            Les gagnants Limanya
          </h4>

          <p class="mb-0 opacity-75">
            Suivez les tirages, vérifiez les informations et confirmez
            les gagnants avant leur publication officielle.
          </p>
        </div>

        <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
          <i class="bi bi-award" style="font-size: 4rem;"></i>
        </div>
      </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-3 mb-4">

      <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-3">
            <span class="text-muted small">Total des gagnants</span>
            <h3 class="fw-bold text-success mt-2">{{ totalGagnants }}</h3>
            <i class="bi bi-trophy text-success"></i>
          </div>
        </div>
      </div>

      <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-3">
            <span class="text-muted small">En attente</span>
            <h3 class="fw-bold text-warning mt-2">{{ totalEnAttente }}</h3>
            <i class="bi bi-hourglass-split text-warning"></i>
          </div>
        </div>
      </div>

      <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-3">
            <span class="text-muted small">Confirmés</span>
            <h3 class="fw-bold text-info mt-2">{{ totalConfirmes }}</h3>
            <i class="bi bi-patch-check text-info"></i>
          </div>
        </div>
      </div>

      <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-3">
            <span class="text-muted small">Publiés</span>
            <h3 class="fw-bold text-success mt-2">{{ totalPublies }}</h3>
            <i class="bi bi-megaphone text-success"></i>
          </div>
        </div>
      </div>

    </div>

    <div
  v-if="message"
  class="alert alert-success alert-dismissible fade show"
  role="alert"
>
  {{ message }}
  <button
    type="button"
    class="btn-close"
    @click="message = ''"
  ></button>
</div>

<div
  v-if="erreur"
  class="alert alert-danger alert-dismissible fade show"
  role="alert"
>
  {{ erreur }}
  <button
    type="button"
    class="btn-close"
    @click="erreur = ''"
  ></button>
</div>

    <!-- Tirage au sort -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
  <div class="card-body p-3 p-md-4">
    <h4 class="fw-bold mb-2">
      <i class="bi bi-shuffle me-2 text-success"></i>
      Tirage au sort
    </h4>

    <p class="text-muted">
      Sélectionnez une journée pour effectuer le tirage parmi les participants ayant donné la bonne réponse.
    </p>

    <div class="row g-3 align-items-end">
      <div class="col-md-8">
        <label class="form-label fw-semibold">Journée du calendrier</label>
        <select v-model="jourSelectionne" class="form-select">
          <option value="">Choisir une journée</option>
          <option
            v-for="question in questions"
            :key="question.id"
            :value="question.id"
          >
            Jour {{ question.jour }} — {{ question.cadeau }}
          </option>
        </select>
      </div>

      <div class="col-md-4">
       <button
            type="button"
            class="btn btn-success w-100"
            @click="lancerTirage"
            :disabled="!jourSelectionne || tirageEnCours"
            >
            <span
                v-if="tirageEnCours"
                class="spinner-border spinner-border-sm me-2"
            ></span>

            <i v-else class="bi bi-shuffle me-2"></i>

            {{ tirageEnCours ? 'Tirage en cours...' : 'Lancer le tirage' }}
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Résultat du tirage -->
<div
  v-if="gagnantTire"
  class="card border-0 shadow-sm rounded-4 mb-4"
>
  <div class="card-body p-4">
    <h4 class="fw-bold text-success mb-3">
      <i class="bi bi-trophy-fill me-2"></i>
      Félicitations au gagnant !
    </h4>

    <h5 class="fw-bold">
      {{ gagnantTire.prenoms }} {{ gagnantTire.nom }}
    </h5>

    <p class="mb-1">
      <strong>WhatsApp :</strong> {{ gagnantTire.whatsapp }}
    </p>

    <p class="mb-1">
      <strong>Commune :</strong> {{ gagnantTire.commune }}
    </p>

    <p class="text-muted mb-0">
      <strong>Journée :</strong> {{ jourSelectionne }}
    </p>

    <div class="alert alert-warning mt-3 mb-0">
      Le gagnant est en attente de vérification avant toute publication.
    </div>
  </div>
</div>

    <!-- Liste des gagnants -->
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-3 p-md-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
          <div>
            <h4 class="fw-bold mb-1">Liste des gagnants</h4>
            <p class="text-muted small mb-0">
              Consultez et mettez à jour les informations des gagnants.
            </p>
          </div>

          <button
            class="btn btn-outline-success"
            type="button"
            @click="chargerGagnants"
            :disabled="chargement"
          >
            <i class="bi bi-arrow-clockwise me-2"></i>
            Actualiser
          </button>
        </div>

        <div v-if="message" class="alert alert-success">
          <i class="bi bi-check-circle me-2"></i>
          {{ message }}
        </div>

        <div v-if="erreur" class="alert alert-danger">
          {{ erreur }}
        </div>

        <!-- Recherche et filtre -->
        <div class="row g-3 mb-4">

          <div class="col-md-7">
            <input
              v-model="recherche"
              type="text"
              class="form-control"
              placeholder="Rechercher par nom ou numéro WhatsApp..."
            >
          </div>

          <div class="col-md-5">
            <select v-model="filtreStatut" class="form-select">
              <option value="">Tous les statuts</option>
              <option value="en_attente">En attente</option>
              <option value="confirme">Confirmé</option>
              <option value="publie">Publié</option>
              <option value="annule">Annulé</option>
            </select>
          </div>

        </div>

        <div v-if="chargement" class="text-center py-5">
          <div class="spinner-border text-success"></div>
          <p class="text-muted mt-2">Chargement des gagnants...</p>
        </div>

        <div v-else-if="gagnantsFiltres.length === 0" class="text-center py-5">
          <i class="bi bi-trophy text-muted" style="font-size: 3rem;"></i>

          <h5 class="mt-3">Aucun gagnant trouvé</h5>

          <p class="text-muted mb-0">
            Les gagnants enregistrés apparaîtront ici.
          </p>
        </div>

        <div v-else class="table-responsive">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Gagnant</th>
                <th>Coordonnées</th>
                <th>Journée</th>
                <th>Cadeau</th>
                <th>Date du tirage</th>
                <th>Statut</th>
                <th>Observation</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="gagnant in gagnantsFiltres" :key="gagnant.id">

                <td>
                  <strong>
                    {{ gagnant.nom }} {{ gagnant.prenoms }}
                  </strong>
                </td>

                <td>
                  <div>{{ gagnant.whatsapp }}</div>
                  <small class="text-muted">{{ gagnant.commune }}</small>
                </td>

                <td>
                  <span class="badge bg-light text-success">
                    Jour {{ gagnant.jour }}
                  </span>
                </td>

                <td>{{ gagnant.cadeau }}</td>

                <td class="small">
                  {{ formatDate(gagnant.date_tirage) }}
                </td>

                <td>
                  <span :class="['badge', classeStatut(gagnant.statut)]">
                    {{ libelleStatut(gagnant.statut) }}
                  </span>
                </td>

                <td style="min-width: 190px;">
                  <textarea
                    v-model="gagnant.observationModifiee"
                    class="form-control form-control-sm"
                    rows="2"
                    placeholder="Ajouter une observation..."
                  ></textarea>
                </td>

                <td style="min-width: 180px;">
                  <select
                    v-model="gagnant.statutModifie"
                    class="form-select form-select-sm mb-2"
                  >
                    <option value="en_attente">En attente</option>
                    <option value="confirme">Confirmé</option>
                    <option value="publie">Publié</option>
                    <option value="annule">Annulé</option>
                  </select>

                  <button
                    type="button"
                    class="btn btn-success btn-sm w-100"
                    @click="modifierStatut(gagnant)"
                    :disabled="gagnant.enregistrement"
                  >
                    <span
                      v-if="gagnant.enregistrement"
                      class="spinner-border spinner-border-sm me-1"
                    ></span>

                    <i v-else class="bi bi-save me-1"></i>

                    {{ gagnant.enregistrement ? 'Enregistrement...' : 'Enregistrer' }}
                  </button>
                </td>

              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </div>
</template>
