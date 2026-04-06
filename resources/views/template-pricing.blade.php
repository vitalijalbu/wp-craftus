@php
/**
 * Template Name: Pricing
 * Template Post Type: page
 *
 * Pricing page template — configura i piani in Aspetto → Personalizza → Prezzi.
 * Oppure modifica direttamente i dati statici qui sotto.
 */

// Pricing plans — override via Customizer or ACF in a real project
$plans = [
    [
        'name'       => 'Starter',
        'price'      => '€ 29',
        'period'     => '/ mese',
        'desc'       => 'Per piccole imprese che vogliono partire con il piede giusto.',
        'featured'   => false,
        'cta_label'  => 'Inizia gratis',
        'cta_url'    => home_url('/contatti'),
        'features'   => [
            ['active' => true,  'text' => 'Fino a 5 utenti'],
            ['active' => true,  'text' => '10 GB di spazio'],
            ['active' => true,  'text' => 'Supporto email'],
            ['active' => true,  'text' => 'Dashboard analytics'],
            ['active' => false, 'text' => 'API access'],
            ['active' => false, 'text' => 'Account manager dedicato'],
            ['active' => false, 'text' => 'SLA 99.9%'],
        ],
    ],
    [
        'name'       => 'Business',
        'price'      => '€ 99',
        'period'     => '/ mese',
        'desc'       => 'Per team in crescita che hanno bisogno di più potenza e controllo.',
        'featured'   => true,
        'cta_label'  => 'Inizia ora',
        'cta_url'    => home_url('/contatti'),
        'features'   => [
            ['active' => true, 'text' => 'Utenti illimitati'],
            ['active' => true, 'text' => '100 GB di spazio'],
            ['active' => true, 'text' => 'Supporto prioritario 24/5'],
            ['active' => true, 'text' => 'Dashboard analytics avanzato'],
            ['active' => true, 'text' => 'API access completo'],
            ['active' => false, 'text' => 'Account manager dedicato'],
            ['active' => false, 'text' => 'SLA 99.9%'],
        ],
    ],
    [
        'name'       => 'Enterprise',
        'price'      => 'Su misura',
        'period'     => '',
        'desc'       => 'Per grandi aziende con esigenze personalizzate e compliance avanzata.',
        'featured'   => false,
        'cta_label'  => 'Contattaci',
        'cta_url'    => home_url('/contatti'),
        'features'   => [
            ['active' => true, 'text' => 'Utenti illimitati'],
            ['active' => true, 'text' => 'Spazio illimitato'],
            ['active' => true, 'text' => 'Supporto dedicato 24/7'],
            ['active' => true, 'text' => 'Dashboard analytics avanzato'],
            ['active' => true, 'text' => 'API access completo'],
            ['active' => true, 'text' => 'Account manager dedicato'],
            ['active' => true, 'text' => 'SLA 99.9% garantito'],
        ],
    ],
];

// FAQ accordion data
$faqs = [
    ['q' => 'Posso cambiare piano in qualsiasi momento?',
     'a' => 'Sì, puoi fare upgrade o downgrade del tuo piano in qualsiasi momento. Le modifiche entrano in vigore immediatamente e la fatturazione viene adeguata proporzionalmente.'],
    ['q' => 'Qual è la politica di rimborso?',
     'a' => 'Offriamo una garanzia soddisfatti o rimborsati di 30 giorni su tutti i piani. Se non sei soddisfatto, ti rimborsiamo senza fare domande.'],
    ['q' => 'I dati sono al sicuro?',
     'a' => 'Assolutamente. Utilizziamo crittografia AES-256 per i dati a riposo e TLS 1.3 per i dati in transito. Siamo conformi GDPR e ISO 27001.'],
    ['q' => 'È disponibile un periodo di prova gratuito?',
     'a' => 'Sì, tutti i piani includono 14 giorni di prova gratuita. Nessuna carta di credito richiesta.'],
];
@endphp

@extends('layouts.app')

