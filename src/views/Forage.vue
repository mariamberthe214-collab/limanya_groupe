<script setup>
import { reactive, ref, onMounted } from 'vue'
import api from '../services/api'
import { buildWhatsAppLink } from '../utils/whatsapp'
import { getImageUrl } from '../utils/images'

const form = reactive({
  name: '',
  email: '',
  phone: '',
  project: '',
  message: '',
})

const submitted = ref(false)
const lastWhatsAppLink = ref('')

const realisations = ref([])
const chargementRealisations = ref(true)

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' })
}

const chargerRealisations = async () => {
  chargementRealisations.value = true
  try {
    const response = await api.get('/realisations', { params: { categorie: 'Forages Hydrauliques' } })
    realisations.value = response.data
  } catch (error) {
    console.error(error)
  } finally {
    chargementRealisations.value = false
  }
}

onMounted(chargerRealisations)

const buildRecapMessage = (data) => {
  const lignes = [
    `Bonjour LIMANYA Groupe, je viens de faire une demande de devis via votre site :`,
    `Nom : ${data.name}`,
    data.project ? `Service souhaité : ${data.project}` : null,
    `Téléphone : ${data.phone}`,
    `Message : ${data.message}`,
  ].filter(Boolean)
  return lignes.join('\n')
}

const submitForm = async () => {
  try {
    const recapMessage = buildRecapMessage(form)

    await api.post('/devis', {
      nom: form.name,
      email: form.email,
      telephone: form.phone,
      service: form.project,
      message: form.message,
      statut: 'nouveau',
    })

    lastWhatsAppLink.value = buildWhatsAppLink(recapMessage)
    submitted.value = true

    form.name = ''
    form.email = ''
    form.phone = ''
    form.project = ''
    form.message = ''
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
  <section class="forage-hero">

    <div class="overlay"></div>

    <div class="container">

      <div class="row align-items-center min-vh-100">

        <div class="col-lg-7">

          <span class="hero-badge">
            💧 Spécialiste du forage hydraulique
          </span>

          <h1 class="display-3 fw-bold mt-4">
            Des solutions fiables pour
            <span>l'accès à l'eau potable</span>
          </h1>

          <p class="lead mt-4">
            LIMANYA Groupe accompagne les particuliers, entreprises,
            collectivités et exploitations agricoles dans la réalisation
            de forages hydrauliques, d'études géophysiques et
            d'installations de pompage adaptées à chaque besoin.
          </p>

          <div class="mt-5 d-flex flex-wrap gap-3">

            <RouterLink
              to="/contacts"
              class="btn btn-warning btn-lg">

              Demander un devis

            </RouterLink>

            <RouterLink
              to="/realisations-btp-services-de-construction"
              class="btn btn-outline-light btn-lg">

              Nos réalisations

            </RouterLink>

          </div>

        </div>

      </div>

    </div>

  </section>

  <section class="prestations-section py-5">

  <div class="container">

    <div class="row align-items-center mb-5">

      <div class="col-lg-5">

        <span class="section-tag">
          Nos expertises
        </span>

        <h2 class="mt-3">
          Des solutions complètes pour vos projets hydrauliques
        </h2>

        <p>
          LIMANYA Groupe intervient dans toutes les étapes d'un projet
          d'accès à l'eau : étude du terrain, forage, installation des
          équipements de pompage et maintenance des ouvrages.
        </p>

      </div>


      <div class="col-lg-7">

        <div class="services-list">


          <div class="service-item">

            <div class="icon">
              💧
            </div>

            <div>
              <h4>Forages villageois</h4>
              <p>
                Points d'eau potable pour les communautés rurales et
                les villages.
              </p>
            </div>

          </div>


          <div class="service-item">

            <div class="icon">
              🌾
            </div>

            <div>
              <h4>Forages agricoles</h4>
              <p>
                Solutions d'irrigation et d'approvisionnement en eau
                pour l'agriculture.
              </p>
            </div>

          </div>


          <div class="service-item">

            <div class="icon">
              🏭
            </div>

            <div>
              <h4>Forages industriels</h4>
              <p>
                Approvisionnement en eau adapté aux besoins des sites
                industriels et entreprises.
              </p>
            </div>

          </div>


          <div class="service-item">

            <div class="icon">
              📊
            </div>

            <div>
              <h4>Essais de pompage</h4>
              <p>
                Mesure du débit et de la capacité du forage pour
                garantir sa performance dans le temps.
              </p>
            </div>

          </div>


          <div class="service-item">

            <div class="icon">
              🌍
            </div>

            <div>
              <h4>Études géophysiques</h4>
              <p>
                Analyse du sous-sol pour identifier les meilleures zones
                d'implantation des forages.
              </p>
            </div>

          </div>


          <div class="service-item">

            <div class="icon">
              🛠️
            </div>

            <div>
              <h4>Réhabilitation & maintenance</h4>

              <p>
                Entretien, réparation et amélioration des forages
                existants pour prolonger leur durée de vie.
              </p>

            </div>

          </div>


        </div>

      </div>


    </div>

  </div>

</section>

<section class="method-section py-5">

  <div class="container">

    <div class="text-center mb-5" v-reveal>

      <span class="section-tag">
        Notre méthode
      </span>

      <h2 class="mt-3">
        Un accompagnement complet de l'étude à la mise en service
      </h2>

      <p class="method-intro">
        LIMANYA Groupe vous accompagne à chaque étape afin de garantir
        des installations fiables, durables et adaptées à vos besoins.
      </p>

    </div>


    <div class="steps-container">


      <div class="step-item">

        <div class="step-number">
          01
        </div>

        <div class="step-content">

          <div class="step-icon">
            🌍
          </div>

          <h4>
            Étude du terrain
          </h4>

          <p>
            Analyse des besoins et étude de la zone d'intervention
            avant le démarrage des travaux.
          </p>

        </div>

      </div>



      <div class="step-item">

        <div class="step-number">
          02
        </div>

        <div class="step-content">

          <div class="step-icon">
            🔎
          </div>

          <h4>
            Recherche d'eau
          </h4>

          <p>
            Réalisation d'études géophysiques pour identifier
            les meilleures zones de captage.
          </p>

        </div>

      </div>



      <div class="step-item">

        <div class="step-number">
          03
        </div>

        <div class="step-content">

          <div class="step-icon">
            🛠️
          </div>

          <h4>
            Réalisation du forage
          </h4>

          <p>
            Exécution des travaux avec des équipements adaptés
            et respect des normes techniques.
          </p>

        </div>

      </div>



      <div class="step-item">

        <div class="step-number">
          04
        </div>

        <div class="step-content">

          <div class="step-icon">
            ⚡
          </div>

          <h4>
            Installation du pompage
          </h4>

          <p>
            Mise en place de pompes solaires, électriques ou hybrides
            selon les besoins du projet.
          </p>

        </div>

      </div>



      <div class="step-item">

        <div class="step-number">
          05
        </div>

        <div class="step-content">

          <div class="step-icon">
            ✅
          </div>

          <h4>
            Mise en service & suivi
          </h4>

          <p>
            Contrôle final, accompagnement et maintenance des ouvrages.
          </p>

        </div>

      </div>


    </div>


  </div>

</section>

<section class="py-5 bg-light">

  <div class="container">

    <div class="text-center mb-5" v-reveal>
      <span class="eyebrow">Nos réalisations</span>
      <h2 class="mt-3">Forages et ouvrages hydrauliques réalisés</h2>
    </div>

    <div v-if="chargementRealisations" class="text-center text-muted py-5">
      <span class="spinner-border spinner-border-sm me-2"></span>Chargement...
    </div>

    <div v-else-if="realisations.length === 0" class="text-center text-muted py-5">
      <i class="bi bi-images fs-2 d-block mb-2"></i>
      Nos réalisations de forage seront bientôt visibles ici.
    </div>

    <div v-else class="row g-4">
      <div class="col-md-6 col-lg-4" v-for="projet in realisations" :key="projet.id">
        <div class="card border-0 shadow-sm h-100 overflow-hidden">
          <div class="ratio ratio-4x3 bg-secondary bg-opacity-10">
            <video
              v-if="projet.video"
              :src="getImageUrl(projet.video)"
              :poster="projet.image ? getImageUrl(projet.image) : ''"
              controls
              class="w-100 h-100" style="object-fit:cover;"></video>
            <img
              v-else-if="projet.image"
              :src="getImageUrl(projet.image)"
              :alt="projet.titre"
              class="w-100 h-100 object-fit-cover">
            <div v-else class="d-flex align-items-center justify-content-center text-muted">
              <i class="bi bi-image fs-1"></i>
            </div>
          </div>
          <div class="p-4">
            <h5>{{ projet.titre }}</h5>
            <p class="text-muted small mb-2" v-if="projet.lieu || projet.date_realisation">
              <i class="bi bi-geo-alt me-1" v-if="projet.lieu"></i>{{ projet.lieu }}
              <span v-if="projet.lieu && projet.date_realisation"> · </span>
              <i class="bi bi-calendar3 me-1" v-if="projet.date_realisation"></i>{{ formatDate(projet.date_realisation) }}
            </p>
            <p class="text-muted">{{ projet.description }}</p>
          </div>
        </div>
      </div>
    </div>

  </div>

</section>

<section class="devis-section py-5">

  <div class="container">

    <div class="row justify-content-center">

      <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">

          <div class="text-center mb-4">
            <span class="section-tag">Demande de devis</span>
            <h2 class="mt-3">Un projet en tête ? Parlons-en</h2>
            <p class="text-muted">Remplissez ce formulaire, nous vous répondrons rapidement avec une proposition adaptée.</p>
          </div>

          <form @submit.prevent="submitForm">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nom complet</label>
                <input v-model="form.name" type="text" class="form-control" placeholder="Votre nom" required />
              </div>
              <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input v-model="form.phone" type="tel" class="form-control" placeholder="+225 07 77 04 70 57" required />
              </div>
            </div>
            <div class="row g-3 mt-3">
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input v-model="form.email" type="email" class="form-control" placeholder="email@exemple.com" />
              </div>
              <div class="col-md-6">
                <label class="form-label">Type de projet</label>
                <input v-model="form.project" type="text" class="form-control" placeholder="Forage, étude géophysique..." />
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label">Message</label>
              <textarea v-model="form.message" rows="4" class="form-control" placeholder="Décrivez votre projet"></textarea>
            </div>
            <button type="submit" class="btn btn-warning btn-lg mt-4">Envoyer ma demande</button>
          </form>

          <div v-if="submitted" class="alert alert-success mt-4" role="alert">
            <p class="mb-3">Merci ! Votre demande a bien été envoyée. Nous revenons vers vous rapidement.</p>
            <a :href="lastWhatsAppLink" target="_blank" class="btn btn-success btn-sm">
              <i class="bi bi-whatsapp me-1"></i>Continuer la conversation sur WhatsApp
            </a>
          </div>

        </div>

      </div>

    </div>

  </div>

</section>
</template>

<style scoped>

.method-section{

    background:#f8f8f8;

}


.method-section h2{

    font-size:42px;

    font-weight:700;

}


.method-intro{

    max-width:700px;

    margin:auto;

    color:#666;

}



.steps-container{

    display:flex;

    justify-content:space-between;

    gap:25px;

    position:relative;

}



.steps-container::before{

    content:"";

    position:absolute;

    top:45px;

    left:8%;

    right:8%;

    height:3px;

    background:#bf571c;

}



.step-item{

    flex:1;

    position:relative;

    text-align:center;

}



.step-number{

    width:90px;

    height:90px;

    background:#bf571c;

    color:white;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:28px;

    font-weight:700;

    margin:auto;

    position:relative;

    z-index:2;

}



.step-content{

    margin-top:30px;

    background:white;

    padding:25px 20px;

    border-radius:15px;

    min-height:260px;

    height:auto;

    box-shadow:0 10px 30px rgba(0,0,0,.06);

}



.step-icon{

    font-size:40px;

    margin-bottom:15px;

}


.step-content h4{

    font-weight:700;

}


@media(max-width:992px){

    .steps-container{

        flex-direction:column;

    }


    .steps-container::before{

        display:none;

    }

}

.prestations-section{

    background:#fff;

}


.section-tag{

    display:inline-block;

    background:#bf571c;

    color:white;

    padding:8px 18px;

    border-radius:30px;

    font-weight:600;

}


.prestations-section h2{

    font-size:42px;

    font-weight:700;

    color:#1b1b1b;

}


.prestations-section p{

    color:#666;

    line-height:1.8;

}



.services-list{

    display:flex;

    flex-direction:column;

    gap:20px;

}



.service-item{

    display:flex;

    gap:20px;

    padding:25px;

    background:#fafafa;

    border-left:4px solid #bf571c;

    border-radius:12px;

    transition:.3s;

}



.service-item:hover{

    transform:translateX(10px);

    box-shadow:0 15px 30px rgba(0,0,0,.08);

}



.service-item .icon{

    font-size:35px;

}



.service-item h4{

    margin-bottom:8px;

    font-weight:700;

}
.page-hero h1 {
  max-width: 700px;
}

.forage-hero{

    position:relative;

    min-height:100vh;

    background:url("/images/service-forage.png") center center/cover no-repeat;

    display:flex;

    align-items:center;

}

.overlay{

    position:absolute;

    inset:0;

    background:rgba(0,0,0,.60);

}

.forage-hero .container{

    position:relative;

    z-index:2;

}

.hero-badge{

    display:inline-block;

    background:#bf571c;

    color:#fff;

    padding:10px 22px;

    border-radius:50px;

    font-weight:600;

}

.forage-hero h1{

    color:#fff;

    line-height:1.2;

}

.forage-hero h1 span{

    color:#ffb347;

}

.forage-hero p{

    color:#f5f5f5;

    max-width:700px;

}

.btn-warning{

    background:#bf571c;

    border:none;

}

.btn-warning:hover{

    background:#a64815;

}

.btn-outline-light:hover{

    color:#000;

}

.devis-section{

    background:#f8f8f8;

}
</style>
