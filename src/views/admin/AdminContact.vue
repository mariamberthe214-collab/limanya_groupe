<script setup>
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import api from '../../services/api'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'
import ToastNotification from '../../components/admin/ToastNotification.vue'

const contacts = ref([])
const recherche = ref('')
const afficherModal = ref(false)
const toast = ref(null)
const toastMessage = ref('')
const toastType = ref('success')
const showToast = ref(false)

const pageCourante = ref(1)

const parPage = 10
const afficherToast = (message, type = 'success') => {

  toastMessage.value = message
  toastType.value = type
  showToast.value = true

  setTimeout(() => {

    showToast.value = false

  }, 3000)

}

const chargerContacts = async () => {
    try {
        const response = await api.get('/contacts')
        contacts.value = response.data
    } catch (error) {
        console.error(error)
    }
}

const contactsFiltres = computed(() => {

    return contacts.value.filter(contact =>

        contact.nom.toLowerCase().includes(recherche.value.toLowerCase()) ||

        contact.email.toLowerCase().includes(recherche.value.toLowerCase()) ||

        (contact.sujet ?? '').toLowerCase().includes(recherche.value.toLowerCase())

    )

})

const totalPages = computed(() =>

    Math.ceil(contactsFiltres.value.length / parPage)

)

const contactsPagine = computed(() => {

    const debut = (pageCourante.value - 1) * parPage

    return contactsFiltres.value.slice(

        debut,

        debut + parPage

    )

})

const contactASupprimer = ref(null)

const ouvrirConfirmation = (id) => {

    contactASupprimer.value = id
    afficherModal.value = true

}

const supprimer = async () => {

    try {

        await api.delete(`/contacts/${contactASupprimer.value}`)

        afficherModal.value = false
        afficherToast('Message supprimé avec succès.', 'success')
        chargerContacts()

    } catch (error) {

        console.error(error)
        afficherModal.value = false
        afficherToast("Erreur lors de la suppression du message.", 'error')

    }

}




onMounted(chargerContacts)
</script>

<template>

<div class="container py-4">

    <h2 class="fw-bold mb-1"><i class="bi bi-envelope text-amber me-2"></i>Messages reçus</h2>
    <p class="text-muted mb-4">Consultez les messages envoyés via le formulaire de contact.</p>
    <div class="row mb-3">

        <div class="col-md-4 position-relative">

            <i class="bi bi-search position-absolute" style="left: 28px; top: 50%; transform: translateY(-50%); color: #b3ab99;"></i>
            <input
                v-model="recherche"
                class="form-control ps-5"
                placeholder="Rechercher un message...">

        </div>

    </div>

    <div class="table-responsive">
    <table class="table table-hover align-middle">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Sujet</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            <tr
                v-for="contact in contactsPagine"
                :key="contact.id">

                <td>{{ contact.id }}</td>

                <td>{{ contact.nom }}</td>

                <td>{{ contact.sujet }}</td>

                <td>{{ contact.email }}</td>

                <td>{{ contact.telephone }}</td>

                <td>{{ contact.created_at }}</td>

                <td>

                    <span
                        v-if="contact.lu"
                        class="badge bg-success">

                        Lu

                    </span>

                    <span
                        v-else
                        class="badge bg-danger">

                        Non lu

                    </span>

                </td>

                <td>

                    <RouterLink
                        :to="`/admin/contacts/${contact.id}`"
                        class="btn btn-primary btn-sm me-2">

                        <i class="bi bi-eye me-1"></i>Voir

                    </RouterLink>

                    <button
                        class="btn btn-danger btn-sm"
                        @click="ouvrirConfirmation(contact.id)">

                        <i class="bi bi-trash"></i>

                    </button>

                </td>

            </tr>

        </tbody>

    </table>
    </div>
    <nav v-if="totalPages > 1">

        <ul class="pagination justify-content-center">

            <li
                class="page-item"
                :class="{ disabled: pageCourante === 1 }">

                <button
                    class="page-link"
                    @click="pageCourante--">

                    Précédent

                </button>

            </li>

            <li
                v-for="page in totalPages"
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
                :class="{ disabled: pageCourante === totalPages }">

                <button
                    class="page-link"
                    @click="pageCourante++">

                    Suivant

                </button>

            </li>

        </ul>

    </nav>

</div>

<ConfirmModal

    :show="afficherModal"

    title="Suppression"

    message="Voulez-vous vraiment supprimer ce message ?"

    confirm-text="Supprimer"

    cancel-text="Annuler"

    @confirm="supprimer"

    @cancel="afficherModal = false"

/>

<ToastNotification
  v-if="showToast"
  :message="toastMessage"
  :type="toastType"
/>    

</template>
