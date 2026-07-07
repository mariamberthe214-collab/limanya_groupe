<template>
  <aside class="sidebar">
    <div class="logo">
      <span class="logo-mark">L</span>
      <span class="logo-text">LIMANYA</span>
    </div>

    <nav class="sidebar-nav">
      <RouterLink to="/admin/dashboard">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
      </RouterLink>

      <RouterLink to="/admin/actualites">
        <i class="bi bi-newspaper"></i>
        <span>Actualités</span>
      </RouterLink>

      <RouterLink to="/admin/realisations">
        <i class="bi bi-building"></i>
        <span>Réalisations</span>
      </RouterLink>

      <RouterLink to="/admin/equipements">
        <i class="bi bi-truck"></i>
        <span>Équipements</span>
      </RouterLink>

      <RouterLink to="/admin/devis">
        <i class="bi bi-file-earmark-text"></i>
        <span>Devis</span>
      </RouterLink>

      <RouterLink to="/admin/contacts" class="nav-link">
        <i class="bi bi-envelope"></i>
        <span>Messages</span>
      </RouterLink>

      <RouterLink to="/admin/utilisateurs">
        <i class="bi bi-person"></i>
        <span>Utilisateurs</span>
      </RouterLink>
    </nav>
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
</style>
