<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const deconnexion = () => {
  localStorage.removeItem('user')
  router.push('/admin/login')
}
</script>

<template>
  <nav class="navbar-admin">

    <h4>Espace administration</h4>

    <div class="right">

      <span class="admin-user">
        <i class="bi bi-person-circle"></i>
        Administrateur
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
    gap:8px;
    color:#6b6353;
    font-weight:500;

}

.admin-user i{

    font-size:1.2rem;
    color:#bf571c;

}

</style>
