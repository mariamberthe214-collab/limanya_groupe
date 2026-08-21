import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],

  server: {
    proxy: {
      '/limanya-api': {
        target: 'http://localhost/limanya_groupe',
        changeOrigin: true,
      },
    },
  },
})