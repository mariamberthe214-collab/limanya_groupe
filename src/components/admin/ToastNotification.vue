<script setup>
const props = defineProps({
  show: Boolean,
  message: String,
  type: {
    type: String,
    default: 'success'
  }
})

const emit = defineEmits(['close'])
</script>

<template>
  <Transition name="toast">
    <div
      v-if="show"
      class="toast-container position-fixed top-0 end-0 p-3"
      style="z-index:9999">

      <div
        class="toast show align-items-center text-white border-0"
        :class="{
          'bg-success': type === 'success',
          'bg-danger': type === 'error',
          'bg-warning text-dark': type === 'warning',
          'bg-info': type === 'info'
        }">

        <div class="d-flex">

          <div class="toast-body">

            {{ message }}

          </div>

          <button
            class="btn-close btn-close-white me-2 m-auto"
            @click="emit('close')">
          </button>

        </div>

      </div>

    </div>
  </Transition>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active{
  transition: all .3s ease;
}

.toast-enter-from{
  opacity:0;
  transform:translateX(100%);
}

.toast-leave-to{
  opacity:0;
  transform:translateX(100%);
}
</style>