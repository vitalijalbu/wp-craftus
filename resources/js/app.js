import.meta.glob(['../images/**', '../fonts/**']);

// ── Alpine.js ────────────────────────────────────────────────────────────────
import Alpine   from 'alpinejs';
import Collapse  from '@alpinejs/collapse';
import Focus     from '@alpinejs/focus';

// ── GSAP ─────────────────────────────────────────────────────────────────────
import { gsap }        from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// ── Modules ───────────────────────────────────────────────────────────────────
import { initLocomotiveScroll, updateLocomotiveScroll } from './modules/locomotive-scroll.js';
import { initLuxuryAnimations }  from './modules/luxury-animations.js';
import { initScrollEffects }     from './modules/scroll-effects.js';
import { initCarousels }         from './modules/carousel.js';
import { initMagneticHover }     from './modules/magnetic-hover.js';

// ── Accessibility: reduced motion ─────────────────────────────────────────────
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!prefersReducedMotion) {
  gsap.registerPlugin(ScrollTrigger);
  window.ScrollTrigger = ScrollTrigger;
} else {
  window.ScrollTrigger = { refresh: () => {}, update: () => {} };
}

window.gsap = gsap;

// ── Alpine plugins ────────────────────────────────────────────────────────────
Alpine.plugin(Collapse);
Alpine.plugin(Focus);

// ── Alpine store: shared layout state ─────────────────────────────────────────
Alpine.store('layout', {
  hasHero:   false,
  cartCount: 0,
  init() {
    this.hasHero   = document.body.classList.contains('has-hero');
    this.cartCount = parseInt(
      document.querySelector('[data-cart-count]')?.dataset.cartCount ?? '0', 10
    );
    // Sync cart count when WooCommerce refreshes fragments via AJAX
    document.body.addEventListener('wc_fragments_refreshed', () => {
      const el = document.querySelector('[data-cart-count]');
      if (el) this.cartCount = parseInt(el.dataset.cartCount ?? '0', 10);
    });
  },
});

