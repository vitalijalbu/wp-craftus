/**
 * Magnetic hover effect — buttons/icons attract toward cursor.
 * Apply [data-magnetic] attribute to any element.
 */

import { gsap } from 'gsap';

export function initMagneticHover() {
  document.querySelectorAll('[data-magnetic]').forEach(el => {
    attachMagnetic(el);
  });
}

function attachMagnetic(el) {
  const strength = parseFloat(el.dataset.magneticStrength ?? '0.25');

  const onMove = (e) => {
    const { left, top, width, height } = el.getBoundingClientRect();
    const cx = left + width  / 2;
    const cy = top  + height / 2;
    const dx = e.clientX - cx;
    const dy = e.clientY - cy;

    gsap.to(el, {
      x:        dx * strength,
      y:        dy * strength,
      duration: 0.6,
      ease:     'power2.out',
    });
  };

  const onLeave = () => {
    gsap.to(el, { x: 0, y: 0, duration: 0.8, ease: 'elastic.out(1, 0.4)' });
  };

  el.addEventListener('mousemove',  onMove);
  el.addEventListener('mouseleave', onLeave);
}
