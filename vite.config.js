import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from "@sveltejs/vite-plugin-svelte";
import { glob } from "glob";
import path from "path";

export default defineConfig({
    resolve: {
        alias: {
            "@components": path.resolve(
                __dirname,
                "/resources/js/components",
            ),
            "@ui": path.resolve(
                __dirname,
                "/resources/js/ui",
            ),
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                "resources/js/mingle.svelte.js",
                ...glob.sync("resources/js/components/**/*.svelte"),
            ],
            refresh: true,
        }),
        svelte(),
    ],
});
