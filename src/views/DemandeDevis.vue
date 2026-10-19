<script setup>
import { reactive, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'
import { buildWhatsAppLink } from '../utils/whatsapp'

const route = useRoute()
const envoiEnCours = ref(false)
const erreur = ref('')
const confirmation = ref(false)
const lienWhatsApp = ref('')

const services = [
  'Forages Hydrauliques',
  'Études Géophysiques',
  'Assainissement',
  'BTP & Génie Civil',
  'Énergies renouvelables',
  'Équipements hydrauliques',
  'Autre projet',
]

const form = reactive({
  nom: '',
  email: '',
  telephone: '',
  service: '',
  message: '',
})

watch(
  () => route.query.service,
  (service) => {
    if (typeof service === 'string' && services.includes(service)) {
      form.service = service
    }
  },
  { immediate: true },
)

watch(
  () => route.query.message,
  (message) => {
    if (typeof message === 'string') form.message = message
  },
  { immediate: true },
)

const envoyerDemande = async () => {
  if (envoiEnCours.value) return

  erreur.value = ''
  confirmation.value = false
  envoiEnCours.value = true

  const messageWhatsApp = [
    'Bonjour LIMANYA Groupe, je viens d’envoyer une demande de devis via votre site :',
    `Nom : ${form.nom}`,
    `Service souhaité : ${form.service}`,
    `Téléphone : ${form.telephone}`,
    form.email ? `E-mail : ${form.email}` : null,
    form.message ? `Message : ${form.message}` : null,
  ].filter(Boolean).join('\n')

  try {
    await api.post('/devis', {
      nom: form.nom,
      email: form.email,
      telephone: form.telephone,
      service: form.service,
      message: form.message,
      statut: 'nouveau',
    })

    lienWhatsApp.value = buildWhatsAppLink(messageWhatsApp)
    confirmation.value = true
    form.nom = ''
    form.email = ''
    form.telephone = ''
    form.message = ''
  } catch (error) {
    console.error(error)
    erreur.value = error.response?.data?.message || 'Impossible d’envoyer votre demande pour le moment. Veuillez réessayer.'
  } finally {
    envoiEnCours.value = false
  }
}
</script>

<template>
  <section class="quote-hero py-5 text-white">
    <div class="container py-4">
      <RouterLink to="/" class="quote-back-link">
        <i class="bi bi-arrow-left me-2"></i>Accueil
      </RouterLink>
      <span class="quote-eyebrow d-block mt-4">Votre projet</span>
      <h1 class="display-5 fw-bold mt-2">Demander un devis</h1>
      <p class="lead mt-3 mb-0">Décrivez votre besoin. Notre équipe vous recontactera rapidement.</p>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
          <div class="quote-form-panel p-4 p-md-5">
            <div v-if="erreur" class="alert alert-danger" role="alert">{{ erreur }}</div>

            <div v-if="confirmation" class="alert alert-success" role="status">
              <h2 class="h5">Votre demande a bien été envoyée.</h2>
              <p class="mb-3">Notre équipe vous répondra dès que possible.</p>
              <a :href="lienWhatsApp" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-sm">
                <i class="bi bi-whatsapp me-1"></i>Continuer sur WhatsApp
              </a>
            </div>

            <form v-else @submit.prevent="envoyerDemande">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="quote-name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                  <input id="quote-name" v-model="form.nom" class="form-control" autocomplete="name" required />
                </div>
                <div class="col-md-6">
                  <label for="quote-phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                  <input id="quote-phone" v-model="form.telephone" type="tel" class="form-control" autocomplete="tel" required />
                </div>
                <div class="col-md-6">
                  <label for="quote-email" class="form-label">E-mail</label>
                  <input id="quote-email" v-model="form.email" type="email" class="form-control" autocomplete="email" />
                </div>
                <div class="col-md-6">
                  <label for="quote-service" class="form-label">Service souhaité <span class="text-danger">*</span></label>
                  <select id="quote-service" v-model="form.service" class="form-select" required>
                    <option disabled value="">Choisir un service</option>
                    <option v-for="service in services" :key="service" :value="service">{{ service }}</option>
                  </select>
                </div>
                <div class="col-12">
                  <label for="quote-message" class="form-label">Détails du projet</label>
                  <textarea id="quote-message" v-model="form.message" rows="5" class="form-control" placeholder="Décrivez votre besoin, le lieu ou les délais souhaités."></textarea>
                </div>
                <div class="col-12 d-flex flex-wrap gap-3 align-items-center mt-4">
                  <button type="submit" class="btn btn-primary btn-lg" :disabled="envoiEnCours">
                    <span v-if="envoiEnCours" class="spinner-border spinner-border-sm me-2"></span>
                    {{ envoiEnCours ? 'Envoi en cours...' : 'Envoyer ma demande' }}
                  </button>
                  <RouterLink to="/contacts" class="quote-contact-link">J’ai une question générale</RouterLink>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.quote-hero {
  background: linear-gradient(135deg, #17140f, #343027);
}

.quote-back-link {
  color: rgba(255, 255, 255, 0.76);
  text-decoration: none;
}

.quote-back-link:hover {
  color: #fff;
}

.quote-eyebrow {
  color: var(--lg-amber);
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
}

.quote-form-panel {
  background: #fff;
  border: 1px solid #ece7dd;
  border-radius: 0.75rem;
  box-shadow: 0 12px 32px rgba(34, 29, 20, 0.06);
}

.quote-contact-link {
  color: var(--lg-ink-soft);
  text-decoration: none;
}

.quote-contact-link:hover {
  color: var(--lg-amber-dark);
  text-decoration: underline;
}
</style>
