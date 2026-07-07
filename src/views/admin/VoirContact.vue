<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'

const route = useRoute()
const router = useRouter()

const contact = ref({})
const afficherModal = ref(false)

const chargerContact = async () => {
    try {
        const response = await api.get(`/contacts/${route.params.id}`)
        contact.value = response.data

        // Marquer comme lu
        if (contact.value.lu == 0) {
            await api.put(`/contacts/${route.params.id}`, {
                ...contact.value,
                lu: 1
            })
        }

    } catch (error) {
        console.error(error)
    }
}

const ouvrirConfirmation = () => {

    afficherModal.value = true

}

const supprimer = async () => {

    try {

        await api.delete(`/contacts/${route.params.id}`)

        afficherModal.value = false

        router.push('/admin/contacts')

    } catch (error) {

        console.error(error)

    }

}

onMounted(chargerContact)
</script>

<template>

<div class="container py-5" style="max-width: 760px;">

    <RouterLink to="/admin/contacts" class="text-muted d-inline-flex align-items-center mb-3" style="gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour aux messages
    </RouterLink>

    <h2 class="fw-bold mb-1">
        <i class="bi bi-envelope-open text-amber me-2"></i>Détails du message
    </h2>
    <p class="text-muted mb-4">Message reçu via le formulaire de contact.</p>

    <div class="card">

        <div class="card-body p-4 p-md-5">

            <div class="row g-4 mb-4">
                <div class="col-sm-6">
                    <div class="detail-label">Nom</div>
                    <div class="detail-value">{{ contact.nom }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ contact.email }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Téléphone</div>
                    <div class="detail-value">{{ contact.telephone }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Sujet</div>
                    <div class="detail-value">{{ contact.sujet }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Date</div>
                    <div class="detail-value">{{ contact.created_at }}</div>
                </div>
            </div>

            <div class="message-box mb-4">
                <div class="detail-label mb-2">Message</div>
                <p class="mb-0">{{ contact.message }}</p>
            </div>

            <div class="d-flex gap-2">
            <RouterLink
                to="/admin/contacts"
                class="btn btn-secondary">

                <i class="bi bi-arrow-left me-1"></i>Retour

            </RouterLink>

            <button
                class="btn btn-danger"
                @click="ouvrirConfirmation">

                <i class="bi bi-trash me-1"></i>Supprimer

            </button>
            </div>

        </div>

    </div>

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

</template>
