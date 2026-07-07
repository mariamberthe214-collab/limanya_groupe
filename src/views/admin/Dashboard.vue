<template>
  <div class="container py-5">

    <h2 class="fw-bold mb-1">
      Tableau de bord
    </h2>

    <p class="text-muted mb-4">
      Bienvenue dans l'administration de LIMANYA GROUPE.
    </p>

    <div class="row">

    <div class="col-lg-3 col-md-6 mb-4">

        <CardStat
            titre="Actualités"
            :nombre="stats.actualites"
            icone="📰"
            couleur="primary"
        />

    </div>

    <div class="col-lg-3 col-md-6 mb-4">

        <CardStat
            titre="Réalisations"
            :nombre="stats.realisations"
            icone="🏗"
            couleur="success"
        />

    </div>

    <div class="col-lg-3 col-md-6 mb-4">

        <CardStat
            titre="Devis"
            :nombre="stats.devis"
            icone="📄"
            couleur="warning"
        />

    </div>

    <div class="col-lg-3 col-md-6 mb-4">

        <CardStat
            titre="Messages"
            :nombre="stats.contacts"
            icone="📩"
            couleur="danger"
        />

    </div>

</div>

<div class="card shadow border-0 mt-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-newspaper text-amber me-2"></i> Dernières actualités
        </h5>

    </div>

    <div class="card-body">

        <table class="table table-hover align-middle">

            <thead>

                <tr>

                    <th>Date</th>
                    <th>Titre</th>
                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

                <tr
                    v-for="item in dernieresActualites"
                    :key="item.id">

                    <td>{{ formatDate(item.date_publication) }}</td>

                    <td>{{ item.titre }}</td>

                    <td>

                        <RouterLink
                            class="btn btn-sm btn-primary"
                            :to="'/admin/actualites/modifier/' + item.id">

                            Modifier

                        </RouterLink>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

  </div>

</div>
<div class="container py-5">

    <div class="card shadow border-0 mt-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="bi bi-envelope text-amber me-2"></i> Derniers messages
            </h5>

        </div>

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Date</th>
                        <th>Nom</th>
                        <th>Sujet</th>
                        <th>Email</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <tr
                        v-for="item in derniersContacts"
                        :key="item.id">

                        <td>{{ formatDate(item.created_at) }}</td>

                        <td>{{ item.nom }}</td>

                        <td>{{ item.sujet }}</td>

                        <td>{{ item.email }}</td>

                        <td>

                            <RouterLink
                                class="btn btn-sm btn-primary"
                                :to="'/admin/contacts/' + item.id">

                                Voir

                            </RouterLink>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

      </div>
  </div>

</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import CardStat from '../../components/admin/CardStat.vue'

const dernieresActualites = ref([])
const derniersContacts = ref([])


const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR')
}
const stats = ref({
  actualites: 0,
  realisations: 0,
  devis: 0,
  contacts: 0
})

const chargerStatistiques = async () => {
  try {
    const response = await api.get('/dashboard')
    stats.value = response.data
  } catch (error) {
    console.error(error)
  }
}
const chargerDernieresActualites = async () => {
  try {
    const response = await api.get('/actualites?limit=5')
    dernieresActualites.value = response.data
  } catch (error) {
    console.error(error)
  }
}

const chargerDerniersContacts = async () => {
  try {
    const response = await api.get('/contacts?limit=5')
    derniersContacts.value = response.data
  } catch (error) {
    console.error(error)
  }
}



onMounted(() => {
  chargerStatistiques()
  chargerDernieresActualites()
  chargerDerniersContacts()
})
</script>
