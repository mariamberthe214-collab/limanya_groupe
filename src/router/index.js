import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import About from '../views/About.vue'
import Forage from '../views/Forage.vue'
import Btp from '../views/Btp.vue'
import Assainissement from '../views/Assainissement.vue'
import Materiels from '../views/Materiels.vue'
import EtudesGeophysiques from '../views/EtudesGeophysiques.vue'
import Realisations from '../views/Realisations.vue'
import RealisationDetail from '../views/RealisationDetail.vue'
import Actualites from '../views/Actualites.vue'
import ActualiteDetail from '../views/ActualiteDetail.vue'
import Contact from '../views/Contact.vue'
import DemandeDevis from '../views/DemandeDevis.vue'
import NotFound from '../views/NotFound.vue'
import AdminLogin from '../views/AdminLogin.vue'
import Dashboard from '../views/admin/Dashboard.vue'
import AdminActualites from '../views/admin/AdminActualites.vue'
import AjouterActualite from '../views/admin/AjouterActualite.vue'
import ModifierActualite from '../views/admin/ModifierActualite.vue'
import AdminLayout from '../layouts/AdminLayout.vue'
import VoirContact from '../views/admin/VoirContact.vue'
import VoirDevis from '../views/admin/VoirDevis.vue'
import AdminDemandes from '../views/admin/AdminDemandes.vue'
import AdminRealisations from '../views/admin/AdminRealisations.vue'
import AjouterRealisation from '../views/admin/AjouterRealisation.vue'
import ModifierRealisation from '../views/admin/ModifierRealisation.vue'
import AdminEquipements from '../views/admin/AdminEquipements.vue'
import AjouterEquipement from '../views/admin/AjouterEquipement.vue'
import ModifierEquipement from '../views/admin/ModifierEquipement.vue'
import AdminUtilisateurs from '../views/admin/AdminUtilisateurs.vue'


