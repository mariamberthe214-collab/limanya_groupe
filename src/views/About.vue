<script setup>
const values = [
  {
    icon: 'bi-droplet-fill',
    title: 'Spécialiste du forage hydraulique',
    text: 'Notre expertise principale est la réalisation de forages hydrauliques pour l’alimentation en eau potable des particuliers, entreprises et collectivités.'
  },
  {
    icon: 'bi-globe-africa',
    title: 'Études géophysiques fiables',
    text: 'Nous réalisons des études géophysiques afin d’identifier les zones les plus favorables avant chaque forage.'
  },
  {
    icon: 'bi-tools',
    title: 'Équipements performants',
    text: 'Nous disposons d’équipements modernes permettant d’assurer des interventions efficaces et de qualité.'
  },
  {
    icon: 'bi-shield-check',
    title: 'Qualité & Satisfaction',
    text: 'Nous nous engageons à livrer des travaux conformes aux normes tout en respectant les délais convenus.'
  }
]

const services = [
  {
    icon: "bi-droplet",
    title: "Forages Hydrauliques",
    text: "Forages villageois, agricoles, industriels, essais de pompage, réhabilitation et maintenance."
  },
  {
    icon: "bi-globe-africa",
    title: "Études Géophysiques",
    text: "Recherche d'eau, études géophysiques et implantation des forages."
  },
  {
    icon: "bi-recycle",
    title: "Assainissement",
    text: "Réseaux d'assainissement, gestion des eaux usées, fosses septiques et fosses biodigesteurs."
  },
  {
    icon: "bi-building",
    title: "BTP & Génie Civil",
    text: "Construction, génie civil, VRD et travaux publics."
  },
  {
    icon: "bi-lightning-charge",
    title: "Vente de Matériels",
    text: "Pompes solaires, électriques et hybrides, panneaux solaires, PVC hydrauliques et accessoires."
  }
]
</script>

<template>
  <section class="about-hero py-5 bg-light">
    <div class="container py-3">
      <div class="row align-items-center gx-5 gy-4">
        <div class="col-lg-6">
          <span class="eyebrow">À propos de LIMANYA Groupe</span>

          <h1 class="display-5 fw-bold mt-3">
          Construire aujourd'hui les infrastructures de demain.
          </h1>
          <p class="lead text-muted mt-4">
            LIMANYA Groupe est une entreprise ivoirienne spécialisée dans les forages hydrauliques, les études géophysiques, l'assainissement (fosses septiques biodigesteurs) ainsi que le Bâtiment et Travaux Publics (BTP), au service des particuliers, entreprises et institutions.
          </p>
          <p>
           Grâce à une équipe expérimentée et des équipements performants, nous intervenons sur des projets de toutes dimensions avec un objectif constant : offrir des prestations de qualité, durables et conformes aux exigences de nos clients tout en respectant les normes de sécurité et les délais d'exécution.
          </p>
          <ul class="list-unstyled legal-list mt-4">
            <li><i class="bi bi-patch-check-fill text-amber me-2"></i>Capital social de 6 000 000 F CFA</li>
            <li><i class="bi bi-geo-alt-fill text-amber me-2"></i>Siège social à Gonzagueville, Port-Bouët</li>
            <li><i class="bi bi-person-fill text-amber me-2"></i>Dirigée par M. N'DIAYE, Directeur Général</li>
          </ul>
        </div>
        <div class="col-lg-6">
          <div class="hero-media ratio ratio-4x3">
            <img src="/images/about-team.png" alt="Équipe d'ingénieurs Limanya sur un chantier" class="w-100 h-100 object-fit-cover" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row gx-4 gy-4">
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="service-icon mb-3"><i class="bi bi-bullseye"></i></div>
            <h3 class="h5">Notre mission</h3>
            <p class="text-muted mb-0">Fournir des solutions techniques performantes dans les domaines des forages hydrauliques, des études géophysiques, de l'assainissement et du BTP, en garantissant la qualité des travaux, le respect des délais et la satisfaction de nos clients.</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="service-icon mb-3"><i class="bi bi-gem"></i></div>
            <h3 class="h5">Nos valeurs</h3>
            <p class="text-muted mb-0">Fiabilité, transparence, engagement et professionnalisme guident chacune de nos interventions.</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="service-icon mb-3"><i class="bi bi-diagram-3"></i></div>
            <h3 class="h5">Notre vision</h3>
            <p class="text-muted mb-0">
             Être une référence nationale dans les domaines des forages hydrauliques, des études géophysiques, de l'assainissement et du BTP grâce à notre expertise et à notre capacité d'innovation.
            </p>          
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">

      <div class="text-center mb-5" v-reveal>
        <span class="eyebrow">Nos domaines d'intervention</span>
        <h2 class="mt-3">
          Des solutions complètes pour tous vos projets
        </h2>

        <p class="text-muted mx-auto" style="max-width:700px">
          LIMANYA Groupe intervient dans plusieurs domaines afin d'accompagner
          efficacement les particuliers, les entreprises et les institutions
          publiques dans la réalisation de leurs projets.
        </p>

      </div>

      <div class="row g-4">

        <div
          class="col-md-6 col-lg-4"
          v-for="service in services"
          :key="service.title"
        >

          <div class="card border-0 shadow-sm h-100 p-4 service-card">

            <div class="service-icon mb-4">
              <i :class="['bi', service.icon]"></i>
            </div>

            <h4>{{ service.title }}</h4>

            <p class="text-muted mb-0">
              {{ service.text }}
            </p>

          </div>

        </div>

      </div>

    </div>
  </section>

  <section class="py-5 bg-light">
  <div class="container">

    <div class="text-center mb-5" v-reveal>

      <span class="eyebrow">Notre expérience</span>

      <h2 class="fw-bold mt-3">
        Quelques chiffres qui parlent de nous
      </h2>

      <p class="text-muted">
        Chaque projet réalisé renforce notre engagement envers la qualité et la satisfaction de nos clients.
      </p>

    </div>

    <div class="row text-center g-4">

      <div class="col-6 col-lg-3">

        <div class="stat-card">

          <h2>15+</h2>

          <p>Années d'expertise</p>

        </div>

      </div>

      <div class="col-6 col-lg-3">

        <div class="stat-card">

          <h2>200+</h2>

          <p>Projets livrés</p>

        </div>

      </div>

      <div class="col-6 col-lg-3">

        <div class="stat-card">

          <h2>100%</h2>

          <p>Engagement qualité</p>

        </div>

      </div>

      <div class="col-6 col-lg-3">

        <div class="stat-card">

          <h2>24/7</h2>

          <p>Accompagnement client</p>

        </div>

      </div>

    </div>

  </div>
