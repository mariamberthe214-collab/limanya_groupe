<template>
  <main class="reglement-page">
    <section class="reglement-hero">
      <span>CALENDRIER DES RÊVES LIMANYA</span>
      <h1>Règlement du jeu</h1>
      <p>Les modalités de participation à l’édition de décembre 2026.</p>
    </section>
    <article class="reglement-contenu">
      <section><h2>1. Période du jeu</h2><p>Le jeu se déroule du 1er au 31 décembre 2026. Une nouvelle fenêtre est disponible chaque jour à partir de 12h, heure d’Abidjan. La participation à la question du jour se termine à minuit, heure d’Abidjan.</p></section>
      <section><h2>2. Participation</h2><p>Pour participer, le joueur renseigne son nom, ses prénoms, son numéro WhatsApp et sa commune ou ville, puis choisit une réponse parmi les trois propositions.</p><p>Une seule participation est autorisée par numéro WhatsApp et par journée. Le numéro est normalisé côté serveur pour contrôler les variantes de saisie.</p></section>
      <section><h2>3. Réponses et sélection</h2><p>Les participations ayant donné la bonne réponse sont éligibles au tirage au sort manuel de la journée. Le tirage est réalisé par l’équipe Limanya après la fermeture de la fenêtre. Le gagnant est contacté afin de confirmer son identité.</p></section>
      <section><h2>4. Publication des gagnants</h2><p>Le nom d’un gagnant est publié uniquement après vérification de son identité. Les questions des jours passés et les gagnants publiés peuvent être consultés sur la page du calendrier.</p></section>
      <section><h2>5. Données personnelles</h2><p>Les coordonnées fournies servent à enregistrer les participations et à contacter le gagnant. Elles ne sont pas affichées publiquement. Seuls le nom et les prénoms d’un gagnant confirmé puis publié apparaissent sur le site. Les modalités de conservation sont celles de la politique de confidentialité de LIMANYA Groupe.</p></section>
      <section><h2>6. Contact</h2><p>Pour toute question sur le jeu, contactez LIMANYA Groupe via la page <RouterLink to="/contacts">Contacts</RouterLink>.</p></section>
      <RouterLink class="retour-calendrier" to="/calendrier-des-reves">Retour au calendrier</RouterLink>
    </article>
  </main>
</template>

<style scoped>
.reglement-page{min-height:70vh;padding-bottom:64px;background:var(--lg-cream)}.reglement-hero{padding:72px 20px;text-align:center;background:linear-gradient(135deg,#17140f,#373124);color:#fff}.reglement-hero span{font-size:.75rem;letter-spacing:.18em;color:#fe8a54;font-weight:700}.reglement-hero h1{color:#fff;margin:14px 0}.reglement-hero p{color:#eee}.reglement-contenu{max-width:850px;margin:42px auto;padding:0 22px}.reglement-contenu section{padding:20px 0;border-bottom:1px solid var(--lg-line)}.reglement-contenu h2{font-size:1.25rem}.reglement-contenu p{line-height:1.8}.retour-calendrier{display:inline-block;margin-top:28px;padding:12px 18px;background:var(--lg-amber);border-radius:8px;color:white}.retour-calendrier:hover{color:white;background:var(--lg-amber-dark)}
</style>
