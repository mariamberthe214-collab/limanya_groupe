<script setup>
defineProps({
  show: Boolean,
  title: {
    type: String,
    default: 'Confirmation'
  },
  message: {
    type: String,
    default: 'Êtes-vous sûr de vouloir continuer ?'
  },
  confirmText: {
    type: String,
    default: 'Confirmer'
  },
  cancelText: {
    type: String,
    default: 'Annuler'
  },
  confirmClass: {
    type: String,
    default: 'btn-danger'
  }
})

const emit = defineEmits(['confirm', 'cancel'])
</script>

<template>

<Teleport to="body">

<div
    v-if="show"
    class="modal fade show d-block"
    tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content shadow">

            <div class="modal-header">

                <h5 class="modal-title">

                    {{ title }}

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    @click="emit('cancel')">
                </button>

            </div>

            <div class="modal-body">

                <p class="mb-0">
                    {{ message }}
                </p>

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    @click="emit('cancel')">

                    {{ cancelText }}

                </button>

                <button
                    :class="['btn', confirmClass]"
                    @click="emit('confirm')">

                    {{ confirmText }}

                </button>

            </div>

        </div>

    </div>

</div>

<div
    v-if="show"
    class="modal-backdrop fade show">
</div>

</Teleport>

</template>