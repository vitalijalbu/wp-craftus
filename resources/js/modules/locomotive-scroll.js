/**
 * Locomotive Scroll v5 (Lenis) — smooth scrolling module.
 *
 * Usage:
 *   import { initLocomotiveScroll, updateLocomotiveScroll } from './modules/locomotive-scroll.js';
 *   const loco = initLocomotiveScroll();
 */

import LocomotiveScroll from 'locomotive-scroll';

let locoScroll = null;

/**
 * Initialize Locomotive Scroll.
 * Returns null if user prefers reduced motion.
 *
 * @returns {LocomotiveScroll|null}
 */
export function initLocomotiveScroll() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return null;
  if (locoScroll) { locoScroll.destroy(); locoScroll = null; }

  locoScroll = new LocomotiveScroll({
    lenisOptions: {
      lerp:            0.12,
      duration:        1.0,
      smoothWheel:     true,
      smoothTouch:     false,
      wheelMultiplier: 1.1,
      touchMultiplier: 1.4,
      infinite:        false,
    },
    scrollCallback: () => {
      // Keep GSAP ScrollTrigger in sync on every Lenis tick
      if (window.ScrollTrigger) window.ScrollTrigger.update();
    },
  });

  // Smooth anchor scrolling
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
      const href   = anchor.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (!target || !locoScroll) return;
      e.preventDefault();
      locoScroll.scrollTo(target, { duration: 1.2, offset: -80 });
    });
  });

  window.locoScroll = locoScroll;
  return locoScroll;
}

export function destroyLocomotiveScroll() {
  if (locoScroll) { locoScroll.destroy(); locoScroll = null; }
}

export function updateLocomotiveScroll() {
  locoScroll?.resize();
}

export function getLocomotiveScroll() {
  return locoScroll;
}
