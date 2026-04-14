import Swiper from 'swiper'
import { A11y, Autoplay, EffectFade, Navigation, Pagination } from 'swiper/modules'

/**
 * Initialize hero swipers in a given root context.
 * datasetKey avoids duplicate initialization in dynamic UIs (e.g. Gutenberg editor).
 */
export function initHeroSwipers(
  root = document,
  { datasetKey = 'swiperInit', pauseOnMouseEnter = true } = {},
) {
  root.querySelectorAll('.js-hero-swiper').forEach((el) => {
    if (el.dataset[datasetKey] === '1') {
      return
    }

    new Swiper(el, {
      modules: [Navigation, Pagination, Autoplay, EffectFade, A11y],
      effect: 'fade',
      fadeEffect: { crossFade: true },
      autoplay: {
        delay: 5500,
        disableOnInteraction: false,
        pauseOnMouseEnter,
      },
      loop: true,
      speed: 1000,
      pagination: {
        el: el.querySelector('.swiper-pagination'),
        clickable: true,
      },
      navigation: {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev'),
      },
      a11y: {
        prevSlideMessage: 'Slide precedente',
        nextSlideMessage: 'Slide successiva',
        paginationBulletMessage: 'Vai alla slide {{index}}',
      },
    })

    el.dataset[datasetKey] = '1'
  })
}
