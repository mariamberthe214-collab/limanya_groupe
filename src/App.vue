<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'

const route = useRoute()

const isAdmin = computed(() => {
  return route.path.startsWith('/admin')
})
</script>

<template>
  <div id="app">

    <Navbar v-if="!isAdmin" />

    <main>
      <router-view />
    </main>

    <div v-if="!isAdmin" class="mobile-contact-actions" aria-label="Contacts rapides">
      <a class="mobile-contact-button mobile-contact-call" href="tel:+2250777047057" aria-label="Appeler LIMANYA Groupe">
        <i class="bi bi-telephone-fill" aria-hidden="true"></i>
        <span>Appeler</span>
      </a>
      <a
        class="mobile-contact-button mobile-contact-whatsapp"
        href="https://wa.me/2250797398700?text=Bonjour%20LIMANYA%20Groupe%2C%20je%20souhaite%20avoir%20plus%20d%27informations."
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Contacter LIMANYA Groupe sur WhatsApp">
        <i class="bi bi-whatsapp" aria-hidden="true"></i>
        <span>WhatsApp</span>
      </a>
    </div>

    <Footer v-if="!isAdmin" />

  </div>
</template>

<style scoped>
.mobile-contact-actions {
  display: none;
}

@media (max-width: 767.98px) {
  .mobile-contact-actions {
    position: fixed;
    right: 12px;
    bottom: calc(12px + env(safe-area-inset-bottom, 0px));
    z-index: 1050;
    display: flex;
    gap: 8px;
  }

  .mobile-contact-button {
    display: inline-flex;
    min-width: 44px;
    min-height: 48px;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 14px;
    border-radius: 999px;
    color: #fff;
    font-size: 0.84rem;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
  }

  .mobile-contact-call {
    background: var(--lg-amber-dark, #9a541f);
  }

  .mobile-contact-whatsapp {
    background: #168b52;
  }
}
</style>