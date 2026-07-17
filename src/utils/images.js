import api from '../services/api'

// Identifiants Cloudinary de LIMANYA Groupe
const CLOUDINARY_CLOUD_NAME = 'dwyibyy50'
const CLOUDINARY_UPLOAD_PRESET = 'sxfwn8vq'
const CLOUDINARY_UPLOAD_URL = `https://api.cloudinary.com/v1_1/${CLOUDINARY_CLOUD_NAME}/auto/upload`

/**
 * Construit l'URL complète d'une image/vidéo à afficher.
 * - Si c'est déjà une URL Cloudinary (ou toute URL complète), on la retourne telle quelle.
 * - Si c'est un ancien chemin local (ex: "/uploads/img_123.jpg"), on le préfixe avec l'API
 *   (pour garder compatibles les médias déjà en place avant Cloudinary).
 * @param {string} path
 * @returns {string|null}
 */
export function getImageUrl(path) {
  if (!path) return null
  if (path.startsWith('http://') || path.startsWith('https://')) return path

  const base = (api.defaults.baseURL || '').replace(/index\.php\/?$/, '').replace(/\/$/, '')
  return `${base}${path}`
}

/**
 * Téléverse un fichier (image ou vidéo) directement vers Cloudinary.
 * @param {File} file
 * @returns {Promise<string>} l'URL sécurisée (https) du fichier hébergé sur Cloudinary
 */
export async function uploadImage(file) {
  const formData = new FormData()
  formData.append('file', file)
  formData.append('upload_preset', CLOUDINARY_UPLOAD_PRESET)

  const response = await fetch(CLOUDINARY_UPLOAD_URL, {
    method: 'POST',
    body: formData,
  })

  if (!response.ok) {
    const errorData = await response.json().catch(() => null)
    throw new Error(errorData?.error?.message || "Erreur lors de l'envoi vers Cloudinary")
  }

  const data = await response.json()
  return data.secure_url
}
