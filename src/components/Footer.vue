<template>
  <footer class="site-footer pt-5">
    <div class="container">
      <div class="row gy-4 pb-5">
        <div class="col-lg-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="foot-mark"><i class="bi bi-buildings-fill"></i></span>
            <span class="foot-brand">LIMANYA <span class="text-amber">Groupe</span></span>
          </div>
          <p class="foot-text mb-4">Nous construisons vos rêves : BTP, forages hydrauliques, assainissement, énergies renouvelables et gestion immobilière.</p>
          <div class="d-flex gap-2">
            <a class="foot-social" href="https://wa.me/0564613584" target="_blank" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
            <a class="foot-social" href="https://www.facebook.com/groupelimanyaservices" target="_blank" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a class="foot-social" href="mailto:groupelimanya18@gmail.com" aria-label="Email"><i class="bi bi-envelope-fill"></i></a>
          </div>
        </div>

        <div class="col-6 col-lg-2">
          <h6 class="foot-title">Services</h6>
          <ul class="list-unstyled foot-links">
            <li><router-link to="/btp">BTP</router-link></li>
            <li><router-link to="/forage">Forage</router-link></li>
            <li><router-link to="/assainissement">Assainissement</router-link></li>
            <li><router-link to="/location">Location</router-link></li>
          </ul>
        </div>

        <div class="col-6 col-lg-2">
          <h6 class="foot-title">Entreprise</h6>
          <ul class="list-unstyled foot-links">
            <li><router-link to="/apropos">À propos</router-link></li>
            <li><router-link to="/realisations-btp-services-de-construction">Réalisations</router-link></li>
            <li><router-link to="/actualites">Actualités</router-link></li>
            <li><router-link to="/contacts">Contacts</router-link></li>
          </ul>
        </div>

        <div class="col-lg-4">
          <h6 class="foot-title">Contact</h6>
          <ul class="list-unstyled foot-links">
            <li><i class="bi bi-envelope me-2"></i>groupelimanya18@gmail.com</li>
            <li><i class="bi bi-telephone me-2"></i>+225 07 07 17 21 72</li>
            <li><i class="bi bi-telephone me-2"></i>+225 07 77 04 70 57</li>
            <li><i class="bi bi-geo-alt me-2"></i>Côte d'Ivoire</li>
          </ul>
        </div>
      </div>

      <div class="foot-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 py-4">
        <p>© 2026 Limanya Groupe. Tous droits réservés.</p>
        <p>Bâtir un avenir durable et solide.</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>

<style scoped>
.foot-mark {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  border-radius: 11px;
  background: var(--lg-amber);
  color: #fff;
}
.foot-brand {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 700;
  font-size: 1.2rem;
  color: #fff;
}
.foot-text {
  color: #a79f8d;
  max-width: 340px;
}
.foot-title {
  color: #fff;
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 600;
  font-size: 0.85rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 1rem;
}
.foot-links li {
  color: #b7ae9b;
  margin-bottom: 0.6rem;
  font-size: 0.95rem;
}
.foot-links a {
  color: #b7ae9b;
  transition: color 0.2s ease;
}
.foot-links a:hover {
  color: var(--lg-amber);
}
.foot-social {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  color: #cfc7b6;
  transition: all 0.2s ease;
}
.foot-social:hover {
  background: var(--lg-amber);
  border-color: var(--lg-amber);
  color: #fff;
}
.foot-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}
.foot-bottom p {
  color: #8a8271;
  font-size: 0.88rem;
  margin: 0;
}
</style>
