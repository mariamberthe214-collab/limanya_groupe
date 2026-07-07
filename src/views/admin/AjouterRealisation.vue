<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()

const form = reactive({
    titre: '',
    description: '',
    categorie: '',
    lieu: '',
    date_realisation: ''
})

const enregistrer = async () => {
    try {

        await api.post('/realisations', {
            ...form,
            image: null
        })

        router.push('/admin/realisations')

    } catch (error) {

        console.error(error)

    }
}
</script>

<template>

<div class="container py-5" style="max-width: 820px;">

    <RouterLink to="/admin/realisations" class="text-muted d-inline-flex align-items-center mb-3" style="gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour aux réalisations
    </RouterLink>

    <h2 class="fw-bold mb-1">
        <i class="bi bi-building text-amber me-2"></i>Nouvelle réalisation
    </h2>
    <p class="text-muted mb-4">Ajoutez un projet à votre portfolio.</p>

    <div class="card p-4 p-md-5">
    <form @submit.prevent="enregistrer">

        <div class="mb-3">
            <label class="form-label">Titre</label>
            <input
                v-model="form.titre"
                class="form-control"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Catégorie</label>
            <input
                v-model="form.categorie"
                class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Lieu</label>
            <input
                v-model="form.lieu"
                class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input
                type="date"
                v-model="form.date_realisation"
                class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea
                v-model="form.description"
                rows="5"
                class="form-control">
            </textarea>
        </div>

        <button
            class="btn btn-primary btn-lg">

            <i class="bi bi-check-lg me-1"></i>Enregistrer

        </button>

    </form>
    </div>

</div>

</template>
