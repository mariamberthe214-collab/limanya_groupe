<script setup>
import { ref, onMounted, inject } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()
const utilisateur = ref(null)
const toggleSidebar = inject('toggleSidebar')

const initiales = (nom) => {
  if (!nom) return 'A'
  return nom
    .split(' ')
    .map((part) => part[0])
    .join('')
    .slice(0, 2)
    .toUpperCase()
}

onMounted(() => {
  const stocke = localStorage.getItem('admin_user')
  if (stocke) {
    try {
      utilisateur.value = JSON.parse(stocke)
    } catch (e) {
      utilisateur.value = null
    }
  }
})

const deconnexion = async () => {
  try {
    await api.post('/auth/logout')
  } catch (error) {
    console.error(error)
  } finally {
    localStorage.removeItem('admin_user')
    router.push('/admin/login')
  }
}
</script>

<template>
  <nav class="navbar-admin">

    <div class="left">
      <button class="burger d-lg-none" @click="toggleSidebar" aria-label="Ouvrir le menu">
        <i class="bi bi-list"></i>
      </button>
      <h4>Espace administration</h4>
    </div>

    <div class="right">

      <span class="admin-user">
        <span class="avatar">{{ initiales(utilisateur?.nom) }}</span>
        <span class="admin-name">{{ utilisateur?.nom || 'Administrateur' }}</span>
      </span>

      <button
        class="btn btn-outline-danger btn-sm"
        @click="deconnexion">

        <i class="bi bi-box-arrow-right me-lg-1"></i>
        <span class="deco-label">Déconnexion</span>

      </button>

    </div>

  </nav>
</template>

<style scoped>

.navbar-admin{

    height:70px;
    background:#fff;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 30px;
    border-bottom:1px solid #ece7dd;
    gap:12px;

}

.left{

    display:flex;
    align-items:center;
    gap:14px;
    min-width:0;

}

.burger{

    background:transparent;
    border:1px solid #ece7dd;
    border-radius:10px;
    width:38px;
    height:38px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:1.3rem;
    color:#2c281f;
    flex-shrink:0;

}

.navbar-admin h4{

    margin:0;
    font-size:1.1rem;
    font-weight:700;
    color:#2c281f;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;

}

.right{

    display:flex;
    align-items:center;
    gap:18px;

}

.admin-user{

    display:flex;
    align-items:center;
    gap:10px;
    color:#443f34;
    font-weight:600;
    font-size:0.92rem;

}

.avatar{

    width:34px;
    height:34px;
    border-radius:50%;
    background:#f6e7da;
    color:#bf571c;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:0.8rem;
    flex-shrink:0;

}

@media (max-width: 991px) {

    .navbar-admin{
        padding:0 16px;
    }

    .navbar-admin h4{
        font-size:0.95rem;
    }

}

@media (max-width: 576px) {

    .admin-name{
        display:none;
    }

    .deco-label{
        display:none;
    }

}

</style>
