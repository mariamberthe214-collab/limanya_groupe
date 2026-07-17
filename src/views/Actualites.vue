<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'

const posts = ref([])
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const chargerActualites = async () => {
  try {
    const response = await api.get('/actualites?statut=Publié')
    posts.value = response.data
  } catch (error) {
    console.error(error)
  }
}

onMounted(() => {
  chargerActualites()
})
</script>

<template>
  <section class="page-section py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <span class="eyebrow text-uppercase">Actualités</span>
        <h1 class="display-6 fw-bold mt-3">Les dernières nouvelles de LIMANYA Groupe</h1>
        <p class="text-muted mt-3">Suivez nos projets récents, nos chantiers en cours et nos initiatives en énergie renouvelable.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4" v-for="post in posts" :key="post.id">
          <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="ratio ratio-16x9 bg-secondary bg-opacity-10" v-if="post.image">
              <img :src="getImageUrl(post.image)" :alt="post.titre" class="w-100 h-100 object-fit-cover">
            </div>
            <div class="p-4">
              <span class="badge bg-primary mb-3">
                {{ formatDate(post.date_publication) }}
              </span>

              <h5>{{ post.titre }}</h5>

              <p class="text-muted">
                {{ post.contenu }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>


