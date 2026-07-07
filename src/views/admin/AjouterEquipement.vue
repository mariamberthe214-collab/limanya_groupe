<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()

const form = reactive({
    nom: '',
    description: '',
    image: '',
    prix: '',
    disponible: 1
})

const enregistrer = async () => {

    try {

        await api.post('/equipements', form)

        router.push('/admin/equipements')

    } catch (error) {

        console.error(error)

    }

}
</script>

<template>

<div class="container py-5" style="max-width: 820px;">

    <RouterLink to="/admin/equipements" class="text-muted d-inline-flex align-items-center mb-3" style="gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour aux équipements
    </RouterLink>

    <h2 class="fw-bold mb-1">
        <i class="bi bi-box-seam text-amber me-2"></i>Ajouter un équipement
    </h2>
    <p class="text-muted mb-4">Ajoutez un nouvel équipement au catalogue.</p>

    <div class="card p-4 p-md-5">
    <form @submit.prevent="enregistrer">

        <div class="mb-3">

            <label class="form-label">
                Nom
            </label>

            <input
                v-model="form.nom"
                class="form-control"
                required>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Description
            </label>

            <textarea
                v-model="form.description"
                rows="5"
                class="form-control">
            </textarea>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Image (URL)
            </label>

            <input
                v-model="form.image"
                class="form-control">

        </div>

        <div class="mb-3">

            <label class="form-label">
                Prix
            </label>

            <input
                type="number"
                step="0.01"
                v-model="form.prix"
                class="form-control">

        </div>

        <div class="form-check mb-4">

            <input
                class="form-check-input"
                type="checkbox"
                v-model="form.disponible">

            <label class="form-check-label">

                Disponible

            </label>

        </div>

        <button
            class="btn btn-primary btn-lg">

            <i class="bi bi-check-lg me-1"></i>Enregistrer

        </button>

    </form>
    </div>

</div>

</template>
