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
const envoiEnCours = ref(false)

// Médias déjà enregistrés : { type, url, existant: true }
// Nouveaux médias ajoutés : { type, url (aperçu local), file, existant: false }
const galerie = ref([])

const chargerActualite = async () => {
    try {

        const response = await api.get(`/actualites/${id}`)

        titre.value = response.data.titre
        contenu.value = response.data.contenu
        auteur.value = response.data.auteur
        statut.value = response.data.statut
        date_publication.value = response.data.date_publication ? response.data.date_publication.slice(0, 10) : ''

        if (Array.isArray(response.data.medias) && response.data.medias.length) {
            galerie.value = response.data.medias.map((m) => ({ ...m, url: getImageUrl(m.url), existant: true }))
        } else if (response.data.image || response.data.video) {
            // Compatibilité avec les actualités créées avant l'ajout de la galerie
            if (response.data.image) galerie.value.push({ type: 'image', url: getImageUrl(response.data.image), existant: true })
            if (response.data.video) galerie.value.push({ type: 'video', url: getImageUrl(response.data.video), existant: true })
        }

    } catch (error) {
        console.error(error)
    }
}

const choisirFichiers = (event) => {
    const fichiers = Array.from(event.target.files || [])
    for (const file of fichiers) {
        galerie.value.push({
            file,
            url: URL.createObjectURL(file),
            type: file.type.startsWith('video/') ? 'video' : 'image',
            existant: false,
        })
    }
    event.target.value = ''
}

const retirerMedia = (index) => {
    galerie.value.splice(index, 1)
}

const modifierActualite = async () => {

    envoiEnCours.value = true

    try {

        const mediasFinaux = []
        for (const item of galerie.value) {
            if (item.existant) {
                // Le url a été résolu en URL complète pour l'aperçu : on retrouve le chemin d'origine
                mediasFinaux.push({ type: item.type, url: item.url })
            } else {
                const url = await uploadImage(item.file)
                mediasFinaux.push({ type: item.type, url })
            }
        }

        const couvertureImage = mediasFinaux.find((m) => m.type === 'image')?.url || null
        const couvertureVideo = mediasFinaux.find((m) => m.type === 'video')?.url || null

        await api.put(`/actualites/${id}`, {

            titre: titre.value,
            contenu: contenu.value,
            auteur: auteur.value,
            statut: statut.value,
            image: couvertureImage,
            video: couvertureVideo,
            medias: mediasFinaux,
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

            <label class="form-label">Photos et/ou vidéos</label>

            <input
                type="file"
                accept="image/*,video/mp4,video/quicktime,video/webm"
                class="form-control"
                multiple
                @change="choisirFichiers">
            <small class="text-muted d-block mt-1">
                Ajoutez une ou plusieurs photos/vidéos. La première photo sert de couverture dans les listes.
            </small>

            <div v-if="galerie.length" class="row g-3 mt-2">
                <div class="col-4 col-md-3" v-for="(item, index) in galerie" :key="index">
                    <div class="position-relative">
                        <video v-if="item.type === 'video'" :src="item.url" class="w-100 rounded" style="height:100px;object-fit:cover;" muted></video>
                        <img v-else :src="item.url" class="w-100 rounded" style="height:100px;object-fit:cover;" />
                        <span v-if="item.type === 'video'" class="position-absolute top-0 start-0 m-1 badge bg-dark"><i class="bi bi-camera-video-fill"></i></span>
                        <span v-if="!item.existant" class="position-absolute bottom-0 start-0 m-1 badge bg-success">Nouveau</span>
                        <button
                            type="button"
                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 py-0 px-1"
                            @click="retirerMedia(index)"
                            title="Retirer">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>

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