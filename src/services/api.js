import axios from 'axios'
import router from '../router'

const api = axios.create({
  baseURL: 'http://localhost/limanya_groupe/limanya-api/index.php',
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
      router.push('/admin/login')
    }
    return Promise.reject(error)
  },
)

export default api