const routes = [

  { path: '/', name: 'home', component: Home, meta: { title: 'LIMANYA GROUPE — Forages Hydrauliques en Côte d\'Ivoire' } },
  { path: '/realisations-btp-services-de-construction', name: 'realisations', component: Realisations, meta: { title: 'Nos Réalisations — LIMANYA GROUPE' } },
  { path: '/realisations/:id', name: 'realisation-detail', component: RealisationDetail, meta: { title: 'Réalisation — LIMANYA GROUPE' } },
  { path: '/materiels-de-forages', name: 'materiels', component: Materiels, meta: { title: 'Vente de Matériels — LIMANYA GROUPE' } },
  { path: '/contacts', name: 'contacts', component: Contact, meta: { title: 'Contact — LIMANYA GROUPE' } },
  { path: '/demande-de-devis', name: 'demande-de-devis', component: DemandeDevis, meta: { title: 'Demande de devis — LIMANYA GROUPE' } },
  { path: '/apropos', name: 'about', component: About, meta: { title: 'À propos — LIMANYA GROUPE' } },
  { path: '/forage', name: 'forage', component: Forage, meta: { title: 'Forages Hydrauliques — LIMANYA GROUPE' } },
  { path: '/btp', name: 'btp', component: Btp, meta: { title: 'BTP & Génie Civil — LIMANYA GROUPE' } },
  { path: '/assainissement', name: 'assainissement', component: Assainissement, meta: { title: 'Assainissement — LIMANYA GROUPE' } },
  { path: '/etudes-geophysiques', name: 'etudes-geophysiques', component: EtudesGeophysiques, meta: { title: 'Études Géophysiques — LIMANYA GROUPE' } },
  { path: '/location', redirect: '/materiels-de-forages' },
  { path: '/actualites', name: 'actualites', component: Actualites, meta: { title: 'Actualités — LIMANYA GROUPE' } },
  { path: '/actualites/:id', name: 'actualite-detail', component: ActualiteDetail, meta: { title: 'Article — LIMANYA GROUPE' } },
  { path: '/admin/login', name: 'admin-login',component: AdminLogin, meta: { title: 'Connexion admin — LIMANYA GROUPE' }},
 {
  path: '/admin',
  component: AdminLayout,
  meta: { requiresAuth: true },
  children: [
    {
      path: 'dashboard',
      name: 'dashboard',
      component: Dashboard,
      meta: { roles: ['admin'] }
    },
    {
      path: 'actualites',
      component: AdminActualites,
      meta: { roles: ['gestionnaire_contenu'] }
    },
    {
      path: 'actualites/ajouter',
      component: AjouterActualite,
      meta: { roles: ['gestionnaire_contenu'] }
    },
    {
      path: 'actualites/modifier/:id',
      component: ModifierActualite,
      meta: { roles: ['gestionnaire_contenu'] }
    },
    {
      path: 'demandes',
      component: AdminDemandes,
      meta: { roles: ['gestionnaire_commercial'] }
    },
    {
      path: 'contacts',
      redirect: { path: '/admin/demandes', query: { type: 'message' } },
      meta: { roles: ['gestionnaire_commercial'] }
    },
    {
        path: 'contacts/:id',
        component: VoirContact,
        meta: { roles: ['gestionnaire_commercial'] }
    },
    {
      path: 'realisations',
      component: AdminRealisations,
      meta: { roles: ['gestionnaire_contenu'] }
    },
    {
      path: 'realisations/ajouter',
      component: AjouterRealisation,
      meta: { roles: ['gestionnaire_contenu'] }
    },
    {
      path: 'realisations/modifier/:id',
      component: ModifierRealisation,
      meta: { roles: ['gestionnaire_contenu'] }
    },
    {
      path: 'equipements',
      component: AdminEquipements,
      meta: { roles: ['gestionnaire_commercial'] }
    },
    {
      path: 'equipements/ajouter',
      component: AjouterEquipement,
      meta: { roles: ['gestionnaire_commercial'] }
    },
    {
      path: 'equipements/modifier/:id',
      component: ModifierEquipement,
      meta: { roles: ['gestionnaire_commercial'] }
    },
    {
      path: 'devis',
      redirect: { path: '/admin/demandes', query: { type: 'devis' } },
      meta: { roles: ['gestionnaire_commercial'] }
    },
    {
      path: 'devis/:id',
      component: VoirDevis,
      meta: { roles: ['gestionnaire_commercial'] }
    },
    {
      path: 'utilisateurs',
      component: AdminUtilisateurs,
      meta: { roles: ['admin'] }
    }
  ]
},
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFound,
    meta: { title: 'Page introuvable — LIMANYA GROUPE' },
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    // Navigation avec le bouton précédent/suivant du navigateur : on restaure la position
    if (savedPosition) {
      return savedPosition
    }
    // Ancre dans l'URL (ex: #section) : on scroll jusqu'à l'élément visé
    if (to.hash) {
      return { el: to.hash, behavior: 'smooth' }
    }
    // Dans tous les autres cas (clic sur un lien, changement de page) : retour en haut
    return { top: 0 }
  },
})

router.afterEach((to) => {
  document.title = to.meta?.title || 'LIMANYA GROUPE'
})

router.beforeEach((to) => {
  let utilisateur = null
  try {
    utilisateur = JSON.parse(localStorage.getItem('admin_user') || 'null')
  } catch {
    localStorage.removeItem('admin_user')
  }

  if (to.matched.some((record) => record.meta.requiresAuth) && !utilisateur) {
    return {
      name: 'admin-login',
      query: { redirect: to.fullPath },
    }
  }

  const rolesAutorises = to.meta.roles
  const role = utilisateur?.role === 'editeur' ? 'gestionnaire_contenu' : utilisateur?.role
  if (rolesAutorises && role !== 'admin' && !rolesAutorises.includes(role)) {
    return role === 'gestionnaire_commercial'
      ? '/admin/devis'
      : role === 'gestionnaire_contenu'
        ? '/admin/realisations'
        : '/admin/login'
  }

  if (to.name === 'admin-login' && utilisateur) {
    return role === 'admin'
      ? { name: 'dashboard' }
      : role === 'gestionnaire_commercial'
        ? '/admin/devis'
        : '/admin/realisations'
  }
})

export default router