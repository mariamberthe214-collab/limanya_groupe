<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'

const router = useRouter()
const utilisateur = ref(null)

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

    <h4>Espace administration</h4>

    <div class="right">

      <span class="admin-user">
        <span class="avatar">{{ initiales(utilisateur?.nom) }}</span>
        {{ utilisateur?.nom || 'Administrateur' }}
      </span>

      <button
        class="btn btn-outline-danger btn-sm"
        @click="deconnexion">

        <i class="bi bi-box-arrow-right me-1"></i>
        Déconnexion

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

}

.navbar-admin h4{

    margin:0;
    font-size:1.1rem;
    font-weight:700;
    color:#2c281f;

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

}

</style>
