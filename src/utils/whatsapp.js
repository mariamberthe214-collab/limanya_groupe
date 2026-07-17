// Numéro WhatsApp officiel de LIMANYA Groupe (format international, sans le "+")
export const WHATSAPP_NUMBER = '2250798449360'

/**
 * Normalise un numéro de téléphone saisi par un client (formats variés :
 * "07 98 44 93 60", "0798449360", "+225 07 98 44 93 60", "225798449360"...)
 * vers le format international attendu par wa.me (sans "+", sans espaces).
 * Par défaut, suppose un numéro ivoirien si aucun indicatif n'est détecté.
 * @param {string} phone
 * @returns {string}
 */
export function normalizePhone(phone = '') {
  let digits = String(phone).trim().replace(/[^\d+]/g, '')

  if (digits.startsWith('+')) {
    digits = digits.slice(1)
  } else if (digits.startsWith('00')) {
    digits = digits.slice(2)
  }

  if (digits.startsWith('225')) {
    return digits
  }

  // Numéro local ivoirien (10 chiffres commençant par 0) -> on retire le 0 et on ajoute 225
  if (digits.startsWith('0') && digits.length === 10) {
    return `225${digits.slice(1)}`
  }

  // Numéro déjà sans le 0 initial (9 chiffres) -> on ajoute juste l'indicatif
  if (digits.length === 9) {
    return `225${digits}`
  }

  // Cas non reconnu : on retourne tel quel, wa.me tentera de l'interpréter
  return digits
}

/**
 * Construit un lien wa.me avec un message pré-rempli, vers un numéro donné
 * (ou vers le numéro officiel LIMANYA Groupe par défaut).
 * @param {string} message - Le texte à pré-remplir dans WhatsApp.
 * @param {string} [phone] - Numéro de destination. Par défaut : numéro officiel.
 * @returns {string} L'URL wa.me prête à ouvrir.
 */
export function buildWhatsAppLink(message = '', phone = WHATSAPP_NUMBER) {
  const numero = normalizePhone(phone)
  const texte = encodeURIComponent(message)
  return `https://wa.me/${numero}${texte ? `?text=${texte}` : ''}`
}

/**
 * Construit un lien wa.me pour répondre directement à un client, en utilisant
 * le numéro qu'il a lui-même renseigné (et non le numéro de l'entreprise).
 * @param {string} clientPhone - Numéro saisi par le client.
 * @param {string} message - Message pré-rempli.
 * @returns {string}
 */
export function buildWhatsAppReplyLink(clientPhone, message = '') {
  return buildWhatsAppLink(message, clientPhone)
}

/**
 * Ouvre WhatsApp dans un nouvel onglet avec un message pré-rempli.
 * @param {string} message
 * @param {string} [phone]
 */
export function openWhatsApp(message = '', phone = WHATSAPP_NUMBER) {
  window.open(buildWhatsAppLink(message, phone), '_blank')
}
