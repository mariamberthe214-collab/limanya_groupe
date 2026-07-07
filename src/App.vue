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

    <Footer v-if="!isAdmin" />

  </div>
</template>