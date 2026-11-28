
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const participants = ref([])
const questions = ref([])
const questionSelectionnee = ref('')
const chargement = ref(false)
const erreur = ref('')
const recherche = ref('')


async function exporterParticipants() {
    try {
        const params = questionSelectionnee.value
            ? { question_id: questionSelectionnee.value }
            : {}

        const response = await api.get(
            'calendrier-reves/exporter',
            {
                params,
                responseType: 'blob'
            }
        )

        const url = window.URL.createObjectURL(
            new Blob([response.data], { type: 'text/csv;charset=utf-8;' })
        )

        const lien = document.createElement('a')
        lien.href = url
        lien.setAttribute(
            'download',
            questionSelectionnee.value
                ? `participants-jour-${questionSelectionnee.value}.csv`
                : 'tous-les-participants-calendrier.csv'
        )

        document.body.appendChild(lien)
        lien.click()
        lien.remove()

        window.URL.revokeObjectURL(url)

    } catch (error) {
        erreur.value = "Impossible d'exporter les participants."
    }
}

function journeeTerminee(participant) {
    const aujourdHui = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'Africa/Abidjan'
    }).format(new Date())

    return participant.date_jeu < aujourdHui
}

function rechercherParticipant(participant) {
    const terme = recherche.value.toLowerCase().trim()

    if (!terme) return true

    const nomComplet = `${participant.nom} ${participant.prenoms}`.toLowerCase()
    const whatsapp = participant.whatsapp.toLowerCase()

    return nomComplet.includes(terme) || whatsapp.includes(terme)
}

async function chargerQuestions() {
    try {
        const response = await api.get('calendrier-reves/admin')
        questions.value = response.data
    } catch (error) {
        erreur.value = "Impossible de charger les journées du jeu."
    }
}

async function chargerParticipants() {
    chargement.value = true
    erreur.value = ''

    try {
        const params = questionSelectionnee.value
            ? { question_id: questionSelectionnee.value }
            : {}

        const response = await api.get(
            'calendrier-reves/participants',
            { params }
        )

        if (Array.isArray(response.data)) {
        participants.value = response.data
        } else {
            console.error('Réponse inattendue de l’API :', response.data)
            participants.value = []
            erreur.value = "Les données reçues par l'API sont incorrectes."
        }
    } catch (error) {
        erreur.value =
            error.response?.data?.message ||
            "Impossible de charger les participants."
    } finally {
        chargement.value = false
    }
}

async function filtrerParticipants() {
    await chargerParticipants()
}

onMounted(async () => {
    await chargerQuestions()
    await chargerParticipants()
})
</script>

