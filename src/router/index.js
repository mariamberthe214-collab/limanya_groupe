import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import About from '../views/About.vue'
import Forage from '../views/Forage.vue'
import Btp from '../views/Btp.vue'
import Assainissement from '../views/Assainissement.vue'
import Location from '../views/Location.vue'
import Realisations from '../views/Realisations.vue'
import Actualites from '../views/Actualites.vue'
import Contact from '../views/Contact.vue'
import AdminLogin from '../views/AdminLogin.vue'
import Dashboard from '../views/admin/Dashboard.vue'
import AdminActualites from '../views/admin/AdminActualites.vue'
import AjouterActualite from '../views/admin/AjouterActualite.vue'
import ModifierActualite from '../views/admin/ModifierActualite.vue'
import AdminLayout from '../layouts/AdminLayout.vue'
import AdminContact from '../views/admin/AdminContact.vue'
import VoirContact from '../views/admin/VoirContact.vue'
import AdminRealisations from '../views/admin/AdminRealisations.vue'
import AjouterRealisation from '../views/admin/AjouterRealisation.vue'
import ModifierRealisation from '../views/admin/ModifierRealisation.vue'
import AdminEquipements from '../views/admin/AdminEquipements.vue'
import AjouterEquipement from '../views/admin/AjouterEquipement.vue'
import ModifierEquipement from '../views/admin/ModifierEquipement.vue'
import AdminDevis from '../views/admin/AdminDevis.vue'
import AdminUtilisateurs from '../views/admin/AdminUtilisateurs.vue'


const routes = [

  { path: '/', name: 'home', component: Home },
  { path: '/realisations-btp-services-de-construction', name: 'realisations', component: Realisations },
  { path: '/materiels-de-forages', name: 'materiels', component: Location },
  { path: '/contacts', name: 'contacts', component: Contact },
  { path: '/apropos', name: 'about', component: About },
  { path: '/forage', name: 'forage', component: Forage },
  { path: '/btp', name: 'btp', component: Btp },
  { path: '/assainissement', name: 'assainissement', component: Assainissement },
  { path: '/location', name: 'location', component: Location },
  { path: '/actualites', name: 'actualites', component: Actualites },
  { path: '/admin/login', name: 'admin-login',component: AdminLogin},
 {
  path: '/admin',
  component: AdminLayout,
  children: [
    {
      path: 'dashboard',
      name: 'dashboard',
      component: Dashboard
    },
    {
      path: 'actualites',
      component: AdminActualites
    },
    {
      path: 'actualites/ajouter',
      component: AjouterActualite
    },
    {
      path: 'actualites/modifier/:id',
      component: ModifierActualite
    },
    {
      path: 'contacts',
      component: AdminContact
    },
    {
        path: 'contacts/:id',
        component: VoirContact
    },
    {
      path: 'realisations',
      component: AdminRealisations
    },
    {
      path: 'realisations/ajouter',
      component: AjouterRealisation
    },
    {
      path: 'realisations/modifier/:id',
      component: ModifierRealisation
    },
    {
      path: 'equipements',
      component: AdminEquipements
    },
    {
      path: 'equipements/ajouter',
      component: AjouterEquipement
    },
    {
      path: 'equipements/modifier/:id',
      component: ModifierEquipement
    },
    {
      path: 'devis',
      component: AdminDevis
    },
    {
      path: 'utilisateurs',
      component: AdminUtilisateurs
    }
  ]
}
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
