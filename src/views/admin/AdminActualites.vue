```vue
<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import api from '../../services/api'
import Breadcrumb from '../../components/admin/Breadcrumb.vue'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'
import ToastNotification from '../../components/admin/ToastNotification.vue'

/* ===========================
   Données
=========================== */

const actualites = ref([])
const recherche = ref('')
const pageCourante = ref(1)
const elementsParPage = 10
const showModal = ref(false)
const actualiteASupprimer = ref(null)
const showToast = ref(false)
const toastMessage = ref('')
const toastType = ref('success')

/* ===========================
   Actualités filtrées
=========================== */

watch(recherche, () => {

    pageCourante.value = 1

})

const afficherToast = (message, type = 'success') => {

  toastMessage.value = message
  toastType.value = type
  showToast.value = true

  setTimeout(() => {

    showToast.value = false

  }, 3000)

}

const actualitesAffichees = computed(() => {

    const debut = (pageCourante.value - 1) * elementsParPage

    return actualitesFiltrees.value.slice(
        debut,
        debut + elementsParPage
    )

})

const nombrePages = computed(() => {

    return Math.ceil(
        actualitesFiltrees.value.length / elementsParPage
    )

})

const actualitesFiltrees = computed(() => {

    return actualites.value.filter(item =>

        item.titre
            .toLowerCase()
            .includes(recherche.value.toLowerCase())

    )

})

/* ===========================
   Format de la date
=========================== */

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}

/* ===========================
   Charger les actualités
=========================== */

const chargerActualites = async () => {
  try {
    const response = await api.get('/actualites')
    actualites.value = response.data
  } catch (error) {
    console.error(error)
  }
}

/* ===========================
   Supprimer une actualité
=========================== */

const ouvrirConfirmation = (id) => {

    actualiteASupprimer.value = id

    showModal.value = true

}

const supprimerActualite = async () => {

    try {

        await api.delete(`/actualites/${actualiteASupprimer.value}`)

        actualites.value = actualites.value.filter(

            item => item.id !== actualiteASupprimer.value

        )

    } catch (error) {

        console.error(error)

    }

    showModal.value = false

}
/* ===========================
   Chargement initial
=========================== */

onMounted(() => {
  chargerActualites()
})
</script>

<template>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-4">

            {{ actualitesFiltrees.length }}

            actualité(s)

        </p>
        <Breadcrumb

            titre="Gestion des actualités"

            page="Actualités"

        />

       <RouterLink
            to="/admin/actualites/ajouter"
            class="btn btn-primary">

            <i class="bi bi-plus-circle me-2"></i>

            Nouvelle actualité

        </RouterLink>

    </div>

    <div class="row mb-4">

        <div class="col-md-5">

           <div class="input-group">

                <span class="input-group-text">

                    <i class="bi bi-search"></i>

                </span>

                <input
                    v-model="recherche"
                    class="form-control"
                    placeholder="Rechercher une actualité...">

            </div>

        </div>

    </div>

    <div class="table-responsive">
        <div class="card shadow-sm border-0">

    <div class="card-body">

        <table class="table table-hover table-bordered align-middle">

            <thead class="table-dark">

                <tr>

                    <th style="width:80px">ID</th>

                    <th>Titre</th>

                    <th style="width:180px">Date</th>

                    <th style="width:180px">Actions</th>

                </tr>

            </thead>

            <tbody>

                <tr
                    v-for="item in actualitesAffichees"
                    :key="item.id">

                    <td>{{ item.id }}</td>

                    <td>{{ item.titre }}</td>

                    <td>

                        {{ formatDate(item.date_publication) }}

                    </td>

                    <td>
                    <RouterLink
                        :to="`/admin/actualites/modifier/${item.id}`"
                        class="btn btn-warning btn-sm me-2"
                        title="Modifier">

                        <i class="bi bi-pencil-square"></i>

                    </RouterLink>

                    <button
                        class="btn btn-danger btn-sm"
                        @click="ouvrirConfirmation(item.id)"
                        title="Supprimer">

                        <i class="bi bi-trash"></i>

                    </button>

                    </td>

                </tr>

                <tr v-if="actualitesFiltrees.length === 0">

                    <td colspan="4" class="text-center text-muted">

                        Aucune actualité trouvée.

                    </td>

                </tr>

            </tbody>

        </table>
         </div>

</div>

        <nav
    class="mt-4 d-flex justify-content-center"
    v-if="nombrePages > 1">

    <ul class="pagination">

        <li
            class="page-item"
            :class="{ disabled: pageCourante === 1 }">

            <button
                class="page-link"
                @click="pageCourante--"
                :disabled="pageCourante === 1">

                Précédent

            </button>

        </li>

        <li
            v-for="page in nombrePages"
            :key="page"
            class="page-item"
            :class="{ active: page === pageCourante }">

            <button
                class="page-link"
                @click="pageCourante = page">

                {{ page }}

            </button>

        </li>

        <li
            class="page-item"
            :class="{ disabled: pageCourante === nombrePages }">

            <button
                class="page-link"
                @click="pageCourante++"
                :disabled="pageCourante === nombrePages">

                Suivant

            </button>

        </li>

    </ul>

</nav>

    </div>

</div>

<ConfirmModal
    :show="showModal"
    title="Suppression d'une actualité"
    message="Voulez-vous vraiment supprimer cette actualité ? Cette action est irréversible."
    confirmText="Supprimer"
    cancelText="Annuler"
    confirmClass="btn-danger"
    @confirm="supprimerActualite"
    @cancel="showModal = false"
/>

<ToastNotification
    :show="showToast"
    :message="toastMessage"
    :type="toastType"
    @close="showToast = false"
/>
</template>
```