@section('content')

  {{-- Hero --}}
  <div class="bg-cream border-b border-border pt-20 pb-14">
    <div class="max-w-2xl mx-auto px-6 text-center">
      <p class="section-label text-accent">{{ __('Prezzi', 'sage') }}</p>
      <h1 class="font-serif text-[clamp(2rem,4vw,3rem)] font-light text-ink leading-tight mb-4">
        {{ get_the_title() ?: __('Piani semplici, prezzi trasparenti', 'sage') }}
      </h1>
      @php the_excerpt() @endphp
      {{-- Annual/monthly toggle --}}
      <div class="flex items-center justify-center gap-3 mt-8"
           x-data="{ annual: false }">
        <span class="text-sm font-semibold" :class="!annual ? 'text-ink' : 'text-muted'">{{ __('Mensile', 'sage') }}</span>
        <button
          type="button"
          @click="annual = !annual"
          :aria-pressed="annual"
          class="relative w-11 h-6 bg-border rounded-full transition-colors duration-200"
          :class="annual ? 'bg-accent' : 'bg-border'"
          aria-label="{{ __('Passa a fatturazione annuale', 'sage') }}"
        >
          <span
            class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"
            :class="annual ? 'translate-x-5' : 'translate-x-0'"
          ></span>
        </button>
        <span class="text-sm font-semibold" :class="annual ? 'text-ink' : 'text-muted'">
          {{ __('Annuale', 'sage') }}
          <span class="ml-1 text-xs font-semibold tracking-wide uppercase text-success bg-success/10 px-1.5 py-0.5">-20%</span>
        </span>
      </div>
    </div>
  </div>

  {{-- Pricing cards --}}
  <div class="container py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-start">
      @foreach($plans as $plan)
        <div class="pricing-card {{ $plan['featured'] ? 'pricing-card--featured' : '' }}">

          @if($plan['featured'])
            <div class="pricing-badge">{{ __('Più popolare', 'sage') }}</div>
          @endif

          <div class="pricing-card__header">
            <p class="font-sans text-xs font-semibold tracking-[0.2em] uppercase {{ $plan['featured'] ? 'text-white/60' : 'text-muted' }} mb-2">
              {{ esc_html($plan['name']) }}
            </p>
            <div class="flex items-baseline gap-1 mb-3">
              <span class="font-serif text-[clamp(2rem,4vw,2.75rem)] font-light {{ $plan['featured'] ? 'text-white' : 'text-ink' }}">
                {{ esc_html($plan['price']) }}
              </span>
              @if($plan['period'])
                <span class="text-sm {{ $plan['featured'] ? 'text-white/50' : 'text-muted' }}">{{ esc_html($plan['period']) }}</span>
              @endif
            </div>
            <p class="text-sm {{ $plan['featured'] ? 'text-white/70' : 'text-muted' }} leading-relaxed">
              {{ esc_html($plan['desc']) }}
            </p>
          </div>

          <ul class="pricing-card__features">
            @foreach($plan['features'] as $feature)
              <li class="flex items-start gap-2.5 py-2 border-b {{ $plan['featured'] ? 'border-white/10' : 'border-border' }} last:border-0">
                @if($feature['active'])
                  <x-icons.check class="w-4 h-4 mt-0.5 shrink-0 {{ $plan['featured'] ? 'text-white' : 'text-success' }}" />
                  <span class="text-sm {{ $plan['featured'] ? 'text-white/80' : 'text-ink' }}">{{ esc_html($feature['text']) }}</span>
                @else
                  <x-icons.x-mark class="w-4 h-4 mt-0.5 shrink-0 {{ $plan['featured'] ? 'text-white/20' : 'text-border' }}" />
                  <span class="text-sm {{ $plan['featured'] ? 'text-white/30' : 'text-muted/50' }} line-through">{{ esc_html($feature['text']) }}</span>
                @endif
              </li>
            @endforeach
          </ul>

          <div class="mt-8">
            <a href="{{ esc_url($plan['cta_url']) }}"
               class="{{ $plan['featured'] ? 'btn-outline-white w-full justify-center' : 'btn-outline w-full justify-center' }}">
              {{ esc_html($plan['cta_label']) }}
            </a>
          </div>

        </div>
      @endforeach
    </div>

    {{-- Enterprise CTA band --}}
    <div class="mt-12 bg-cream border border-border px-8 py-7 flex flex-col md:flex-row items-center justify-between gap-5">
      <div>
        <p class="font-serif text-lg font-light text-ink mb-1">{{ __('Hai esigenze particolari?', 'sage') }}</p>
        <p class="text-sm text-muted">{{ __('Il piano Enterprise si adatta completamente al tuo business. Parla con il nostro team.', 'sage') }}</p>
      </div>
      <a href="{{ esc_url(home_url('/contatti')) }}" class="btn-primary shrink-0">{{ __('Richiedi un preventivo', 'sage') }}</a>
    </div>
  </div>

  {{-- FAQ --}}
  <div class="max-w-2xl mx-auto px-6 pb-20">
    <h2 class="font-serif text-[clamp(1.5rem,3vw,2rem)] font-light text-ink text-center mb-10">
      {{ __('Domande frequenti', 'sage') }}
    </h2>
    <div class="space-y-0">
      @foreach($faqs as $i => $faq)
        <div class="faq-item" x-data="{ open: false }">
          <button
            type="button"
            class="faq-item__trigger"
            @click="open = !open"
            :aria-expanded="open"
            aria-controls="faq-pricing-{{ $i }}"
          >
            <span>{{ esc_html($faq['q']) }}</span>
            <x-icons.plus class="w-5 h-5 text-muted shrink-0 transition-transform duration-200" ::class="open ? 'rotate-45' : ''" />
          </button>
          <div
            id="faq-pricing-{{ $i }}"
            x-show="open"
            x-collapse
            class="overflow-hidden"
          >
            <p class="text-muted leading-relaxed pb-5 pr-10">{{ esc_html($faq['a']) }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>

@endsection
