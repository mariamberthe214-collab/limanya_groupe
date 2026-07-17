<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import { uploadImage, getImageUrl } from '../../utils/images'

const route = useRoute()
const router = useRouter()

const id = route.params.id

const titre = ref('')
const contenu = ref('')
const auteur = ref('')
const statut = ref('Publié')
const date_publication = ref('')
const image = ref(null)
const fichierImage = ref(null)
const apercu = ref(null)
const envoiEnCours = ref(false)

const chargerActualite = async () => {
    try {

        const response = await api.get(`/actualites/${id}`)

        titre.value = response.data.titre
        contenu.value = response.data.contenu
        auteur.value = response.data.auteur
        statut.value = response.data.statut
        date_publication.value = response.data.date_publication ? response.data.date_publication.slice(0, 10) : ''
        image.value = response.data.image

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

const modifierActualite = async () => {

    envoiEnCours.value = true

    try {

        let imagePath = image.value
        if (fichierImage.value) {
            imagePath = await uploadImage(fichierImage.value)
        }

        await api.put(`/actualites/${id}`, {

            titre: titre.value,
            contenu: contenu.value,
            auteur: auteur.value,
            statut: statut.value,
            image: imagePath,
            date_publication: date_publication.value

        })

        router.push('/admin/actualites')

    } catch (error) {

        console.error(error)
        alert("Erreur lors de la modification.")

    } finally {
        envoiEnCours.value = false
    }

}

onMounted(() => {

    chargerActualite()

})
</script>

<template>

<div class="container py-5" style="max-width: 820px;">

    <RouterLink to="/admin/actualites" class="text-muted d-inline-flex align-items-center mb-3" style="gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour aux actualités
    </RouterLink>

    <h2 class="fw-bold mb-1">
        <i class="bi bi-newspaper text-amber me-2"></i>Modifier une actualité
    </h2>
    <p class="text-muted mb-4">Mettez à jour le contenu de cette actualité.</p>

    <div class="card p-4 p-md-5">
    <form @submit.prevent="modifierActualite">

        <div class="mb-3">

            <label class="form-label">Titre</label>

            <input
                class="form-control"
                v-model="titre"
                required>

        </div>

        <div class="mb-3">

            <label class="form-label">Contenu</label>

            <textarea
                class="form-control"
                rows="6"
                v-model="contenu"
                required></textarea>

        </div>

        <div class="mb-3">

            <label class="form-label">Auteur</label>

            <input
                class="form-control"
                v-model="auteur">

        </div>

        <div class="mb-3">

            <label class="form-label">Statut</label>

            <select
                class="form-select"
                v-model="statut">

                <option>Publié</option>
                <option>Brouillon</option>

            </select>

        </div>

        <div class="mb-4">

            <label class="form-label">
                Date de publication
            </label>

            <input
                type="date"
                class="form-control"
                v-model="date_publication">

        </div>

        <div class="mb-4">

            <label class="form-label">Photo</label>

            <input
                type="file"
                accept="image/*"
                class="form-control"
                @change="choisirImage">

            <img :src="apercu || getImageUrl(image)" v-if="apercu || image" class="img-thumbnail mt-3" style="max-height:180px;" />

        </div>

        <button
            class="btn btn-primary btn-lg"
            :disabled="envoiEnCours">

            <span v-if="envoiEnCours" class="spinner-border spinner-border-sm me-2"></span>
            <i v-else class="bi bi-check-lg me-1"></i>Enregistrer les modifications

        </button>

    </form>
    </div>

</div>

</template>
