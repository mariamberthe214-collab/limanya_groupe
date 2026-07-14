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
              <h4>Forages hydrauliques</h4>
              <p>
                Réalisation de forages adaptés aux besoins des ménages,
                entreprises, collectivités et exploitations agricoles.
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
              🌞
            </div>

            <div>
              <h4>Pompes solaires</h4>
              <p>
                Installation de systèmes autonomes utilisant l'énergie
                solaire pour l'approvisionnement en eau.
              </p>
            </div>

          </div>


          <div class="service-item">

            <div class="icon">
              ⚡
            </div>

            <div>
              <h4>Pompes électriques</h4>
              <p>
                Solutions de pompage électrique fiables pour différents
                types d'installations.
              </p>
            </div>

          </div>


          <div class="service-item">

            <div class="icon">
              🔋
            </div>

            <div>
              <h4>Systèmes hybrides</h4>

              <p>
                Combinaison solaire et électrique pour garantir la
                disponibilité permanente de l'eau.
              </p>

            </div>

          </div>


          <div class="service-item">

            <div class="icon">
              🏗️
            </div>

            <div>
              <h4>Châteaux d'eau</h4>

              <p>
                Conception et installation d'ouvrages de stockage et
                distribution d'eau.
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
                Entretien, réparation et amélioration des installations
                existantes.
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

    <div class="text-center mb-5">

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
</style>
