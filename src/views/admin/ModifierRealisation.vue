<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'

const route = useRoute()
const router = useRouter()

const form = ref({
  titre: '',
  description: '',
  categorie: '',
  lieu: '',
  date_realisation: ''
})

const charger = async () => {
  try {
    const res = await api.get(`/realisations/${route.params.id}`)
    form.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const update = async () => {
  try {
    await api.put(`/realisations/${route.params.id}`, form.value)
    router.push('/admin/realisations')
  } catch (err) {
    console.error(err)
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
      <input v-model="form.categorie" class="form-control" placeholder="Catégorie" />
    </div>

    <div class="mb-3">
      <label class="form-label">Lieu</label>
      <input v-model="form.lieu" class="form-control" placeholder="Lieu" />
    </div>

    <div class="mb-3">
      <label class="form-label">Date</label>
      <input v-model="form.date_realisation" type="date" class="form-control" />
    </div>

    <div class="mb-4">
      <label class="form-label">Description</label>
      <textarea v-model="form.description" rows="5" class="form-control" placeholder="Description"></textarea>
    </div>

    <button class="btn btn-primary btn-lg"><i class="bi bi-check-lg me-1"></i>Enregistrer les modifications</button>

  </form>
  </div>

</div>
</template>
