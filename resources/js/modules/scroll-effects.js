/**
 * Declarative scroll animation registry.
 * Add data-scroll="<effect>" to any element.
 *
 * Effects: slide-up | slide-down | slide-left | slide-right |
 *          fade | zoom-in | text-reveal | stagger | line-in |
 *          hero-parallax | parallax | anim-image-parallax
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const EFFECTS = {
	'slide-up': (el) =>
		gsap.fromTo(
			el,
			{ opacity: 0, y: 64 },
			{
				opacity: 1,
				y: 0,
				duration: 0.9,
				ease: 'power3.out',
				scrollTrigger: { trigger: el, start: 'top 88%', once: true },
			},
		),

	'slide-down': (el) =>
		gsap.fromTo(
			el,
			{ opacity: 0, y: -64 },
			{
				opacity: 1,
				y: 0,
				duration: 0.9,
				ease: 'power3.out',
				scrollTrigger: { trigger: el, start: 'top 88%', once: true },
			},
		),

	'slide-left': (el) =>
		gsap.fromTo(
			el,
			{ opacity: 0, x: 80 },
			{
				opacity: 1,
				x: 0,
				duration: 0.9,
				ease: 'power3.out',
				scrollTrigger: { trigger: el, start: 'top 88%', once: true },
			},
		),

	'slide-right': (el) =>
		gsap.fromTo(
			el,
			{ opacity: 0, x: -80 },
			{
				opacity: 1,
				x: 0,
				duration: 0.9,
				ease: 'power3.out',
				scrollTrigger: { trigger: el, start: 'top 88%', once: true },
			},
		),

	fade: (el) =>
		gsap.fromTo(
			el,
			{ opacity: 0 },
			{
				opacity: 1,
				duration: 1.2,
				ease: 'power2.out',
				scrollTrigger: { trigger: el, start: 'top 85%', once: true },
			},
		),

	'zoom-in': (el) =>
		gsap.fromTo(
			el,
			{ opacity: 0, scale: 0.88 },
			{
				opacity: 1,
				scale: 1,
				duration: 0.9,
				ease: 'power3.out',
				scrollTrigger: { trigger: el, start: 'top 88%', once: true },
			},
		),

	'text-reveal': (el) =>
		gsap.fromTo(
			el,
			{ opacity: 0, y: 80, skewY: 2 },
			{
				opacity: 1,
				y: 0,
				skewY: 0,
				duration: 1.1,
				ease: 'power4.out',
				scrollTrigger: { trigger: el, start: 'top 90%', once: true },
			},
		),

	'line-in': (el) =>
		gsap.fromTo(
			el,
			{ scaleX: 0, transformOrigin: 'left center' },
			{
				scaleX: 1,
				duration: 1.3,
				ease: 'expo.inOut',
				scrollTrigger: { trigger: el, start: 'top 90%', once: true },
			},
		),

	stagger: (container) => {
		const items = container.querySelectorAll('[data-scroll-item]');
		if (!items.length) {
			return;
		}
		gsap.fromTo(
			items,
			{ opacity: 0, y: 50 },
			{
				opacity: 1,
				y: 0,
				duration: 0.8,
				stagger: 0.1,
				ease: 'power3.out',
				scrollTrigger: { trigger: container, start: 'top 82%', once: true },
			},
		);
	},

	'hero-parallax': (el) =>
		gsap.to(el, {
			yPercent: 18,
			ease: 'none',
			scrollTrigger: {
				trigger: el.closest('section') ?? el,
				start: 'top top',
				end: 'bottom top',
				scrub: true,
			},
		}),

	'anim-image-parallax': (el) =>
		gsap.fromTo(
			el,
			{ opacity: 0, scale: 1.06 },
			{
				opacity: 1,
				scale: 1,
				duration: 1.0,
				ease: 'power2.out',
				scrollTrigger: { trigger: el, start: 'top 88%', once: true },
			},
		),
};

export function initScrollEffects() {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	document.querySelectorAll('[data-scroll]').forEach((el) => {
		const effect = el.dataset.scroll;
		const fn = EFFECTS[effect];
		if (fn) {
			fn(el);
		}
	});
}