<template>
    <div class="participants-page">

        <div class="page-header">
            <div>
                <h2>Gestion des participants</h2>
                <p>Suivez les participations au Calendrier des Rêves Limanya.</p>
            </div>
        </div>

        <div class="stats-card">
            <div>
                <span>Total des participations</span>
                <h3>{{ participants.length }}</h3>
            </div>
            <i class="fas fa-users"></i>
        </div>

        <div class="participants-card">

            <div class="filter-header">
                <div>
                    <h3>Liste des participants</h3>
                    <p>Consultez les réponses enregistrées.</p>
                </div>

                <select
                    v-model="questionSelectionnee"
                    @change="filtrerParticipants"
                >
                    <option value="">Toutes les journées</option>

                    <option
                        v-for="question in questions"
                        :key="question.id"
                        :value="question.id"
                    >
                        Jour {{ question.jour }} - {{ question.cadeau }}
                    </option>
                </select>
                <div class="export-actions">
                    <button
                        type="button"
                        class="btn-export"
                        @click="exporterParticipants"
                    >
                        <i class="fas fa-file-excel"></i>

                            {{ questionSelectionnee
                                ? 'Exporter les bonnes réponses de cette journée'
                                : 'Exporter les bonnes réponses des journées terminées' }}
                    </button>
                </div>
            </div>

            <div class="search-box">
                <i class="fas fa-search"></i>

                <input
                    type="text"
                    v-model="recherche"
                    placeholder="Rechercher par nom ou WhatsApp..."
                >
            </div>

            <div v-if="erreur" class="error-message">
                {{ erreur }}
            </div>

            <div v-if="chargement" class="loading-message">
                Chargement des participants...
            </div>

            <div v-else class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Participant</th>
                            <th>WhatsApp</th>
                            <th>Commune</th>
                            <th>Journée</th>
                            <th>Réponse</th>
                            <th>Date de participation</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="participant in participants.filter(rechercherParticipant)"
                            :key="participant.id"
                        >
                            <td>
                                <strong>
                                    {{ participant.nom }}
                                    {{ participant.prenoms }}
                                </strong>
                            </td>

                            <td>{{ participant.whatsapp }}</td>
                            <td>{{ participant.commune }}</td>

                            <td>
                                <span class="jour-badge">
                                    Jour {{ participant.jour }}
                                </span>
                            </td>

                           <td>
                                <span class="reponse-badge">
                                    {{ participant.reponse }}
                                </span>

                                <span
                                    v-if="journeeTerminee(participant)"
                                    :class="participant.est_correcte
                                        ? 'resultat-correct'
                                        : 'resultat-incorrect'"
                                >
                                    {{ participant.est_correcte
                                        ? 'Bonne réponse'
                                        : 'Mauvaise réponse' }}
                                </span>

                                <span v-else class="resultat-attente">
                                    En attente
                                </span>
                            </td>

                            <td>
                                {{ new Date(
                                    participant.date_participation
                                ).toLocaleString('fr-FR') }}
                            </td>
                        </tr>

                        <tr v-if="participants.filter(rechercherParticipant).length === 0">
                            <td colspan="6" class="empty-message">
                                Aucun participant pour le moment.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<style scoped>
.export-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-export {
    background: #16845b;
    color: white;
    border: none;
    padding: 11px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
}

.btn-export:hover {
    background: #116b49;
}

.btn-export i {
    margin-right: 7px;
}
.reponse-badge {
    display: inline-block;
    padding: 5px 10px;
    background: #f1f3f2;
    border-radius: 6px;
    font-weight: 600;
    margin-right: 5px;
}

.resultat-correct,
.resultat-incorrect,
.resultat-attente {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 11px;
    margin-top: 4px;
}

.resultat-correct {
    background: #e5f7ed;
    color: #16834f;
}

.resultat-incorrect {
    background: #fdeaea;
    color: #c0392b;
}

.resultat-attente {
    background: #fff4dc;
    color: #a66a00;
}
.search-box {
    position: relative;
    min-width: 260px;
}

.search-box i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
}

.search-box input {
    width: 100%;
    padding: 11px 12px 11px 38px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
}

.search-box input:focus {
    border-color: #16845b;
    box-shadow: 0 0 0 3px rgba(22, 132, 91, 0.1);
}
.participants-page {
    padding: 20px;
}

.page-header {
    margin-bottom: 25px;
}

.page-header h2 {
    font-size: 25px;
    font-weight: 700;
    color: #183b32;
}

.page-header p,
.filter-header p {
    color: #777;
    margin-top: 5px;
}

.stats-card {
    background: #fff;
    padding: 22px;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 320px;
    margin-bottom: 25px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
}

.stats-card span {
    color: #777;
}

.stats-card h3 {
    font-size: 30px;
    color: #16845b;
    margin-top: 8px;
}

.stats-card i {
    font-size: 30px;
    color: #16845b;
}

.participants-card {
    background: white;
    padding: 22px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-header h3 {
    color: #183b32;
}

.filter-header select {
    padding: 10px 14px;
    border: 1px solid #ddd;
    border-radius: 8px;
    min-width: 220px;
}

.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 850px;
}

th, td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background: #f7f9f8;
    color: #555;
    font-size: 13px;
}

td {
    font-size: 14px;
    color: #444;
}

.jour-badge {
    background: #e8f5ee;
    color: #16845b;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.empty-message,
.loading-message {
    text-align: center;
    padding: 25px;
    color: #777;
}

.error-message {
    background: #fff1f0;
    color: #c0392b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .participants-page {
        padding: 12px;
    }

    .filter-header {
        align-items: stretch;
        flex-direction: column;
    }

    .filter-header select {
        width: 100%;
    }
}
</style>
