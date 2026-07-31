<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import ConfirmModal from '../../components/admin/ConfirmModal.vue'
import { buildWhatsAppReplyLink } from '../../utils/whatsapp'

const route = useRoute()
const router = useRouter()

const devisItem = ref({})
const afficherModal = ref(false)

const chargerDevis = async () => {
    try {
        const response = await api.get(`/devis/${route.params.id}`)
        devisItem.value = response.data
    } catch (error) {
        console.error(error)
    }
}

const lienReponseWhatsApp = () => {
    const message = `Bonjour ${devisItem.value.nom}, merci pour votre demande de devis concernant "${devisItem.value.service || 'votre projet'}". `
    return buildWhatsAppReplyLink(devisItem.value.telephone, message)
}

const marquerTraite = async () => {
    try {
        const nouveauStatut = devisItem.value.statut === 'nouveau' ? 'traité' : 'nouveau'
        await api.put(`/devis/${route.params.id}`, { ...devisItem.value, statut: nouveauStatut })
        devisItem.value.statut = nouveauStatut
    } catch (error) {
        console.error(error)
    }
}

const ouvrirConfirmation = () => {
    afficherModal.value = true
}

const supprimer = async () => {
    try {
        await api.delete(`/devis/${route.params.id}`)
        afficherModal.value = false
        router.push('/admin/devis')
    } catch (error) {
        console.error(error)
    }
}

onMounted(chargerDevis)
</script>

<template>

<div class="container py-5" style="max-width: 760px;">

    <RouterLink to="/admin/devis" class="text-muted d-inline-flex align-items-center mb-3" style="gap:6px;">
        <i class="bi bi-arrow-left"></i> Retour aux devis
    </RouterLink>

    <h2 class="fw-bold mb-1">
        <i class="bi bi-file-earmark-text text-amber me-2"></i>Détails de la demande de devis
    </h2>
    <p class="text-muted mb-4">Demande reçue via le formulaire de devis du site.</p>

    <div class="card">

        <div class="card-body p-4 p-md-5">

            <div class="row g-4 mb-4">
                <div class="col-sm-6">
                    <div class="detail-label">Nom</div>
                    <div class="detail-value">{{ devisItem.nom }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ devisItem.email || '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Téléphone</div>
                    <div class="detail-value">{{ devisItem.telephone }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Service demandé</div>
                    <div class="detail-value">{{ devisItem.service || '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Date</div>
                    <div class="detail-value">{{ devisItem.created_at }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-label">Statut</div>
                    <div class="detail-value">
                        <span class="badge" :class="devisItem.statut === 'nouveau' ? 'bg-warning text-dark' : 'bg-success'">
                            {{ devisItem.statut || 'nouveau' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="message-box mb-4">
                <div class="detail-label mb-2">Message du client</div>
                <p class="mb-0">{{ devisItem.message || 'Aucun message renseigné.' }}</p>
            </div>

            <div class="d-flex flex-wrap gap-2">
            <RouterLink
                to="/admin/devis"
                class="btn btn-secondary">

                <i class="bi bi-arrow-left me-1"></i>Retour

            </RouterLink>

            <a
                v-if="devisItem.telephone"
                :href="lienReponseWhatsApp()"
                target="_blank"
                class="btn btn-success">

                <i class="bi bi-whatsapp me-1"></i>Répondre via WhatsApp

            </a>

            <button
                class="btn btn-outline-primary"
                @click="marquerTraite">

                <i class="bi bi-arrow-repeat me-1"></i>Marquer {{ devisItem.statut === 'nouveau' ? 'traité' : 'nouveau' }}

            </button>

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

    message="Voulez-vous vraiment supprimer ce devis ?"

    confirm-text="Supprimer"

    cancel-text="Annuler"

    @confirm="supprimer"

    @cancel="afficherModal = false"

/>

</template>

<style scoped>
.detail-label {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #6f685a;
    margin-bottom: 0.2rem;
}
.detail-value {
    font-size: 1rem;
    color: #2c281f;
}
.message-box {
    background: #f6f4ee;
    border-radius: 0.75rem;
    padding: 1.25rem 1.5rem;
}
</style>