import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

if (!process.env.APP_URL) {
  process.env.APP_URL = 'http://example.test';
}

export default defineConfig({
  base: '/app/themes/sage-theme/public/build/',
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/editor.css',
        'resources/js/editor.js',
      ],
      refresh: true,
    }),
    wordpressPlugin(),
    wordpressThemeJson({
      disableTailwindColors:      false,
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
