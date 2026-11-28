<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import api from '../services/api'
import { getImageUrl } from '../utils/images'

const etat = ref(null)
const chargement = ref(true)
const erreur = ref('')
const jourSelectionne = ref(null)
const reponse = ref('')
const participant = ref({ nom: '', prenoms: '', whatsapp: '', commune: '' })
const message = ref('')
const envoi = ref(false)
const envoye = ref(false)
const serveurSynchronise = ref(false)
const horloge = ref(new Date())
let decalageServeur = 0
let minuterie

const dateServeur = computed(() => {
  if (!etat.value?.date_aujourdhui) return ''
  return etat.value.date_aujourdhui
})
const jourCourant = computed(() => Number(dateServeur.value.slice(-2)) || 0)
const questionAujourdhui = computed(() => etat.value?.question_du_jour || null)
const questionsPassees = computed(() => etat.value?.questions_passees || [])
const gagnants = computed(() => etat.value?.gagnants_publies || [])
const questionSelectionnee = computed(() => {
  if (!jourSelectionne.value) return null
  if (Number(questionAujourdhui.value?.jour) === jourSelectionne.value) return questionAujourdhui.value
  return questionsPassees.value.find((q) => Number(q.jour) === jourSelectionne.value) || null
})

function dateEtHeureAbidjan() {
  if (!serveurSynchronise.value || !(horloge.value instanceof Date) || Number.isNaN(horloge.value.getTime())) {
    return 'Heure officielle indisponible'
  }
  return new Intl.DateTimeFormat('fr-FR', {
    timeZone: 'Africa/Abidjan', dateStyle: 'full', timeStyle: 'short'
  }).format(horloge.value)
}

async function chargerEtat() {
  try {
    const { data } = await api.get('calendrier-reves')
    const instantServeur = Date.parse(data?.server_now)
    if (!Number.isFinite(instantServeur) || !data?.date_aujourdhui || !data?.phase) {
      throw new Error(data?.message || 'Réponse d’heure invalide reçue du serveur.')
    }
    etat.value = data
    decalageServeur = instantServeur - Date.now()
    horloge.value = new Date(Date.now() + decalageServeur)
    serveurSynchronise.value = true
    erreur.value = ''
  } catch (e) {
    etat.value = null
    serveurSynchronise.value = false
    erreur.value = e.response?.data?.message || e.message || 'Le calendrier est momentanément indisponible.'
  } finally {
    chargement.value = false
  }
}

function estPasse(jour) {
  return questionsPassees.value.some((question) => Number(question.jour) === jour)
}

function fenetreOuverte(jour) {
  return jour === jourCourant.value && etat.value?.phase === 'ouvert' && Number(questionAujourdhui.value?.jour) === jour
}

function choisirFenetre(jour) {
  if (!fenetreOuverte(jour) && !estPasse(jour)) return
  jourSelectionne.value = jour
  reponse.value = ''
  message.value = ''
  envoye.value = false
  participant.value = { nom: '', prenoms: '', whatsapp: '', commune: '' }
}

async function participer() {
  if (!fenetreOuverte(jourSelectionne.value) || !reponse.value || envoi.value || envoye.value) return
  envoi.value = true
  message.value = ''
  try {
    const { data } = await api.post('calendrier-reves/participer', {
      ...participant.value,
      nom: participant.value.nom.trim(),
      prenoms: participant.value.prenoms.trim(),
      whatsapp: participant.value.whatsapp.trim(),
      commune: participant.value.commune.trim(),
      reponse: reponse.value
    })
    message.value = data.message || 'Votre participation est enregistrée.'
    envoye.value = true
  } catch (e) {
    message.value = e.response?.data?.message || 'La participation n’a pas pu être enregistrée.'
  } finally {
    envoi.value = false
  }
}

onMounted(async () => {
  await chargerEtat()
  minuterie = window.setInterval(() => {
    horloge.value = new Date(Date.now() + decalageServeur)
    chargerEtat()
  }, 30000)
})
onUnmounted(() => window.clearInterval(minuterie))
</script>

