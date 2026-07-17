<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import { uploadImage, getImageUrl } from '../../utils/images'

const route = useRoute()
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

const chargerEquipement = async () => {

    try {

        const response = await api.get(`/equipements/${route.params.id}`)

        Object.assign(form, response.data)

    } catch (error) {

        console.error(error)

    }

}

const choisirImage = (event) => {
    const file = event.target.files[0]
    if (!file) return
    fichierImage.value = file
    apercu.value = URL.createObjectURL(file)
}

const modifier = async () => {

    envoiEnCours.value = true

    try {

        let imagePath = form.image
        if (fichierImage.value) {
            imagePath = await uploadImage(fichierImage.value)
        }

        await api.put(`/equipements/${route.params.id}`, {
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

onMounted(chargerEquipement)
</script>

<template>

<div class="container py-5" style="max-width: 820px;">

    <RouterLink to="/admin/equipements" class="text-muted d-inline-flex align-items-center mb-3" style="gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour aux équipements
    </RouterLink>

    <h2 class="fw-bold mb-1">
        <i class="bi bi-box-seam text-amber me-2"></i>Modifier un équipement
    </h2>
    <p class="text-muted mb-4">Mettez à jour les informations de cet équipement.</p>

    <div class="card p-4 p-md-5">
    <form @submit.prevent="modifier">

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

            <img :src="apercu || getImageUrl(form.image)" v-if="apercu || form.image" class="img-thumbnail mt-3" style="max-height:180px;" />

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

        <div class="d-flex gap-2">
        <button
            class="btn btn-primary btn-lg"
            :disabled="envoiEnCours">

            <span v-if="envoiEnCours" class="spinner-border spinner-border-sm me-2"></span>
            <i v-else class="bi bi-check-lg me-1"></i>Enregistrer les modifications

        </button>

        <RouterLink
            to="/admin/equipements"
            class="btn btn-secondary btn-lg">

            Annuler

        </RouterLink>
        </div>

    </form>
    </div>

</div>

</template>
