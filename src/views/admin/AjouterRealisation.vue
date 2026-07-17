<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import { uploadImage, getImageUrl } from '../../utils/images'

const router = useRouter()

const form = reactive({
    titre: '',
    description: '',
    categorie: '',
    lieu: '',
    date_realisation: ''
})

const fichierImage = ref(null)
const apercu = ref(null)
const envoiEnCours = ref(false)
const erreur = ref('')

const choisirImage = (event) => {
    const file = event.target.files[0]
    if (!file) return
    fichierImage.value = file
    apercu.value = URL.createObjectURL(file)
}

const enregistrer = async () => {
    erreur.value = ''
    envoiEnCours.value = true
    try {

        let imagePath = null
        if (fichierImage.value) {
            imagePath = await uploadImage(fichierImage.value)
        }

        await api.post('/realisations', {
            ...form,
            image: imagePath
        })

        router.push('/admin/realisations')

    } catch (error) {

        console.error(error)
        erreur.value = "Erreur lors de l'enregistrement. Vérifiez les champs et réessayez."

    } finally {
        envoiEnCours.value = false
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
            <select
                v-model="form.categorie"
                class="form-select">
                <option disabled value="">Choisir une catégorie</option>
                <option>Forages Hydrauliques</option>
                <option>Études Géophysiques</option>
                <option>Assainissement</option>
                <option>BTP & Génie Civil</option>
            </select>
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
            <label class="form-label">Photo</label>
            <input
                type="file"
                accept="image/*"
                class="form-control"
                @change="choisirImage">
            <img v-if="apercu" :src="apercu" class="img-thumbnail mt-3" style="max-height:180px;" />
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea
                v-model="form.description"
                rows="5"
                class="form-control">
            </textarea>
        </div>

        <div v-if="erreur" class="alert alert-danger py-2">{{ erreur }}</div>

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
