import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { nodePolyfills } from 'vite-plugin-node-polyfills'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        // Normally you would not need this plugin, but because of the
        // yivi-client package, we need to polyfill some node modules.
        nodePolyfills(),
    ],
});
