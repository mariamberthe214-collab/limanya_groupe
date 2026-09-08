<template>
  <main class="not-found">
    <div class="not-found-card">
      <span class="eyebrow">Erreur 404</span>
      <h1>Cette page n'existe pas</h1>
      <p>Le lien demandé est introuvable ou a été déplacé.</p>
      <RouterLink to="/" class="btn btn-primary">
        <i class="bi bi-house me-2"></i>Retour à l'accueil
      </RouterLink>
    </div>
  </main>
</template>

<style scoped>
.not-found {
  min-height: 70vh;
  display: grid;
  place-items: center;
  padding: 3rem 1rem;
  background: var(--lg-cream);
}

.not-found-card {
  width: min(100%, 560px);
  padding: 3rem 2rem;
  text-align: center;
  background: var(--lg-surface);
  border: 1px solid var(--lg-line);
  border-radius: 1rem;
  box-shadow: 0 18px 40px rgba(23, 20, 15, 0.08);
}

.not-found-card h1 {
  margin: 1rem 0 0.75rem;
  font-size: clamp(2rem, 6vw, 3.5rem);
}

.not-found-card p {
  margin-bottom: 1.5rem;
}
</style>
