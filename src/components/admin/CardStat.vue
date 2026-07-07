<script setup>
defineProps({
  titre: String,
  nombre: Number,
  couleur: String,
  icone: String
})
</script>

<template>

<div class="card shadow border-0 h-100">

    <div class="card-body d-flex justify-content-between align-items-center">

        <div>

            <small class="text-muted">
                {{ titre }}
            </small>

            <h2 class="fw-bold mt-2">
                {{ nombre }}
            </h2>

        </div>

        <div
            class="icone"
            :class="couleur">

            {{ icone }}

        </div>

    </div>

</div>

</template>

<style scoped>

.icone{

    width:60px;
    height:60px;
    border-radius:16px;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:28px;

}

.primary{

    background:#f6e7da;

}

.success{

    background:#e2efe4;

}

.warning{

    background:#fbeecd;

}

.danger{

    background:#f7e0dc;

}

</style>