<template>
  <main class="calendrier-page">
    <section class="hero-calendrier">
      <div class="hero-contenu">
        <span class="edition">ÉDITION SPÉCIALE · DÉCEMBRE 2026</span>
        <h1>Le Calendrier<br><span>des Rêves</span></h1>
        <p class="hero-description">31 jours, 31 occasions de rêver et de gagner ! Chaque jour, ouvrez une fenêtre et découvrez votre question.</p>
        <div class="hero-infos">
          <div><strong>31</strong><span>Fenêtres</span></div>
          <div><strong>12h</strong><span>Ouverture à Abidjan</span></div>
          <div><strong>Minuit</strong><span>Fermeture quotidienne</span></div>
        </div>
        <p class="hero-signature">Heure officielle : {{ dateEtHeureAbidjan() }}</p>
        <RouterLink class="btn-reglement" to="/reglement-calendrier">Lire le règlement</RouterLink>
      </div>
    </section>

    <section class="calendrier-introduction">
      <span class="petit-titre">À VOUS DE JOUER</span>
      <h2>Une nouvelle fenêtre chaque jour</h2>
      <p>Les fenêtres du jour s’ouvrent à midi, heure d’Abidjan, et ferment à minuit. Les jours passés restent consultables.</p>
      <p v-if="etat?.phase === 'attente'" class="etat-jeu">La fenêtre du jour s’ouvrira à 12h.</p>
      <p v-else-if="etat?.phase === 'hors_periode'" class="etat-jeu">Le jeu est fermé en dehors du mois de décembre 2026.</p>
      <p v-if="erreur" class="message-erreur" role="alert">{{ erreur }}</p>
    </section>

    <section class="grille-calendrier" aria-label="Les 31 fenêtres du calendrier">
      <button
        v-for="jour in 31" :key="jour" type="button" class="case-jour"
        :class="{ ouverte: fenetreOuverte(jour), passee: estPasse(jour), selectionnee: jourSelectionne === jour }"
        :disabled="!fenetreOuverte(jour) && !estPasse(jour)"
        :aria-label="`Fenêtre du ${jour} décembre${fenetreOuverte(jour) ? ', ouverte' : estPasse(jour) ? ', journée passée' : ', bientôt disponible'}`"
        @click="choisirFenetre(jour)"
      >
        <span class="numero-jour">{{ jour }}</span>
        <span class="nom-mois">DÉCEMBRE</span>
        <span class="cadenas" aria-hidden="true">{{ fenetreOuverte(jour) ? '↗' : estPasse(jour) ? '✓' : '⌑' }}</span>
        <span class="texte-case">{{ fenetreOuverte(jour) ? 'Ouvrir la fenêtre' : estPasse(jour) ? 'Question passée' : 'Bientôt' }}</span>
      </button>
    </section>

    <section v-if="chargement" class="chargement-calendrier" aria-live="polite">Chargement du calendrier…</section>

    <Teleport to="body">
    <section v-if="questionSelectionnee" class="question-overlay" aria-live="polite"
      @click.self="jourSelectionne = null">
      <div class="question-carte" role="dialog" aria-modal="true"
        :aria-label="`Question du jour ${jourSelectionne}`">
        <button class="fermer-question" type="button" aria-label="Fermer la question" @click="jourSelectionne = null">×</button>
        <span class="petit-titre">{{ fenetreOuverte(jourSelectionne) ? 'QUESTION DU JOUR' : 'QUESTION PASSÉE' }} · JOUR {{ jourSelectionne }}</span>
        <h2>{{ questionSelectionnee.cadeau }}</h2>
        <p class="question-texte">{{ questionSelectionnee.question }}</p>
        <div class="choix-reponses">
          <button v-for="lettre in ['a', 'b', 'c']" :key="lettre" type="button"
            :disabled="!fenetreOuverte(jourSelectionne) || envoye"
            :class="{ 'reponse-active': reponse === lettre.toUpperCase() }"
            @click="reponse = lettre.toUpperCase()">
            <strong>{{ lettre.toUpperCase() }}</strong><span>{{ questionSelectionnee[`choix_${lettre}`] }}</span>
          </button>
        </div>

        <form v-if="fenetreOuverte(jourSelectionne) && !envoye" class="form-participation" @submit.prevent="participer">
          <h3>Vos informations</h3>
          <input v-model.trim="participant.nom" type="text" maxlength="100" placeholder="Votre nom" autocomplete="family-name" required>
          <input v-model.trim="participant.prenoms" type="text" maxlength="150" placeholder="Vos prénoms" autocomplete="given-name" required>
          <input v-model.trim="participant.whatsapp" type="tel" maxlength="30" placeholder="Votre numéro WhatsApp" autocomplete="tel" required>
          <input v-model.trim="participant.commune" type="text" maxlength="100" placeholder="Votre commune ou ville" autocomplete="address-level2" required>
          <button type="submit" :disabled="envoi || !reponse">{{ envoi ? 'Envoi en cours…' : 'Valider ma participation' }}</button>
        </form>
        <p v-if="envoye" class="confirmation-participation" role="status">{{ message }}</p>
        <p v-else-if="message" class="message-erreur" role="alert">{{ message }}</p>
        <p v-if="!fenetreOuverte(jourSelectionne)" class="note-historique">Cette journée est terminée. La réponse correcte n’est pas affichée.</p>
      </div>
    </section>
    </Teleport>

    <section v-if="gagnants.length" class="historique-public">
      <span class="petit-titre">LES RÊVES RÉALISÉS</span>
      <h2>Gagnants des jours passés</h2>
      <div class="cartes-gagnants">
        <article v-for="gagnant in gagnants" :key="`${gagnant.date_jeu}-${gagnant.nom}`" class="carte-gagnant">
          <span>Jour {{ gagnant.jour }} · {{ gagnant.cadeau }}</span>
          <img v-if="gagnant.media_cadeau && gagnant.type_media_cadeau === 'image'"
            :src="getImageUrl(gagnant.media_cadeau)" :alt="`Cadeau : ${gagnant.cadeau}`"
            class="media-gagnant" loading="lazy">
          <video v-else-if="gagnant.media_cadeau && gagnant.type_media_cadeau === 'video'"
            :src="getImageUrl(gagnant.media_cadeau)" class="media-gagnant"
            controls playsinline preload="metadata" :aria-label="`Vidéo du cadeau : ${gagnant.cadeau}`"></video>
          <strong>{{ gagnant.prenoms }} {{ gagnant.nom }}</strong>
        </article>
      </div>
    </section>
  </main>
