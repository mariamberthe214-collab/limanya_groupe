<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import { uploadImage } from '../../utils/images'

const router = useRouter()

const titre = ref('')
const contenu = ref('')
const auteur = ref('Admin')
const statut = ref('Publié')
const date_publication = ref(new Date().toISOString().split('T')[0])
const fichierImage = ref(null)
const apercu = ref(null)
const envoiEnCours = ref(false)

const choisirImage = (event) => {
    const file = event.target.files[0]
    if (!file) return
    fichierImage.value = file
    apercu.value = URL.createObjectURL(file)
}

const ajouterActualite = async () => {

    envoiEnCours.value = true

    try {

        let imagePath = null
        if (fichierImage.value) {
            imagePath = await uploadImage(fichierImage.value)
        }

        await api.post('/actualites', {

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
        alert("Erreur lors de l'ajout.")

    } finally {
        envoiEnCours.value = false
    }

}
</script>

<template>

<div class="container py-5" style="max-width: 820px;">

    <RouterLink to="/admin/actualites" class="text-muted d-inline-flex align-items-center mb-3" style="gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour aux actualités
    </RouterLink>

    <h2 class="fw-bold mb-1">
        <i class="bi bi-newspaper text-amber me-2"></i>Ajouter une actualité
    </h2>
    <p class="text-muted mb-4">Rédigez et publiez une nouvelle actualité.</p>

    <div class="card p-4 p-md-5">
    <form @submit.prevent="ajouterActualite">

        <div class="mb-3">
            <label class="form-label">Titre</label>

            <input
                v-model="titre"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">

            <label class="form-label">Contenu</label>

            <textarea
                v-model="contenu"
                rows="6"
                class="form-control"
                required
            ></textarea>

        </div>

        <div class="mb-3">

            <label class="form-label">Auteur</label>

            <input
                v-model="auteur"
                class="form-control"
            >

        </div>

        <div class="mb-3">

            <label class="form-label">
                Statut
            </label>

            <select
                v-model="statut"
                class="form-select">

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
                v-model="date_publication"
                class="form-control">

        </div>

        <div class="mb-4">

            <label class="form-label">Photo</label>

            <input
                type="file"
                accept="image/*"
                class="form-control"
                @change="choisirImage">

            <img v-if="apercu" :src="apercu" class="img-thumbnail mt-3" style="max-height:180px;" />

        </div>

        <button class="btn btn-primary btn-lg" :disabled="envoiEnCours">

            <span v-if="envoiEnCours" class="spinner-border spinner-border-sm me-2"></span>
            <i v-else class="bi bi-check-lg me-1"></i>Enregistrer

        </button>

    </form>
    </div>

</div>

</template>
