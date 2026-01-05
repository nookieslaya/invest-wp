import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin'
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin'

export default defineConfig({
  // Ścieżka z punktu widzenia PRZEGLĄDARKI, nie systemu plików
  base: '/wp-content/themes/NAZWA_MOTYWU/public/build/',

  plugins: [
    tailwindcss(),

    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/editor.css',
        'resources/js/editor.js',
      ],
      // auto-reload przy zmianie Blade
      refresh: [
        {
          paths: ['resources/views/**/*.blade.php'],
          config: { delay: 0 },
        },
      ],
    }),

    wordpressPlugin(),

    wordpressThemeJson({
      disableTailwindColors: false,
      disableTailwindFonts: false,
      disableTailwindFontSizes: false,
    }),
  ],

  server: {
    host: 'localhost',
    port: 5173,
    hmr: {
      // z docsów: pod WSL / innymi setupami HMR ma gadać z localhost
      host: 'localhost',
    },
    watch: {
      usePolling: false,
    },
  },
})
