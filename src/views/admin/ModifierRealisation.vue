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

const medias = ref([])
const envoiEnCours = ref(false)

const charger = async () => {
  try {
    const res = await api.get(`/realisations/${route.params.id}`)
    form.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const choisirMedias = (event) => {
  medias.value = Array.from(event.target.files || []).map((file) => ({
    file,
    url: URL.createObjectURL(file),
    type: file.type.startsWith('video/') ? 'video' : 'image',
  }))
}

const update = async () => {
  envoiEnCours.value = true
  try {
    const mediasUploades = medias.value.length
      ? []
      : (form.value.medias || [])
    for (const media of medias.value) {
      mediasUploades.push({ url: await uploadImage(media.file), type: media.type })
    }

    await api.put(`/realisations/${route.params.id}`, {
      ...form.value,
      image: mediasUploades.find((media) => media.type === 'image')?.url || form.value.image || null,
      video: mediasUploades.find((media) => media.type === 'video')?.url || form.value.video || null,
      medias: mediasUploades,
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
<div class="container py-5 admin-form-page" style="max-width: 820px;">

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
      <label class="form-label">Photos et vidéos</label>
      <input type="file" accept="image/*,video/mp4,video/quicktime,video/webm" multiple class="form-control" @change="choisirMedias" />
      <small class="text-muted d-block mt-1">Sélectionner de nouveaux fichiers remplace la galerie actuelle.</small>
      <div v-if="medias.length || form.medias?.length" class="row g-2 mt-2">
        <div v-for="(media, index) in (medias.length ? medias : form.medias)" :key="index" class="col-4 col-md-3">
          <video v-if="media.type === 'video'" :src="media.url || getImageUrl(media.url)" class="w-100 rounded" style="height:90px;object-fit:cover;" muted></video>
          <img v-else :src="media.url || getImageUrl(media.url)" class="w-100 rounded" style="height:90px;object-fit:cover;" alt="Aperçu du média" />
        </div>
      </div>
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
