<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../../services/api'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'


const realisations = ref([])
const recherche = ref('')
const showModal = ref(false)
const idASupprimer = ref(null)
const page = ref(1)
const parPage = 10

const chargerRealisations = async () => {
    try {
        const response = await api.get('/realisations')
        realisations.value = response.data
    } catch (error) {
        console.error(error)
    }
}

const realisationsFiltrees = computed(() => {

    return realisations.value.filter(item => {

        const texte = recherche.value.toLowerCase()

        return (
            item.titre.toLowerCase().includes(texte) ||
            (item.categorie ?? '').toLowerCase().includes(texte) ||
            (item.lieu ?? '').toLowerCase().includes(texte)
        )

    })

})

const totalPages = computed(() =>
    Math.ceil(realisationsFiltrees.value.length / parPage)
)

const realisationsPaginees = computed(() => {

    const debut = (page.value - 1) * parPage

    return realisationsFiltrees.value.slice(
        debut,
        debut + parPage
    )

})

const ouvrirConfirmation = (id) => {
    idASupprimer.value = id
    showModal.value = true
}

const supprimer = async () => {

    try {

        await api.delete(`/realisations/${idASupprimer.value}`)

        showModal.value = false

        chargerRealisations()

    } catch (error) {

        console.error(error)

    }

}

onMounted(chargerRealisations)
</script>

<template>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-1">

        <h2 class="fw-bold mb-0"><i class="bi bi-building text-amber me-2"></i>Gestion des réalisations</h2>

        <RouterLink
            to="/admin/realisations/ajouter"
            class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>Nouvelle réalisation

        </RouterLink>

    </div>
    <p class="text-muted mb-4">Présentez vos projets et chantiers réalisés.</p>

    <div class="mb-3 position-relative" style="max-width: 420px;">

        <i class="bi bi-search position-absolute" style="left: 16px; top: 50%; transform: translateY(-50%); color: #b3ab99;"></i>
        <input
            v-model="recherche"
            class="form-control ps-5"
            placeholder="Rechercher une réalisation...">

    </div>

    <div class="table-responsive">
    <table class="table table-hover align-middle">

        <thead class="table-dark">

            <tr>

                <th>ID</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Lieu</th>
                <th>Date</th>
                <th>Actions</th>

            </tr>

        </thead>

        <tbody>

            <tr
                v-for="item in realisationsPaginees"
                :key="item.id">

                <td>{{ item.id }}</td>

                <td>{{ item.titre }}</td>

                <td>{{ item.categorie }}</td>

                <td>{{ item.lieu }}</td>

                <td>{{ item.date_realisation }}</td>

                <td>

                    <RouterLink
                        :to="`/admin/realisations/modifier/${item.id}`"
                        class="btn btn-warning btn-sm me-2">

                        <i class="bi bi-pencil me-1"></i>Modifier

                    </RouterLink>

                    <button
                        class="btn btn-danger btn-sm"
                        @click="ouvrirConfirmation(item.id)">

                        <i class="bi bi-trash"></i>

                    </button>

                </td>

            </tr>

        </tbody>

    </table>
    </div>

    <div
    class="d-flex justify-content-center mt-4">

    <button
        class="btn btn-outline-primary me-2"
        :disabled="page === 1"
        @click="page--">

        ← Précédent

    </button>

    <span class="align-self-center">

        Page {{ page }} / {{ totalPages || 1 }}

    </span>

    <button
        class="btn btn-outline-primary ms-2"
        :disabled="page >= totalPages"
        @click="page++">

        Suivant →

    </button>

</div>

</div>

<ConfirmModal
    :show="showModal"
    title="Supprimer une réalisation"
    message="Voulez-vous vraiment supprimer cette réalisation ?"

    @confirm="supprimer"
    @cancel="showModal = false"
/>

</template>
