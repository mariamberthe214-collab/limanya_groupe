import axios from 'axios'
import router from '../router'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/limanya-api/index.php',
  headers: {
    'Content-Type': 'application/json',
  },
  withCredentials: true,
})

api.interceptors.request.use((config) => {
  if (typeof config.url === 'string' && !config.url.startsWith('http')) {
    config.url = config.url.replace(/^\/+/, '')
  }
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 403 && router.currentRoute?.value?.path?.startsWith('/admin')) {
      localStorage.removeItem('admin_user')
      router.push('/admin/login')
    }
    return Promise.reject(error)
  },
)

export default api