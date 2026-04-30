import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

if (!process.env.APP_URL) {
  process.env.APP_URL = 'http://example.test';
}

export default defineConfig({
  base: '/wp-content/themes/wp-craft/public/build/',
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/css/app.css',
        'resources/css/woocommerce.css',
        'resources/js/app.js',
        'resources/css/editor.css',
        'resources/js/editor.js',
      ],
      refresh: true,
    }),
    wordpressPlugin(),
    wordpressThemeJson({
      disableTailwindColors:      true,
      disableTailwindFonts:       false,
      disableTailwindFontSizes:   false,
      disableTailwindBorderRadius: false,
    }),
  ],
  resolve: {
    alias: {
      '~':        '/resources/js',
      '@scripts': '/resources/js',
      '@styles':  '/resources/css',
      '@fonts':   '/resources/fonts',
      '@images':  '/resources/images',
    },
  },
  build: {
    rollupOptions: {
      output: {
        manualChunks(id) {
          // Group motion-related dynamic modules into one lazy chunk.
          if (
            id.includes('/resources/js/modules/animations.js')
            || id.includes('/resources/js/modules/scroll-effects.js')
            || id.includes('/resources/js/modules/magnetic-hover.js')
          ) {
            return 'motion'
          }

          // Keep vendor bundles split by usage profile.
          if (id.includes('alpinejs') || id.includes('@alpinejs/')) return 'vendor-alpine'
          if (id.includes('gsap')) return 'vendor-gsap'
          if (id.includes('swiper')) return 'vendor-swiper'
        },
      },
    },
    cssCodeSplit:        true,
    sourcemap:           false,
    reportCompressedSize: false,
  },
  optimizeDeps: {
    include: [
      'alpinejs',
      '@alpinejs/collapse',
      '@alpinejs/focus',
      'gsap',
      'gsap/ScrollTrigger',
    ],
  },
});
