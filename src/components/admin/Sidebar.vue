<script setup>
import { inject } from 'vue'

const sidebarOpen = inject('sidebarOpen')
const closeSidebar = inject('closeSidebar')
</script>

<template>
  <aside class="sidebar" :class="{ 'sidebar-open': sidebarOpen }">
    <div class="logo">
      <span class="logo-mark">L</span>
      <span class="logo-text">LIMANYA</span>

      <button class="sidebar-close d-lg-none" @click="closeSidebar" aria-label="Fermer le menu">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <nav class="sidebar-nav">
      <RouterLink to="/admin/dashboard" @click="closeSidebar">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
      </RouterLink>

      <RouterLink to="/admin/actualites" @click="closeSidebar">
        <i class="bi bi-newspaper"></i>
        <span>Actualités</span>
      </RouterLink>

      <RouterLink to="/admin/realisations" @click="closeSidebar">
        <i class="bi bi-building"></i>
        <span>Réalisations</span>
      </RouterLink>

      <RouterLink to="/admin/equipements" @click="closeSidebar">
        <i class="bi bi-truck"></i>
        <span>Équipements</span>
      </RouterLink>

      <RouterLink to="/admin/devis" @click="closeSidebar">
        <i class="bi bi-file-earmark-text"></i>
        <span>Devis</span>
      </RouterLink>

      <RouterLink to="/admin/contacts" class="nav-link" @click="closeSidebar">
        <i class="bi bi-envelope"></i>
        <span>Messages</span>
      </RouterLink>

      <RouterLink to="/admin/utilisateurs" @click="closeSidebar">
        <i class="bi bi-person"></i>
        <span>Utilisateurs</span>
      </RouterLink>
    </nav>

    <div class="sidebar-footer">
      <a href="/" target="_blank" class="sidebar-footer-link">
        <i class="bi bi-box-arrow-up-right"></i>
        <span>Voir le site public</span>
      </a>
    </div>
  </aside>
</template>

<style scoped>
.sidebar {
  width: 260px;
  background: linear-gradient(180deg, #17140f 0%, #241f18 100%);
  color: #e9e3d7;
  padding: 24px 18px;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  flex-shrink: 0;
}

.sidebar-close {
  margin-left: auto;
  background: transparent;
  border: 0;
  color: #e9e3d7;
  font-size: 1.1rem;
  padding: 6px;
  line-height: 1;
}

.logo {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 32px;
  padding: 0 6px;
}

.logo-mark {
  width: 38px;
  height: 38px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background: #bf571c;
  color: #fff;
  font-weight: 800;
  font-size: 1.1rem;
}

.logo-text {
  font-weight: 700;
  letter-spacing: 0.16em;
  font-size: 1.05rem;
  color: #f4f1e9;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

a {
  display: flex;
  align-items: center;
  gap: 12px;
  color: #c9c1b3;
  text-decoration: none;
  padding: 12px 14px;
  border-radius: 10px;
  font-weight: 500;
  transition: all 0.2s ease;
}

a i {
  font-size: 1.15rem;
}

a:hover {
  background: rgba(255, 255, 255, 0.08);
  color: #fff;
}

.router-link-active {
  background: #bf571c;
  color: #fff;
}

.router-link-active:hover {
  background: #a94a15;
  color: #fff;
}

.sidebar-footer {
  margin-top: auto;
  padding-top: 18px;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.sidebar-footer-link {
  display: flex;
  align-items: center;
  gap: 12px;
  color: #c9c1b3;
  text-decoration: none;
  padding: 12px 14px;
  border-radius: 10px;
  font-weight: 500;
  font-size: 0.9rem;
  transition: all 0.2s ease;
}

.sidebar-footer-link:hover {
  background: rgba(255, 255, 255, 0.08);
  color: #fff;
}

.sidebar-close {
  display: none;
}

@media (max-width: 991px) {

  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    transform: translateX(-100%);
    transition: transform 0.25s ease;
    width: 82vw;
    max-width: 300px;
    box-shadow: 20px 0 50px rgba(0, 0, 0, 0.25);
  }

  .sidebar.sidebar-open {
    transform: translateX(0);
  }

  .sidebar-close {
    display: inline-flex;
  }

}

</style>
