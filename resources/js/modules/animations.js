/**
 * Aanimation library — GSAP + ScrollTrigger.
 */

import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)
window.ScrollTrigger = ScrollTrigger

export function initLuxuryAnimations() {
  // ── Parallax images ──────────────────────────────────────────────────────
  gsap.utils.toArray('[data-parallax]').forEach((img) => {
    const speed = parseFloat(img.dataset.parallaxSpeed ?? '0.3')
    const parent = img.closest('section, .parallax-wrap') ?? img.parentElement
    gsap.to(img, {
      yPercent: -30 * speed,
      ease: 'none',
      scrollTrigger: {
        trigger: parent,
        start: 'top bottom',
        end: 'bottom top',
        scrub: true,
        invalidateOnRefresh: true,
      },
    })
  })

  // ── Clip reveal (images + sections) ─────────────────────────────────────
  gsap.utils.toArray('[data-clip-reveal]').forEach((el) => {
    gsap.fromTo(
      el,
      { clipPath: 'inset(100% 0% 0% 0%)' },
      {
        clipPath: 'inset(0% 0% 0% 0%)',
        duration: 1.1,
        ease: 'expo.out',
        scrollTrigger: { trigger: el, start: 'top 88%', once: true },
      },
    )
  })

  // ── Line reveal (decorative dividers) ────────────────────────────────────
  gsap.utils.toArray('[data-line-reveal]').forEach((el) => {
    gsap.fromTo(
      el,
      { scaleX: 0, transformOrigin: 'left center' },
      {
        scaleX: 1,
        duration: 1.4,
        ease: 'expo.inOut',
        scrollTrigger: { trigger: el, start: 'top 90%', once: true },
      },
    )
  })

  // ── Stagger grid cards ────────────────────────────────────────────────────
  gsap.utils.toArray('[data-stagger-grid]').forEach((container) => {
    const items = container.querySelectorAll('[data-stagger-item]')
    if (!items.length) {
      return
    }
    gsap.fromTo(
      items,
      { opacity: 0, y: 48 },
      {
        opacity: 1,
        y: 0,
        duration: 0.85,
        stagger: 0.09,
        ease: 'power3.out',
        scrollTrigger: { trigger: container, start: 'top 82%', once: true },
      },
    )
  })

  // ── Counter animations ────────────────────────────────────────────────────
  gsap.utils.toArray('[data-counter]').forEach((el) => {
    const target = parseFloat(el.dataset.counter) || 0
    const suffix = el.dataset.counterSuffix || ''
    const prefix = el.dataset.counterPrefix || ''
    const decimals = parseInt(el.dataset.counterDecimals ?? '0', 10)
    ScrollTrigger.create({
      trigger: el,
      start: 'top 85%',
      once: true,
      onEnter: () => {
        gsap.fromTo(
          { val: 0 },
          {
            val: target,
            duration: 2.2,
            ease: 'power2.out',
            onUpdate: function () {
              el.textContent = prefix + this.targets()[0].val.toFixed(decimals) + suffix
            },
          },
        )
      },
    })
  })

  // ── Infinite marquee ──────────────────────────────────────────────────────
  // Clone items inside the track (so track has 2× content), then animate
  // the track by -50% — the loop resets exactly where the next cycle begins.
  document.querySelectorAll('.js-marquee-track').forEach((track) => {
    const items = Array.from(track.querySelectorAll('.js-marquee-item'))
    if (!items.length) {
      return
    }
    items.forEach((item) => {
      track.appendChild(item.cloneNode(true))
    })
    const speed = parseFloat(track.dataset.marqueeSpeed ?? '20')
    gsap.to(track, { xPercent: -50, ease: 'none', duration: speed, repeat: -1 })
  })

  // ── Horizontal scroll section ─────────────────────────────────────────────
  gsap.utils.toArray('[data-h-scroll]').forEach((container) => {
    const track = container.querySelector('[data-h-scroll-track]')
    if (!track) {
      return
    }
    const totalWidth = track.scrollWidth - container.offsetWidth
    gsap.to(track, {
      x: -totalWidth,
      ease: 'none',
      scrollTrigger: {
        trigger: container,
        start: 'top top',
        end: () => `+=${totalWidth}`,
        pin: true,
        scrub: 1,
        anticipatePin: 1,
        invalidateOnRefresh: true,
      },
    })
  })
}
