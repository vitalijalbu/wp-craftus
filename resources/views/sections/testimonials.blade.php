@php
  // Parameters
  $section_label    = $section_label    ?? __('Testimonianze', 'sage');
  $section_title    = $section_title    ?? __('Cosa dicono di noi', 'sage');
  $section_subtitle = $section_subtitle ?? '';
  $bg               = $bg               ?? 'cream'; // 'surface' | 'cream' | 'ink'

  // Default testimonials — override via $testimonials parameter
  $testimonials = $testimonials ?? [
    [
      'quote'  => __('Prodotti di altissima qualità, il mio cane li adora! La consegna è stata rapidissima e il servizio clienti è stato gentilissimo.', 'sage'),
      'name'   => 'Laura M.',
      'role'   => __('Proprietaria di Labrador', 'sage'),
      'avatar' => '',
      'rating' => 5,
    ],
    [
      'quote'  => __('Finalmente uno shop che capisce le esigenze dei gatti! Ho trovato tutto quello che cercavo e i prezzi sono onesti.', 'sage'),
      'name'   => 'Marco T.',
      'role'   => __('Amante dei gatti', 'sage'),
      'avatar' => '',
      'rating' => 5,
    ],
    [
      'quote'  => __('Ho consigliato a tutti i miei amici. La qualità supera le aspettative e si vede che c\'è davvero passione dietro ogni prodotto.', 'sage'),
      'name'   => 'Alessia R.',
      'role'   => __('Proprietaria di Border Collie', 'sage'),
      'avatar' => '',
      'rating' => 5,
    ],
    [
      'quote'  => __('Assistenza clienti impeccabile. Avevo un dubbio sulla taglia e mi hanno risposto subito con il consiglio perfetto.', 'sage'),
      'name'   => 'Giovanni B.',
      'role'   => __('Proprietario di Golden Retriever', 'sage'),
      'avatar' => '',
      'rating' => 5,
    ],
  ];

  $bg_class     = match($bg) { 'ink' => 'bg-ink', 'surface' => 'bg-surface', default => 'bg-cream' };
  $title_class  = $bg === 'ink' ? 'text-white' : 'text-ink';
  $sub_class    = $bg === 'ink' ? 'text-white/60' : 'text-muted';
@endphp

<section
  id="{{ $section_id ?? 'section-testimonials' }}"
  class="section {{ $bg_class }} overflow-hidden"
  aria-roledescription="{{ __('carosello', 'sage') }}"
  aria-label="{{ strip_tags($section_title) }}"
  data-testimonials
>
  <div class="container">

    {{-- Header + nav --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
      <div>
        @if($section_label)
          <span class="section-label" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
        @if($section_subtitle)
          <p class="section-subtitle mt-3 {{ $sub_class }}" data-scroll="slide-up">{{ $section_subtitle }}</p>
        @endif
      </div>

      {{-- Custom arrows --}}
      <div class="flex items-center gap-2 shrink-0" data-scroll="fade">
        <button
          class="swiper-button-prev btn-nav static {{ $bg === 'ink' ? 'btn-nav--light' : '' }}"
          aria-label="{{ __('Recensione precedente', 'sage') }}"
          type="button"
        >
          <x-icons.chevron-left class="size-4" stroke-width="2" />
        </button>
        <button
          class="swiper-button-next btn-nav static {{ $bg === 'ink' ? 'btn-nav--light' : '' }}"
          aria-label="{{ __('Recensione successiva', 'sage') }}"
          type="button"
        >
          <x-icons.chevron-right class="size-4" stroke-width="2" />
        </button>
      </div>
    </div>

    {{-- Swiper --}}
    <div class="swiper js-testimonials-swiper overflow-visible">
      <div class="swiper-wrapper" aria-live="polite">
        @foreach($testimonials as $t)
          <div
            class="swiper-slide h-auto"
            role="group"
            aria-roledescription="{{ __('slide', 'sage') }}"
            aria-label="{{ sprintf(__('Recensione %d di %d', 'sage'), $loop->iteration, count($testimonials)) }}"
          >
            <article
              class="testimonial-card h-full {{ $bg === 'ink' ? 'bg-white/5 border-white/10' : '' }}"
              aria-label="{{ sprintf(__('Recensione di %s', 'sage'), $t['name'] ?? '') }}"
            >

              {{-- Star rating --}}
              @if(!empty($t['rating']) && $t['rating'] > 0)
                <div
                  class="flex gap-0.5 mb-5"
                  aria-label="{{ sprintf(__('Valutazione: %d su 5 stelle', 'sage'), $t['rating']) }}"
                  role="img"
                >
                  @for($i = 0; $i < 5; $i++)
                    <x-icons.star class="size-4 {{ $i < $t['rating'] ? 'text-primary fill-primary' : 'text-border fill-border' }}" />
                  @endfor
                </div>
              @endif

              {{-- Quote --}}
              <blockquote
                class="testimonial-card__quote {{ $bg === 'ink' ? 'text-white' : '' }}"
                cite=""
              >
                <p>{{ $t['quote'] ?? '' }}</p>
                <cite class="sr-only">{{ $t['name'] ?? '' }}</cite>
              </blockquote>

              {{-- Author --}}
              <footer class="testimonial-card__author">
                @if(!empty($t['avatar']))
                  <img
                    src="{{ $t['avatar'] }}"
                    alt="{{ esc_attr($t['name'] ?? '') }}"
                    class="testimonial-card__avatar"
                    loading="lazy"
                    width="40"
                    height="40"
                  >
                @else
                  <div
                    class="testimonial-card__avatar bg-cream flex items-center justify-center shrink-0"
                    aria-hidden="true"
                  >
                    <span class="font-serif text-primary text-sm font-medium">
                      {{ mb_substr($t['name'] ?? 'A', 0, 1) }}
                    </span>
                  </div>
                @endif
                <div>
                  <p class="testimonial-card__name {{ $bg === 'ink' ? 'text-white' : '' }}">
                    {{ $t['name'] ?? '' }}
                  </p>
                  @if(!empty($t['role']))
                    <p class="testimonial-card__role {{ $bg === 'ink' ? 'text-white/40' : '' }}">
                      {{ $t['role'] }}
                    </p>
                  @endif
                </div>
              </footer>

            </article>
          </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      <div class="swiper-pagination mt-10 flex justify-center gap-1.5"></div>
    </div>

  </div>
</section>
