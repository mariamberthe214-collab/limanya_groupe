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

const medias = ref([])
const envoiEnCours = ref(false)
const erreur = ref('')

const choisirMedias = (event) => {
    medias.value = Array.from(event.target.files || []).map((file) => ({
        file,
        url: URL.createObjectURL(file),
        type: file.type.startsWith('video/') ? 'video' : 'image',
    }))
}

const enregistrer = async () => {
    erreur.value = ''
    envoiEnCours.value = true
    try {

        const mediasUploades = []
        for (const media of medias.value) {
            mediasUploades.push({ url: await uploadImage(media.file), type: media.type })
        }

        await api.post('/realisations', {
            ...form,
            image: mediasUploades.find((media) => media.type === 'image')?.url || null,
            video: mediasUploades.find((media) => media.type === 'video')?.url || null,
            medias: mediasUploades,
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

<div class="container py-5 admin-form-page" style="max-width: 820px;">

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
            <label class="form-label">Photos et vidéos</label>
            <input
                type="file"
                accept="image/*,video/mp4,video/quicktime,video/webm"
                class="form-control"
                multiple
                @change="choisirMedias">
            <small class="text-muted d-block mt-1">Ajoutez plusieurs photos et vidéos. La première photo devient la couverture.</small>
            <div v-if="medias.length" class="row g-2 mt-2">
                <div v-for="(media, index) in medias" :key="index" class="col-4 col-md-3">
                    <video v-if="media.type === 'video'" :src="media.url" class="w-100 rounded" style="height:90px;object-fit:cover;" muted></video>
                    <img v-else :src="media.url" class="w-100 rounded" style="height:90px;object-fit:cover;" alt="Aperçu du média" />
                </div>
            </div>
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

<style scoped>
@media (max-width: 767.98px) {
    .admin-form-page {
        padding-top: 1.25rem !important;
        padding-bottom: 2rem !important;
    }

    .admin-form-page h2 {
        font-size: 1.45rem;
        line-height: 1.2;
    }

    .admin-form-page .card {
        padding: 1.25rem !important;
    }

    .admin-form-page form > .btn {
        width: 100%;
    }
}
</style>