// ── Alpine component: site header ─────────────────────────────────────────────
// Dual-state (expanded ↔ scrolled) with GSAP timeline — same pattern as Madison.
Alpine.data('siteHeader', () => ({
  mobileOpen:  false,
  activeMenu:  null,
  scrolled:    false,
  prevScrolled: false,

  get hasHero()   { return this.$store.layout.hasHero; },
  get cartCount() { return this.$store.layout.cartCount; },

  // ── Mega-menu ──────────────────────────────────────────────────────────────
  openMenu(id) {
    if (this.activeMenu === id) { this.closeMenu(); return; }
    this.activeMenu = id;
    document.getElementById('site-header')?.classList.add('header-mega-open');
    this.$nextTick(() => {
      const panel = document.getElementById('mega-' + id);
      if (!panel) return;
      gsap.fromTo(panel,
        { clipPath: 'inset(0% 0% 100% 0%)', opacity: 0 },
        { clipPath: 'inset(0% 0% 0% 0%)',   opacity: 1, duration: 0.42, ease: 'expo.out' }
      );
      gsap.fromTo(panel.querySelectorAll('.mega-item'),
        { opacity: 0, y: 14 },
        { opacity: 1, y: 0,  duration: 0.38, ease: 'expo.out', stagger: 0.04, delay: 0.08 }
      );
    });
  },

  closeMenu() {
    if (!this.activeMenu) return;
    const id    = this.activeMenu;
    const panel = document.getElementById('mega-' + id);
    document.getElementById('site-header')?.classList.remove('header-mega-open');
    if (!panel) { this.activeMenu = null; return; }
    gsap.to(panel, {
      clipPath: 'inset(0% 0% 100% 0%)',
      opacity:  0,
      duration: 0.28,
      ease:     'expo.in',
      onComplete: () => { this.activeMenu = null; },
    });
  },

  // ── Mobile ─────────────────────────────────────────────────────────────────
  toggleMobile() {
    this.mobileOpen = !this.mobileOpen;
    document.body.classList.toggle('overflow-hidden', this.mobileOpen);
  },
  closeMobile() {
    this.mobileOpen = false;
    document.body.classList.remove('overflow-hidden');
  },

  // ── Init: scroll + keyboard + GSAP dual-state watcher ──────────────────────
  init() {
    // Scroll detection
    window.addEventListener('scroll', () => {
      const sy = window.scrollY;
      if (!this.scrolled && sy > 80)  this.scrolled = true;
      if (this.scrolled  && sy < 35)  this.scrolled = false;
    }, { passive: true });

    // Escape key
    document.addEventListener('keydown', e => {
      if (e.key !== 'Escape') return;
      this.closeMenu();
      this.closeMobile();
    });

    // ── GSAP dual-state (expanded ↔ scrolled compact bar) ─────────────────
    let _scrollTl  = null;
    let _expandedH = null;

    this.$watch('scrolled', (val) => {
      if (val === this.prevScrolled) return;
      this.prevScrolled = val;

      const expandedW   = this.$refs.expandedWrapper;
      const scrolledBar = this.$refs.scrolledBar;

      if (_scrollTl) { _scrollTl.kill(); _scrollTl = null; }

      if (val) {
        // → scrolled: collapse expanded, reveal compact bar
        if (expandedW) {
          _expandedH = expandedW.offsetHeight;
          gsap.set(expandedW, { height: _expandedH, overflow: 'hidden' });
        }
        _scrollTl = gsap.timeline();
        if (expandedW) {
          _scrollTl.to(expandedW, {
            height: 0, opacity: 0,
            duration: 0.30, ease: 'expo.inOut', overwrite: true,
          }, 0);
        }
        if (scrolledBar) {
          scrolledBar.style.display = 'flex';
          gsap.set(scrolledBar, { opacity: 0, y: -10 });
          _scrollTl.to(scrolledBar, {
            opacity: 1, y: 0,
            duration: 0.36, ease: 'expo.out', overwrite: true,
          }, 0.06);
        }
        _scrollTl.add(() => {
          if (expandedW) {
            expandedW.style.display = 'none';
            gsap.set(expandedW, { clearProps: 'height,opacity,overflow' });
          }
        });

      } else {
        // → not scrolled: reveal expanded, collapse compact bar
        _scrollTl = gsap.timeline();
        if (scrolledBar) {
          _scrollTl.to(scrolledBar, {
            opacity: 0, y: -8,
            duration: 0.2, ease: 'expo.in', overwrite: true,
          }, 0);
        }
        if (expandedW) {
          const targetH = _expandedH ?? expandedW.scrollHeight;
          expandedW.style.display = 'block';
          gsap.set(expandedW, { height: 0, opacity: 0, overflow: 'hidden' });
          _scrollTl.to(expandedW, {
            height: targetH, opacity: 1,
            duration: 0.4, ease: 'expo.out', overwrite: true,
          }, 0);
          _scrollTl.add(() => {
            gsap.set(expandedW, { clearProps: 'height,opacity,overflow' });
          });
        }
        if (scrolledBar) scrolledBar.style.display = 'none';
      }
    });
  },
}));

// ── Alpine component: search overlay ─────────────────────────────────────────
Alpine.data('searchOverlay', () => ({
  open:  false,
  query: '',
  show() { this.open = true; this.$nextTick(() => this.$refs.input?.focus()); },
  hide() { this.open = false; this.query = ''; },
  submit() {
    if (!this.query.trim()) return;
    window.location.href = `/?s=${encodeURIComponent(this.query.trim())}`;
  },
  init() { window.addEventListener('open-search', () => this.show()); },
}));

window.Alpine = Alpine;

// ── Bootstrap ─────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  Alpine.start();
  initCarousels();

  if (!prefersReducedMotion) {
    const loco = initLocomotiveScroll();
    if (loco) {
      initLuxuryAnimations();
      initScrollEffects();
      initMagneticHover();
    }
    window.updateLocomotiveScroll = updateLocomotiveScroll;
  } else {
    // Run non-animated fallbacks (still register scroll effects as visible)
    document.querySelectorAll('[data-scroll], [data-parallax], [data-clip-reveal]').forEach(el => {
      el.style.opacity  = '1';
      el.style.transform = 'none';
    });
  }
});
