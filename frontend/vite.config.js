import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [vue()],
    server: {
        hmr: {
            overlay: true // Keep error overlay enabled (or set to false to disable)
        }
    }
});