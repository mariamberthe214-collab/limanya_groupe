<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '../../services/api'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'

const equipements = ref([])
const recherche = ref('')

const page = ref(1)
const parPage = 10

const showModal = ref(false)
const idASupprimer = ref(null)

const chargerEquipements = async () => {
    try {
        const response = await api.get('/equipements')
        equipements.value = response.data
    } catch (error) {
        console.error(error)
    }
}

const equipementsFiltres = computed(() => {

    return equipements.value.filter(item => {

        const texte = recherche.value.toLowerCase()

        return (
            item.nom.toLowerCase().includes(texte) ||
            (item.description ?? '').toLowerCase().includes(texte)
        )

    })

})

const totalPages = computed(() =>
    Math.ceil(equipementsFiltres.value.length / parPage)
)

const equipementsPagine = computed(() => {

    const debut = (page.value - 1) * parPage

    return equipementsFiltres.value.slice(debut, debut + parPage)

})

const ouvrirConfirmation = (id) => {
    idASupprimer.value = id
    showModal.value = true
}

const supprimer = async () => {

    try {

        await api.delete(`/equipements/${idASupprimer.value}`)

        showModal.value = false

        chargerEquipements()

    } catch (error) {

        console.error(error)

    }

}

onMounted(chargerEquipements)
</script>

<template>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-1">

        <h2 class="fw-bold mb-0"><i class="bi bi-box-seam text-amber me-2"></i>Équipements</h2>

        <RouterLink
            to="/admin/equipements/ajouter"
            class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>Ajouter

        </RouterLink>

    </div>
    <p class="text-muted mb-4">Gérez le catalogue des équipements et matériels.</p>

    <div class="mb-4 position-relative" style="max-width: 420px;">
        <i class="bi bi-search position-absolute" style="left: 16px; top: 50%; transform: translateY(-50%); color: #b3ab99;"></i>
        <input
            v-model="recherche"
            class="form-control ps-5"
            placeholder="Rechercher...">
    </div>

    <div class="table-responsive">
    <table class="table table-hover align-middle">

        <thead class="table-dark">

            <tr>

                <th>ID</th>

                <th>Nom</th>

                <th>Prix</th>

                <th>Disponible</th>

                <th>Actions</th>

            </tr>

        </thead>

        <tbody>

            <tr
                v-for="item in equipementsPagine"
                :key="item.id">

                <td>{{ item.id }}</td>

                <td>{{ item.nom }}</td>

                <td>{{ item.prix }} FCFA</td>

                <td>

                    <span
                        class="badge"
                        :class="item.disponible ? 'bg-success' : 'bg-danger'">

                        {{ item.disponible ? 'Oui' : 'Non' }}

                    </span>

                </td>

                <td>

                    <RouterLink
                        :to="`/admin/equipements/modifier/${item.id}`"
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

    <div class="d-flex justify-content-center mt-4">

        <button
            class="btn btn-outline-primary me-2"
            :disabled="page===1"
            @click="page--">

            ←

        </button>

        <span class="align-self-center">

            Page {{ page }} / {{ totalPages || 1 }}

        </span>

        <button
            class="btn btn-outline-primary ms-2"
            :disabled="page>=totalPages"
            @click="page++">

            →

        </button>

    </div>

    <ConfirmModal
        :show="showModal"
        title="Supprimer un équipement"
        message="Voulez-vous vraiment supprimer cet équipement ?"
        @confirm="supprimer"
        @cancel="showModal=false"/>

</div>

</template>
