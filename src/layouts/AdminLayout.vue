<script setup>
import { ref, provide } from 'vue'
import Sidebar from '../components/admin/Sidebar.vue'
import Navbar from '../components/admin/Navbar.vue'

const sidebarOpen = ref(false)
const toggleSidebar = () => { sidebarOpen.value = !sidebarOpen.value }
const closeSidebar = () => { sidebarOpen.value = false }

provide('sidebarOpen', sidebarOpen)
provide('toggleSidebar', toggleSidebar)
provide('closeSidebar', closeSidebar)
</script>

<template>

<div class="admin-layout">

    <Sidebar />
    <div
        class="sidebar-overlay"
        :class="{ 'is-visible': sidebarOpen }"
        @click="closeSidebar"></div>

    <div class="admin-page">

        <Navbar />

        <div class="admin-content">

            <router-view />

        </div>

    </div>

</div>

</template>

<style scoped>

.admin-layout{

    display:flex;
    min-height:100vh;
    background:#f7f4ee;

}

.admin-page{

    flex:1;
    display:flex;
    flex-direction:column;
    min-width:0;

}

.admin-content{

    padding:30px;

}

.sidebar-overlay{

    display:none;

}

@media (max-width: 991px) {

    .sidebar-overlay.is-visible{

        display:block;
        position:fixed;
        inset:0;
        background:rgba(20,17,13,.55);
        z-index:1040;

    }

    .admin-content{

        padding:18px;

    }

}

</style>
