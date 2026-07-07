<script setup>

defineProps({

    titre: String,

    page: String

})

</script>

<template>

<div class="mb-4">

    <nav>

        <ol class="breadcrumb">

            <li class="breadcrumb-item">

                Dashboard

            </li>

            <li class="breadcrumb-item active">

                {{ page }}

            </li>

        </ol>

    </nav>

    <h2 class="fw-bold">

        {{ titre }}

    </h2>

</div>

</template>