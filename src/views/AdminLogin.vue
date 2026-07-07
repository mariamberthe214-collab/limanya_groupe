<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()

const login = async () => {
  try {
    const response = await api.post('/auth/login', {
      email: email.value,
      password: password.value
    })

    alert(response.data.message)

    router.push('/admin/dashboard')

  } catch (error) {
    alert("Email ou mot de passe incorrect")
    console.error(error)
  }
}

const email = ref('')
const password = ref('')


</script>

<template>

<div class="login-page">

    <div class="login-card">

        <div class="login-brand">
            <span class="login-mark">L</span>
            <span class="login-name">LIMANYA GROUPE</span>
        </div>

        <h2 class="login-title">Connexion administrateur</h2>
        <p class="login-subtitle">Accédez à votre espace de gestion</p>

        <label class="login-label">Adresse email</label>
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input
                v-model="email"
                class="form-control"
                placeholder="exemple@limanya.com"
            >
        </div>

        <label class="login-label">Mot de passe</label>
        <div class="input-group mb-4">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input
                type="password"
                v-model="password"
                class="form-control"
                placeholder="Mot de passe"
                @keyup.enter="login"
            >
        </div>

        <button
            class="btn btn-primary w-100 btn-lg"
            @click="login"
        >
            Se connecter
        </button>

    </div>

</div>

</template>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  background: linear-gradient(135deg, #17140f 0%, #2c281f 100%);
}

.login-card {
  width: 100%;
  max-width: 430px;
  background: #fff;
  border-radius: 20px;
  padding: 40px 36px;
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.35);
}

.login-brand {
  display: flex;
  align-items: center;
  gap: 12px;
  justify-content: center;
  margin-bottom: 24px;
}

.login-mark {
  width: 40px;
  height: 40px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 11px;
  background: #bf571c;
  color: #fff;
  font-weight: 800;
}

.login-name {
  font-weight: 700;
  letter-spacing: 0.12em;
  color: #2c281f;
}

.login-title {
  text-align: center;
  font-size: 1.4rem;
  font-weight: 700;
  color: #2c281f;
  margin-bottom: 4px;
}

.login-subtitle {
  text-align: center;
  color: #8a8272;
  margin-bottom: 28px;
}

.login-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #6b6353;
  margin-bottom: 6px;
}
</style>
