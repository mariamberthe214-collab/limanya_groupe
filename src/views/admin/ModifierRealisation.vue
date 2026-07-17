<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import { uploadImage, getImageUrl } from '../../utils/images'

const route = useRoute()
const router = useRouter()

const form = ref({
  titre: '',
  description: '',
  categorie: '',
  lieu: '',
  date_realisation: '',
  image: null
})

const fichierImage = ref(null)
const apercu = ref(null)
const fichierVideo = ref(null)
const nomVideo = ref('')
const envoiEnCours = ref(false)

const charger = async () => {
  try {
    const res = await api.get(`/realisations/${route.params.id}`)
    form.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const choisirImage = (event) => {
  const file = event.target.files[0]
  if (!file) return
  fichierImage.value = file
  apercu.value = URL.createObjectURL(file)
}

const choisirVideo = (event) => {
  const file = event.target.files[0]
  if (!file) return
  fichierVideo.value = file
  nomVideo.value = file.name
}

const update = async () => {
  envoiEnCours.value = true
  try {
    let imagePath = form.value.image
    if (fichierImage.value) {
      imagePath = await uploadImage(fichierImage.value)
    }

    let videoPath = form.value.video
    if (fichierVideo.value) {
      videoPath = await uploadImage(fichierVideo.value)
    }

    await api.put(`/realisations/${route.params.id}`, {
      ...form.value,
      image: imagePath,
      video: videoPath
    })
    router.push('/admin/realisations')
  } catch (err) {
    console.error(err)
  } finally {
    envoiEnCours.value = false
  }
}

onMounted(charger)
</script>

<template>
<div class="container py-5" style="max-width: 820px;">

  <RouterLink to="/admin/realisations" class="text-muted d-inline-flex align-items-center mb-3" style="gap:6px;">
    <i class="bi bi-arrow-left"></i> Retour aux réalisations
  </RouterLink>

  <h2 class="fw-bold mb-1">
    <i class="bi bi-building text-amber me-2"></i>Modifier une réalisation
  </h2>
  <p class="text-muted mb-4">Mettez à jour les informations de ce projet.</p>

  <div class="card p-4 p-md-5">
  <form @submit.prevent="update">

    <div class="mb-3">
      <label class="form-label">Titre</label>
      <input v-model="form.titre" class="form-control" placeholder="Titre" />
    </div>

    <div class="mb-3">
      <label class="form-label">Catégorie</label>
      <select v-model="form.categorie" class="form-select">
        <option disabled value="">Choisir une catégorie</option>
        <option>Forages Hydrauliques</option>
        <option>Études Géophysiques</option>
        <option>Assainissement</option>
        <option>BTP & Génie Civil</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Lieu</label>
      <input v-model="form.lieu" class="form-control" placeholder="Lieu" />
    </div>

    <div class="mb-3">
      <label class="form-label">Date</label>
      <input v-model="form.date_realisation" type="date" class="form-control" />
    </div>

    <div class="mb-3">
      <label class="form-label">Photo</label>
      <input type="file" accept="image/*" class="form-control" @change="choisirImage" />
      <img :src="apercu || getImageUrl(form.image)" v-if="apercu || form.image" class="img-thumbnail mt-3" style="max-height:180px;" />
    </div>

    <div class="mb-3">
      <label class="form-label">Vidéo (optionnel)</label>
      <input type="file" accept="video/mp4,video/quicktime,video/webm" class="form-control" @change="choisirVideo" />
      <small class="text-muted" v-if="nomVideo">Nouveau fichier : {{ nomVideo }}</small>
      <small class="text-muted d-block" v-else-if="form.video">Vidéo actuelle : {{ form.video.split('/').pop() }}</small>
      <small class="text-muted d-block" v-else>Formats acceptés : MP4, MOV, WEBM (60 Mo max).</small>
    </div>

    <div class="mb-4">
      <label class="form-label">Description</label>
      <textarea v-model="form.description" rows="5" class="form-control" placeholder="Description"></textarea>
    </div>

    <button class="btn btn-primary btn-lg" :disabled="envoiEnCours">
      <span v-if="envoiEnCours" class="spinner-border spinner-border-sm me-2"></span>
      <i v-else class="bi bi-check-lg me-1"></i>Enregistrer les modifications
    </button>

  </form>
  </div>

</div>
</template>
