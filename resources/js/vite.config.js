import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { fileURLToPath, URL } from "url";

export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: [
      { find: '@', replacement: fileURLToPath(new URL('./src', import.meta.url)) },
      { find: '@assets', replacement: fileURLToPath(new URL('./src/assets', import.meta.url)) },
      { find: '@components', replacement: fileURLToPath(new URL('./src/app/components', import.meta.url)) },
      { find: '@pages', replacement: fileURLToPath(new URL('./src/app/pages', import.meta.url)) },
      { find: '@helper', replacement: fileURLToPath(new URL('./src/app/helper', import.meta.url)) },
      { find: '@ducks', replacement: fileURLToPath(new URL('./src/redux/ducks', import.meta.url)) },
    ],
  },
  server: {
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },
  build: {
    outDir: '../../public',
    emptyOutDir: false,
  },
})
