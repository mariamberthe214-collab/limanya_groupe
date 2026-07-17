import api from '../services/api'

/**
 * Construit l'URL complète d'une image stockée sur le serveur (ex: "/uploads/img_123.jpg")
 * à partir du chemin relatif renvoyé par l'API.
 * @param {string} path
 * @returns {string|null}
 */
export function getImageUrl(path) {
  if (!path) return null
  if (path.startsWith('http://') || path.startsWith('https://')) return path

  // baseURL peut être "http://localhost/limanya_groupe/limanya-api/index.php"
  // ou "http://localhost:8000" -> on retire "index.php" final pour obtenir la racine de l'API
  const base = (api.defaults.baseURL || '').replace(/index\.php\/?$/, '').replace(/\/$/, '')
  return `${base}${path}`
}

/**
 * Téléverse un fichier image vers le serveur.
 * @param {File} file
 * @returns {Promise<string>} le chemin relatif de l'image (ex: "/uploads/img_123.jpg")
 */
export async function uploadImage(file) {
  const formData = new FormData()
  formData.append('file', file)

  const response = await api.post('/upload', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  })

  return response.data.path
}
