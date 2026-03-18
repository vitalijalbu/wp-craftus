{{--
  Back-to-top button
  Alpine: shows after scrolling 400px, smooth-scrolls to top.
  Usage: @include('partials.back-to-top') — place before </body> in layouts/app.blade.php
--}}
<button
  x-data="{ visible: false }"
  x-init="window.addEventListener('scroll', () => { visible = window.scrollY > 400 }, { passive: true })"
  x-show="visible"
  x-transition:enter="transition ease-out duration-200"
  x-transition:enter-start="opacity-0 translate-y-2"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100 translate-y-0"
  x-transition:leave-end="opacity-0 translate-y-2"
  @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
  type="button"
  aria-label="{{ __('Torna in cima', 'sage') }}"
  class="fixed bottom-6 right-6 z-40 w-11 h-11 flex items-center justify-center
         bg-ink text-white shadow-lg
         hover:bg-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary
         transition-colors"
  style="display: none"
>
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
       fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
       aria-hidden="true" focusable="false">
    <path d="M18 15l-6-6-6 6"/>
  </svg>
</button>
