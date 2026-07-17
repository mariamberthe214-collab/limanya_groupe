<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import { uploadImage } from '../../utils/images'

const router = useRouter()

const form = reactive({
    nom: '',
    description: '',
    image: '',
    prix: '',
    disponible: 1,
    categorie: 'Matériels de forage'
})

const fichierImage = ref(null)
const apercu = ref(null)
const envoiEnCours = ref(false)

const choisirImage = (event) => {
    const file = event.target.files[0]
    if (!file) return
    fichierImage.value = file
    apercu.value = URL.createObjectURL(file)
}

const enregistrer = async () => {

    envoiEnCours.value = true

    try {

        let imagePath = null
        if (fichierImage.value) {
            imagePath = await uploadImage(fichierImage.value)
        }

        await api.post('/equipements', {
            ...form,
            image: imagePath
        })

        router.push('/admin/equipements')

    } catch (error) {

        console.error(error)

    } finally {
        envoiEnCours.value = false
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
                Photo
            </label>

            <input
                type="file"
                accept="image/*"
                class="form-control"
                @change="choisirImage">

            <img v-if="apercu" :src="apercu" class="img-thumbnail mt-3" style="max-height:180px;" />

        </div>

        <div class="mb-3">

            <label class="form-label">
                Catégorie
            </label>

            <select
                v-model="form.categorie"
                class="form-select">
                <option>Pompes solaires</option>
                <option>Pompes électriques</option>
                <option>Pompes hybrides</option>
                <option>Panneaux solaires</option>
                <option>PVC hydrauliques</option>
                <option>Équipements hydrauliques</option>
                <option>Matériels de forage</option>
                <option>Accessoires</option>
            </select>

            <small class="text-muted">
                Toutes les catégories s'affichent sur la page "Vente de matériels", classées par filtre.
            </small>

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
            class="btn btn-primary btn-lg"
            :disabled="envoiEnCours">

            <span v-if="envoiEnCours" class="spinner-border spinner-border-sm me-2"></span>
            <i v-else class="bi bi-check-lg me-1"></i>Enregistrer

        </button>

    </form>
    </div>

</div>

</template>
