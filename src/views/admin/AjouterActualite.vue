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
const envoiEnCours = ref(false)

// Galerie : liste de { file, url (aperçu local), type }
const galerie = ref([])

const choisirFichiers = (event) => {
    const fichiers = Array.from(event.target.files || [])
    for (const file of fichiers) {
        galerie.value.push({
            file,
            url: URL.createObjectURL(file),
            type: file.type.startsWith('video/') ? 'video' : 'image',
        })
    }
    event.target.value = '' // permet de resélectionner le même fichier plus tard
}

const retirerMedia = (index) => {
    galerie.value.splice(index, 1)
}

const ajouterActualite = async () => {

    envoiEnCours.value = true

    try {

        // Envoie chaque fichier vers Cloudinary, dans l'ordre
        const mediasEnvoyes = []
        for (const item of galerie.value) {
            const url = await uploadImage(item.file)
            mediasEnvoyes.push({ type: item.type, url })
        }

        // La couverture (affichée dans les listes) = premier média de la galerie
        const couvertureImage = mediasEnvoyes.find((m) => m.type === 'image')?.url || null
        const couvertureVideo = mediasEnvoyes.find((m) => m.type === 'video')?.url || null

        await api.post('/actualites', {

            titre: titre.value,
            contenu: contenu.value,
            auteur: auteur.value,
            statut: statut.value,
            image: couvertureImage,
            video: couvertureVideo,
            medias: mediasEnvoyes,
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

            <label class="form-label">Photos et/ou vidéos</label>

            <input
                type="file"
                accept="image/*,video/mp4,video/quicktime,video/webm"
                class="form-control"
                multiple
                @change="choisirFichiers">
            <small class="text-muted d-block mt-1">
                Vous pouvez sélectionner plusieurs photos et vidéos à la fois (JPG, PNG, WEBP, GIF, MP4, MOV, WEBM — 60 Mo max par vidéo).
                La première photo ajoutée sert de couverture dans les listes.
            </small>

            <div v-if="galerie.length" class="row g-3 mt-2">
                <div class="col-4 col-md-3" v-for="(item, index) in galerie" :key="index">
                    <div class="position-relative">
                        <video v-if="item.type === 'video'" :src="item.url" class="w-100 rounded" style="height:100px;object-fit:cover;" muted></video>
                        <img v-else :src="item.url" class="w-100 rounded" style="height:100px;object-fit:cover;" />
                        <span v-if="item.type === 'video'" class="position-absolute top-0 start-0 m-1 badge bg-dark"><i class="bi bi-camera-video-fill"></i></span>
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

        <button class="btn btn-primary btn-lg" :disabled="envoiEnCours">

            <span v-if="envoiEnCours" class="spinner-border spinner-border-sm me-2"></span>
            <i v-else class="bi bi-check-lg me-1"></i>Enregistrer

        </button>

    </form>
    </div>

</div>

</template>