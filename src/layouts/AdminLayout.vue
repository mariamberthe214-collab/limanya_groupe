<script setup>
import Sidebar from '../components/admin/Sidebar.vue'
import Navbar from '../components/admin/Navbar.vue'
</script>

<template>

<div class="admin-layout">

    <Sidebar />

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

}

.admin-content{

    padding:30px;

}

</style>
