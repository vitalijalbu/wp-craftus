/**
 * Swiper carousel instances.
 * Each selector initializes with luxury defaults.
 */

import Swiper from 'swiper';
import {A11y,Autoplay,
  EffectFade, FreeMode, 
  Navigation, Pagination, Scrollbar, Thumbs, 
} from 'swiper/modules';

export function initCarousels() {

  // ── Hero carousel ─────────────────────────────────────────────────────────
  document.querySelectorAll('.js-hero-swiper').forEach(el => {
    new Swiper(el, {
      modules:    [Navigation, Pagination, Autoplay, EffectFade, A11y],
      effect:     'fade',
      fadeEffect: { crossFade: true },
      autoplay:   { delay: 5500, disableOnInteraction: false, pauseOnMouseEnter: true },
      loop:       true,
      speed:      1000,
      pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
      navigation: {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev'),
      },
      a11y: {
        prevSlideMessage: 'Slide precedente',
        nextSlideMessage: 'Slide successiva',
        paginationBulletMessage: 'Vai alla slide {{index}}',
      },
    });
  });

  // ── Products carousel ─────────────────────────────────────────────────────
  document.querySelectorAll('.js-products-swiper').forEach(el => {
    new Swiper(el, {
      modules:      [Navigation, Pagination, FreeMode, Scrollbar, A11y],
      slidesPerView: 1.15,
      spaceBetween:  16,
      freeMode:     { enabled: true, sticky: false },
      scrollbar:    { el: el.closest('[data-products-carousel]')?.querySelector('.swiper-scrollbar'), draggable: true },
      navigation: {
        nextEl: el.closest('[data-products-carousel]')?.querySelector('.swiper-button-next'),
        prevEl: el.closest('[data-products-carousel]')?.querySelector('.swiper-button-prev'),
      },
      breakpoints: {
        640:  { slidesPerView: 2.2,  spaceBetween: 20 },
        1024: { slidesPerView: 3.2,  spaceBetween: 24 },
        1280: { slidesPerView: 4,    spaceBetween: 24 },
      },
      a11y: {
        prevSlideMessage: 'Prodotto precedente',
        nextSlideMessage: 'Prodotto successivo',
      },
    });
  });

  // ── Testimonials carousel ─────────────────────────────────────────────────
  document.querySelectorAll('.js-testimonials-swiper').forEach(el => {
    new Swiper(el, {
      modules:      [Navigation, Pagination, Autoplay, A11y],
      slidesPerView: 1,
      spaceBetween:  32,
      autoplay:     { delay: 6000, disableOnInteraction: false, pauseOnMouseEnter: true },
      loop:         true,
      speed:        800,
      pagination:   { el: el.querySelector('.swiper-pagination'), clickable: true },
      navigation: {
        nextEl: el.closest('[data-testimonials]')?.querySelector('.swiper-button-next'),
        prevEl: el.closest('[data-testimonials]')?.querySelector('.swiper-button-prev'),
      },
      breakpoints: {
        768:  { slidesPerView: 2, spaceBetween: 32 },
        1024: { slidesPerView: 3, spaceBetween: 40 },
      },
      a11y: {
        prevSlideMessage: 'Recensione precedente',
        nextSlideMessage: 'Recensione successiva',
        paginationBulletMessage: 'Vai alla recensione {{index}}',
      },
    });
  });

  // ── Generic carousel (data-swiper-options JSON) ───────────────────────────
  document.querySelectorAll('.js-generic-swiper').forEach(el => {
    const opts = JSON.parse(el.dataset.swiperOptions ?? '{}');
    new Swiper(el, {
      modules:      [Navigation, Pagination, Autoplay, FreeMode, A11y],
      slidesPerView: opts.perView     ?? 1,
      spaceBetween:  opts.gap         ?? 24,
      loop:          opts.loop        ?? false,
      autoplay:      opts.autoplay    ? { delay: opts.autoplayDelay ?? 4000, pauseOnMouseEnter: true } : false,
      pagination:    { el: el.querySelector('.swiper-pagination'), clickable: true },
      navigation: {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev'),
      },
      breakpoints:   opts.breakpoints ?? {},
      a11y: {
        prevSlideMessage: 'Slide precedente',
        nextSlideMessage: 'Slide successiva',
        paginationBulletMessage: 'Vai alla slide {{index}}',
      },
    });
  });
}
