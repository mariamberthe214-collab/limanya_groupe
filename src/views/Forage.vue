<script setup>
import { reactive, ref } from 'vue'
import api from '../services/api'

const form = reactive({
  name: '',
  email: '',
  phone: '',
  project: '',
  message: '',
})

const submitted = ref(false)

const submitForm = async () => {
  try {
    await api.post('/devis', {
      nom: form.name,
      email: form.email,
      telephone: form.phone,
      service: form.project,
      message: form.message,
      statut: 'nouveau',
    })

    submitted.value = true

    setTimeout(() => {
      submitted.value = false
      form.name = ''
      form.email = ''
      form.phone = ''
      form.project = ''
      form.message = ''
    }, 3000)
  } catch (error) {
    console.error(error)
    alert('Une erreur est survenue lors de l’envoi de votre demande. Veuillez réessayer.')
  }
}

const advantages = [
  'Équipements de forage modernes et performants',
  'Études du sous-sol précises avant chantier',
  'Intervention rapide et respect des délais',
  'Expertise en milieu urbain et rural',
]
</script>

<template>
  <section class="page-hero py-5">
    <div class="container py-4">
      <div class="row align-items-center gy-4">
        <div class="col-lg-7">
          <span class="eyebrow">Forage &amp; études géophysiques</span>
          <h1 class="display-5 fw-bold mt-3">Forage d&apos;eau professionnel et solutions géotechniques complètes</h1>
          <p class="lead mt-4">Limanya Groupe réalise des forages de puits, des études géophysiques et des prestations de génie civil avec un engagement qualité durable.</p>
          <div class="d-flex flex-wrap gap-3 mt-4">
            <a href="#devis" class="btn btn-primary btn-lg">Demander un devis</a>
            <router-link to="/contacts" class="btn btn-outline-light btn-lg">Nous contacter</router-link>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="hero-media ratio ratio-4x3">
            <img src="/images/service-forage.png" alt="Foreuse en activité sur un chantier de forage d'eau" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-6">
          <h2>Nos services de forage</h2>
          <p class="text-muted">Forage d’eau, assistance technique, installation d’équipements et maintenance de réseaux hydrauliques.</p>
          <ul class="list-unstyled mt-4">
            <li class="mb-3"><strong>Forage vertical et horizontal</strong> pour puits domestiques et industriels.</li>
            <li class="mb-3"><strong>Études géophysiques</strong> : résistivité, sismique et cyber-sonde pour un diagnostic fiable.</li>
            <li class="mb-3"><strong>Équipement de forage</strong> : pompes, tuyauterie, têtes de puits, coffres et supports techniques.</li>
            <li><strong>Assistance chantier</strong> : supervision de chantier, tests de débit et mise en service.</li>
          </ul>
        </div>
        <div class="col-lg-6">
          <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm">
            <img src="/images/service-forage.png" alt="Forage chantier" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5" id="devis">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-6">
          <h2>Avantages de Limanya Groupe</h2>
          <p class="text-muted">Nous combinons la technique, le terrain et le service pour des solutions robustes et efficaces.</p>
          <ul class="list-group list-group-flush mt-4">
            <li class="list-group-item">Equipe d’ingénieurs et techniciens expérimentés</li>
            <li class="list-group-item">Respect des règles de sécurité et des normes locales</li>
            <li class="list-group-item">Tarification claire et accompagnement de A à Z</li>
            <li class="list-group-item">Maintien et suivi après livraison du chantier</li>
          </ul>
        </div>
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm p-4 h-100">
            <div class="card-body">
              <h3 class="h5 mb-4">Formulaire de demande de devis</h3>
              <form @submit.prevent="submitForm">
                <div class="mb-3">
                  <label class="form-label">Nom complet</label>
                  <input v-model="form.name" type="text" class="form-control" placeholder="Votre nom" required />
                </div>
                <div class="mb-3 row g-3">
                  <div class="col-sm-6">
                    <label class="form-label">Email</label>
                    <input v-model="form.email" type="email" class="form-control" placeholder="email@exemple.com" required />
                  </div>
                  <div class="col-sm-6">
                    <label class="form-label">Téléphone</label>
                    <input v-model="form.phone" type="tel" class="form-control" placeholder="+229 00 00 00 00" required />
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Type de projet</label>
                  <select v-model="form.project" class="form-select" required>
                    <option value="" disabled>Choisissez un service</option>
                    <option>Forage d’eau</option>
                    <option>Étude géophysique</option>
                    <option>BTP & infrastructure</option>
                    <option>Assainissement</option>
                    <option>Location de matériel</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Message</label>
                  <textarea v-model="form.message" class="form-control" rows="4" placeholder="Décrivez votre projet"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Envoyer ma demande</button>
              </form>
              <div v-if="submitted" class="alert alert-success mt-4" role="alert">
                Merci, votre demande a bien été reçue. Nous vous recontacterons rapidement.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <span class="eyebrow text-uppercase">Équipements et performance</span>
        <h2>Matériel adapté à chaque type de forage</h2>
      </div>
      <div class="row gy-4">
        <div class="col-md-6 col-lg-3" v-for="(item, index) in advantages" :key="index">
          <div class="card border-0 shadow-sm h-100 p-4 text-center">
            <div class="service-icon mx-auto mb-3"><i class="bi bi-check-lg"></i></div>
            <p class="mb-0">{{ item }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.page-hero h1 {
  max-width: 700px;
}
</style>