</template>

<style scoped>
.calendrier-page{min-height:100vh;background:#f0ede4;color:#2c281f;padding-bottom:70px}.hero-calendrier{min-height:510px;display:grid;place-items:center;text-align:center;padding:64px 20px;color:#fff;background:radial-gradient(circle at 85% 12%,#5e7332 0,transparent 28%),linear-gradient(135deg,#17140f,#373124 65%,#17140f)}.hero-contenu{max-width:850px}.edition,.petit-titre{color:#ff8a54;font-size:.75rem;font-weight:800;letter-spacing:.18em}.hero-calendrier h1{font-size:clamp(3rem,8vw,5.5rem);line-height:1.02;margin:24px 0;color:#fff}.hero-calendrier h1 span{color:#ff8a54;font-style:italic}.hero-description{max-width:630px;margin:auto;color:#f0ede4;line-height:1.8}.hero-infos{display:flex;justify-content:center;gap:48px;margin:32px 0;flex-wrap:wrap}.hero-infos div{display:flex;flex-direction:column}.hero-infos strong{font-size:1.7rem;color:#ff8a54}.hero-infos span,.hero-signature{color:#e8e3d5}.btn-reglement{display:inline-block;margin-top:10px;padding:10px 18px;border:1px solid #ff8a54;border-radius:30px;color:#fff}.btn-reglement:hover{background:#fe5000;color:#fff}.calendrier-introduction,.historique-public{text-align:center;max-width:800px;margin:64px auto 32px;padding:0 20px}.calendrier-introduction h2,.historique-public h2{margin:12px 0;color:#14110d}.calendrier-introduction p{color:#6f685a}.etat-jeu{font-weight:700;color:#9d4514!important}.grille-calendrier{max-width:1100px;margin:32px auto;padding:0 20px;display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:14px}.case-jour{position:relative;min-height:145px;border:1px solid #ddd7c6;border-radius:14px;background:linear-gradient(145deg,#fff,#f6e7da);color:#14110d;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;overflow:hidden;transition:transform .25s,box-shadow .25s}.case-jour:disabled{opacity:.65;cursor:not-allowed}.case-jour:not(:disabled){cursor:pointer}.case-jour:not(:disabled):hover{transform:translateY(-4px);box-shadow:0 10px 24px #14110d1c}.case-jour.ouverte{border:2px solid #fe5000;background:#fff;animation:pulse 2s infinite}.case-jour.selectionnee{animation:ouverture .45s ease both}.numero-jour{font-size:2.3rem;font-weight:800;color:#9d4514}.nom-mois{font-size:.65rem;letter-spacing:.14em;color:#6f685a}.cadenas{font-size:1.2rem;color:#fe5000}.texte-case{font-size:.72rem;color:#6f685a}.chargement-calendrier{text-align:center;padding:20px}.question-section{max-width:780px;margin:36px auto;padding:0 20px;animation:arrivee .3s ease}.question-carte{position:relative;background:#fff;padding:36px 24px;border-radius:20px;border:1px solid #ddd7c6;box-shadow:0 14px 40px #14110d18;text-align:center}.question-carte h2{color:#9d4514;margin:18px}.question-texte{font-size:1.1rem}.fermer-question{position:absolute;right:14px;top:10px;border:0;background:none;font-size:1.8rem;color:#6f685a}.choix-reponses{display:grid;gap:10px;margin:24px 0}.choix-reponses button{display:flex;align-items:center;gap:14px;text-align:left;padding:13px;border:1px solid #ddd7c6;border-radius:10px;background:#f0ede4;color:#2c281f}.choix-reponses button:disabled{cursor:default}.choix-reponses button.reponse-active{border-color:#fe5000;background:#f6e7da}.choix-reponses button strong{display:grid;place-items:center;min-width:36px;height:36px;border-radius:9px;background:#fe5000;color:#fff}.form-participation{display:grid;gap:12px;margin-top:28px;text-align:left}.form-participation h3{text-align:center}.form-participation input{padding:13px;border:1px solid #ddd7c6;border-radius:9px}.form-participation button{padding:14px;background:#fe5000;border:0;border-radius:9px;color:#fff;font-weight:700}.form-participation button:disabled{opacity:.5}.confirmation-participation{padding:15px;border-radius:10px;background:#e4f2e8;color:#174a37}.note-historique{padding:12px;background:#f0ede4;border-radius:8px}.message-erreur{color:#a52b19!important}.cartes-gagnants{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-top:24px}.carte-gagnant{display:grid;gap:8px;padding:18px;background:#fff;border:1px solid #ddd7c6;border-radius:12px;text-align:left}.carte-gagnant span{font-size:.85rem;color:#6f685a}.carte-gagnant strong{color:#14110d}@keyframes ouverture{0%{transform:perspective(500px) rotateY(0);filter:brightness(1)}50%{transform:perspective(500px) rotateY(-75deg);filter:brightness(.8)}100%{transform:perspective(500px) rotateY(0);filter:brightness(1)}}@keyframes pulse{50%{box-shadow:0 0 0 6px #fe500022}}@keyframes arrivee{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}@media(max-width:900px){.grille-calendrier{grid-template-columns:repeat(4,minmax(0,1fr))}}@media(max-width:600px){.grille-calendrier{grid-template-columns:repeat(3,minmax(0,1fr));gap:9px;padding:0 12px}.case-jour{min-height:118px}.hero-infos{gap:24px}.question-carte{padding:32px 16px}}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms!important;transition-duration:.01ms!important;scroll-behavior:auto!important}}
.grille-calendrier{position:relative;margin-top:62px;padding:40px 24px 24px;border:10px solid #254936;border-radius:18px;background:linear-gradient(135deg,#315c40,#1e412e);box-shadow:0 18px 36px #14110d26}.grille-calendrier::before{content:"";position:absolute;z-index:0;top:-45px;left:-10px;width:calc(100% + 20px);height:46px;background:#182f22;clip-path:polygon(0 100%,50% 0,100% 100%)}.grille-calendrier .case-jour{z-index:1;box-shadow:inset 0 0 0 3px #ffffff18,0 5px 10px #0b201655}.grille-calendrier .case-jour.ouverte{box-shadow:0 0 0 4px #fe500033,0 6px 16px #0b201655}@media(max-width:600px){.grille-calendrier{margin-left:12px;margin-right:12px;padding:32px 10px 14px;border-width:7px}.grille-calendrier::before{left:-7px;width:calc(100% + 14px)}}
.media-gagnant{display:block;width:100%;max-height:280px;object-fit:cover;border-radius:10px;background:#f0ede4}
.question-overlay{position:fixed;inset:0;z-index:2500;display:grid;place-items:center;padding:20px;background:rgba(20,17,13,.72);overflow-y:auto;animation:arrivee .25s ease}
.question-overlay .question-carte{width:min(780px,100%);max-height:calc(100dvh - 40px);overflow-y:auto}
@media(max-width:600px){.question-overlay{padding:10px}.question-overlay .question-carte{max-height:calc(100dvh - 20px)}}
</style>