</section>

  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5" v-reveal>
        <span class="eyebrow">Pourquoi choisir Limanya Groupe</span>
        <h2 class="mt-3">Des solutions locales avec une expertise technique forte</h2>
      </div>
      <div class="row g-4">

        <div
          class="col-md-6"
          v-for="value in values"
          :key="value.title">

          <div class="why-card">

            <div class="why-icon">
              <i :class="['bi', value.icon]"></i>
            </div>

            <h4>{{ value.title }}</h4>

            <p>
              {{ value.text }}
            </p>

          </div>

        </div>

      </div>

      </div>
    
  </section>

  <section class="py-5">
    <div class="container">

      <div class="text-center mb-5" v-reveal>
        <span class="eyebrow">Notre engagement</span>
        <h2 class="mt-3">Politique Qualité, Hygiène, Sécurité et Environnement (QHSE)</h2>
        <p class="text-muted mx-auto" style="max-width:700px">
          Sur chaque chantier, LIMANYA Groupe applique une politique QHSE rigoureuse, portée par la Direction Générale et communiquée à tous les niveaux de l'entreprise.
        </p>
      </div>

      <div class="row g-4">

        <div class="col-md-6 col-lg-3">
          <div class="qhse-card">
            <div class="qhse-icon"><i class="bi bi-droplet-half"></i></div>
            <h5>Hygiène</h5>
            <p>Conditions de travail propres et salubres sur tous les chantiers, avec des installations sanitaires conformes et entretenues.</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="qhse-card">
            <div class="qhse-icon"><i class="bi bi-shield-check"></i></div>
            <h5>Sécurité</h5>
            <p>Identification des risques, équipements de protection individuelle adaptés et formation régulière aux consignes de sécurité.</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="qhse-card">
            <div class="qhse-icon"><i class="bi bi-tree"></i></div>
            <h5>Environnement</h5>
            <p>Réduction des nuisances liées aux travaux, gestion responsable des déchets et préservation des ressources naturelles.</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="qhse-card">
            <div class="qhse-icon"><i class="bi bi-people-fill"></i></div>
            <h5>Engagement collectif</h5>
            <p>Chaque collaborateur est acteur de la démarche QHSE. Toute situation à risque est immédiatement signalée et traitée.</p>
          </div>
        </div>

      </div>

    </div>
  </section>
</template>

<style scoped>

.why-card{
    background:#fff;
    padding:35px;
    border-radius:22px;
    height:100%;
    transition:.35s;
    box-shadow:0 12px 35px rgba(0,0,0,.08);
    border:1px solid rgba(191,87,28,.08);
}

.why-card:hover{
    transform:translateY(-10px);
    box-shadow:0 22px 45px rgba(0,0,0,.12);
}

.why-icon{
    width:70px;
    height:70px;
    border-radius:18px;
    background:#bf571c;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
    margin-bottom:22px;
}

.why-card h4{
    font-weight:700;
    margin-bottom:15px;
    color:#1d1d1d;
}

.why-card p{
    color:#666;
    line-height:1.7;
    margin-bottom:0;
}

.stat-card{

    background:white;

    padding:35px;

    border-radius:20px;

    box-shadow:0 15px 40px rgba(0,0,0,.08);

    transition:.35s;

}

.stat-card:hover{

    transform:translateY(-8px);

}

.stat-card h2{

    color:#bf571c;

    font-size:3rem;

    font-weight:700;

}

.stat-card p{

    margin-top:10px;

    color:#666;

    font-weight:600;

}
.service-card{
    transition:.35s;
    border-radius:20px;
}

.service-card:hover{
    transform:translateY(-10px);
    box-shadow:0 20px 45px rgba(0,0,0,.12)!important;
}

.service-icon{

    width:70px;

    height:70px;

    border-radius:18px;

    background:#bf571c;

    color:white;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:30px;

}

.legal-list li{
    margin-bottom:10px;
    color:#443f34;
    font-weight:600;
    font-size:0.95rem;
}

.qhse-card{
    background:#fff;
    padding:30px 25px;
    border-radius:20px;
    height:100%;
    box-shadow:0 12px 30px rgba(0,0,0,.06);
    border:1px solid rgba(191,87,28,.08);
    transition:.3s;
}

.qhse-card:hover{
    transform:translateY(-6px);
    box-shadow:0 18px 40px rgba(0,0,0,.1);
}

.qhse-icon{
    width:56px;
    height:56px;
    border-radius:14px;
    background:#f6e7da;
    color:#bf571c;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    margin-bottom:18px;
}

.qhse-card h5{
    font-weight:700;
    margin-bottom:10px;
}

.qhse-card p{
    color:#666;
    font-size:0.92rem;
    line-height:1.6;
    margin-bottom:0;
}
</style>