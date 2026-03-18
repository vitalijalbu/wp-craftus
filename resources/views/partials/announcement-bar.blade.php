{{--
  Announcement / Promo Bar
  - Dismissible via Alpine; stores dismissal in sessionStorage.
  - Text + optional CTA configured via WordPress Customizer.
  - Usage: @include('partials.announcement-bar') just above the header in sections/header.blade.php
  - Customizer keys: announcement_bar_text, announcement_bar_cta_text, announcement_bar_cta_url, announcement_bar_active
--}}
@php
  $active   = get_theme_mod('announcement_bar_active', false);
  $text     = get_theme_mod('announcement_bar_text', '');
  $cta_text = get_theme_mod('announcement_bar_cta_text', '');
  $cta_url  = esc_url(get_theme_mod('announcement_bar_cta_url', ''));
  $bar_id   = 'annbar-' . md5($text . $cta_url); // changes when content changes → auto re-shows
@endphp

@if($active && $text)
  <div
    x-data="announcementBar('{{ esc_js($bar_id) }}')"
    x-init="init()"
    x-show="visible"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-full"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-full"
    role="banner"
    aria-label="{{ __('Annuncio promozionale', 'sage') }}"
    class="relative bg-ink text-white text-center px-10 py-2.5"
    style="display:none"
  >
    <p class="font-sans text-xs font-medium tracking-wide inline">
      {!! wp_kses_post($text) !!}
      @if($cta_text && $cta_url)
        <a href="{{ $cta_url }}"
           class="ml-3 font-semibold underline underline-offset-2 hover:text-gold transition-colors">
          {{ esc_html($cta_text) }}
        </a>
      @endif
    </p>

    <button
      @click="dismiss()"
      type="button"
      aria-label="{{ __('Chiudi annuncio', 'sage') }}"
      class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors p-1"
    >
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
           fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
           aria-hidden="true" focusable="false">
        <path d="M18 6 6 18M6 6l12 12"/>
      </svg>
    </button>
  </div>
@endif

<script>
  function announcementBar(id) {
    return {
      visible: false,
      id,
      init() {
        this.visible = sessionStorage.getItem('annbar_dismissed') !== id;
      },
      dismiss() {
        sessionStorage.setItem('annbar_dismissed', this.id);
        this.visible = false;
      },
    };
  }
</script>
