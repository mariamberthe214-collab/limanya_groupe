<script setup>
import { reactive, ref } from 'vue'
import api from '../services/api'

const form = reactive({
  nom: '',
  email: '',
  telephone: '',
  sujet: '',
  message: '',
})

const submitted = ref(false)

const submitContact = async () => {
  try {

    await api.post('/contacts', form)

    submitted.value = true

    form.nom = ''
    form.email = ''
    form.telephone = ''
    form.sujet = ''
    form.message = ''

    setTimeout(() => {
      submitted.value = false
    }, 3000)

  } catch (error) {
    console.error(error)
    alert("Erreur lors de l'envoi du message.")
  }
}
</script>

<template>
  <section class="contact-hero py-5 text-white text-center">
    <div class="container">
      <span class="eyebrow" style="color: #f6e7da;">Parlons de votre projet</span>
      <h1 class="display-5 fw-bold text-white mt-3">Contactez-nous</h1>
      <p class="lead text-white-75 mt-3">groupelimanya18@gmail.com &nbsp;•&nbsp; +225 21 50 04 33 27 &nbsp;•&nbsp; +225 07 77 04 70 57</p>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-5">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h2>Réseaux sociaux</h2>
            <p class="text-muted">Suivez-nous et contactez-nous via WhatsApp ou Facebook.</p>
            <ul class="list-unstyled mb-0">
              <li class="mb-3"><strong>Email :</strong> <a href="mailto:groupelimanya18@gmail.com">groupelimanya18@gmail.com</a></li>
              <li class="mb-3"><strong>Phone :</strong> +225 21 50 04 33 27</li>
              <li class="mb-3"><strong>Fixe :</strong> +225 07 98 44 93 60</li>
              <li><strong>Fixe :</strong> +225 07 77 04 70 57</li>
            </ul>
            <div class="mt-4">
              <a class="btn btn-success me-2" href="https://wa.me/0798449360" target="_blank">WhatsApp</a>
              <a class="btn btn-outline-primary" href="https://www.facebook.com/limanyagroup" target="_blank">Facebook</a>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h2>Formulaire de contact</h2>
            <form @submit.prevent="submitContact">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nom complet</label>
                  <input v-model="form.nom" type="text" class="form-control" placeholder="Votre nom" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Téléphone</label>
                  <input v-model="form.telephone" type="tel" class="form-control" placeholder="+225 07 77 04 70 57" required />
                </div>
              </div>
              <div class="row g-3 mt-3">
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input v-model="form.email" type="email" class="form-control" placeholder="email@exemple.com" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Objet</label>
                  <input v-model="form.sujet" type="text" class="form-control" placeholder="Devis, question, partenariat" />
                </div>
              </div>
              <div class="mt-3">
                <label class="form-label">Message</label>
                <textarea v-model="form.message" rows="4" class="form-control" placeholder="Expliquez votre projet"></textarea>
              </div>
              <button type="submit" class="btn btn-primary mt-4">Envoyer</button>
            </form>
            <div v-if="submitted" class="alert alert-success mt-4" role="alert">
              Merci ! Votre message a bien été envoyé. Nous revenons vers vous rapidement.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.contact-hero {
  min-height: 300px;
  display: flex;
  align-items: center;
  background: linear-gradient(135deg, #17140f 0%, #2c281f 100%);
  position: relative;
  overflow: hidden;
}
.contact-hero::before {
  content: '';
  position: absolute;
  top: -80px;
  right: -60px;
  width: 260px;
  height: 260px;
  background: radial-gradient(circle, rgba(191, 87, 28, 0.35), transparent 70%);
}
.contact-hero .container {
  position: relative;
  z-index: 1;
}
</style>
