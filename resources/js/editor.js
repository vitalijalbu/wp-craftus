/**
 * Block editor script — InspectorControls for all custom blocks.
 *
 * @wordpress/blocks, @wordpress/block-editor, @wordpress/components,
 * @wordpress/server-side-render and @wordpress/element are all mapped
 * to window.wp.* externals by @roots/vite-plugin's wordpressPlugin().
 */

import {
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  useBlockProps,
} from '@wordpress/block-editor'
import {
  registerBlockStyle,
  registerBlockType,
  registerBlockVariation,
  unregisterBlockStyle,
} from '@wordpress/blocks'
import {
  Button,
  CheckboxControl,
  PanelBody,
  RangeControl,
  SelectControl,
  TextareaControl,
  TextControl,
  ToggleControl,
} from '@wordpress/components'
import { useSelect } from '@wordpress/data'
import { createElement as el, Fragment } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import ServerSideRender from '@wordpress/server-side-render'

// ── Block Style Variations ─────────────────────────────────────────────────────
// Registrate dopo il caricamento dei blocchi per evitare race conditions.

window.addEventListener('DOMContentLoaded', () => {
  // ── core/button ─────────────────────────────────────────────────────────────
  registerBlockStyle('core/button', {
    name: 'outline',
    label: __('Outline', 'sage'),
  })
  registerBlockStyle('core/button', {
    name: 'accent',
    label: __('Primary (Blue)', 'sage'),
  })
  registerBlockStyle('core/button', {
    name: 'ghost',
    label: __('Ghost', 'sage'),
  })

  // ── core/heading ────────────────────────────────────────────────────────────
  registerBlockStyle('core/heading', {
    name: 'display',
    label: __('Display', 'sage'),
  })
  registerBlockStyle('core/heading', {
    name: 'overline',
    label: __('Overline', 'sage'),
  })

  // ── core/separator ──────────────────────────────────────────────────────────
  registerBlockStyle('core/separator', {
    name: 'thick',
    label: __('Spesso', 'sage'),
  })
  registerBlockStyle('core/separator', {
    name: 'accent',
    label: __('Primary', 'sage'),
  })

  // ── core/quote ──────────────────────────────────────────────────────────────
  registerBlockStyle('core/quote', {
    name: 'minimal',
    label: __('Minimal', 'sage'),
  })
  registerBlockStyle('core/quote', {
    name: 'large',
    label: __('Grande', 'sage'),
  })

  // ── core/image ──────────────────────────────────────────────────────────────
  registerBlockStyle('core/image', {
    name: 'rounded',
    label: __('Arrotondato', 'sage'),
  })
  registerBlockStyle('core/image', {
    name: 'framed',
    label: __('Con cornice', 'sage'),
  })

  // ── core/group ──────────────────────────────────────────────────────────────
  registerBlockStyle('core/group', {
    name: 'card',
    label: __('Card', 'sage'),
  })
  registerBlockStyle('core/group', {
    name: 'bordered',
    label: __('Bordo', 'sage'),
  })

  // ── Block Variations ──────────────────────────────────────────────────────────
  // Preset di blocchi core preconfigurati con il design system del tema.

  // Variation: Cover → "Hero Section"
  registerBlockVariation('core/cover', {
    name: 'theme-hero',
    title: __('Hero Section', 'sage'),
    description: __('Cover preconfigurata come sezione hero con overlay scuro.', 'sage'),
    category: 'theme',
    icon: 'cover-image',
    attributes: {
      minHeight: 80,
      minHeightUnit: 'vh',
      dimRatio: 50,
      overlayColor: 'ink',
      align: 'full',
      contentPosition: 'center center',
    },
    scope: ['inserter'],
  })

  // Variation: Group → "Content Card"
  registerBlockVariation('core/group', {
    name: 'theme-card',
    title: __('Content Card', 'sage'),
    description: __('Gruppo con sfondo, padding e bordo — ideale per card di contenuto.', 'sage'),
    category: 'theme',
    icon: 'id-alt',
    attributes: {
      backgroundColor: 'surface-alt',
      style: {
        border: { width: '1px', style: 'solid', color: '#e0e0e0' },
        spacing: {
          padding: { top: '2rem', bottom: '2rem', left: '2rem', right: '2rem' },
        },
      },
    },
    scope: ['inserter'],
  })

  // Variation: Group → "Dark Section"
  registerBlockVariation('core/group', {
    name: 'theme-dark-section',
    title: __('Sezione Scura', 'sage'),
    description: __('Gruppo a larghezza piena con sfondo ink e testo chiaro.', 'sage'),
    category: 'theme',
    icon: 'shield',
    attributes: {
      backgroundColor: 'ink',
      textColor: 'white',
      align: 'full',
      style: {
        spacing: {
          padding: { top: '6rem', bottom: '6rem', left: '2rem', right: '2rem' },
        },
      },
    },
    scope: ['inserter'],
  })

  // Variation: Columns → "2 Colonne 60/40"
  registerBlockVariation('core/columns', {
    name: 'theme-cols-60-40',
    title: __('Colonne 60/40', 'sage'),
    description: __('Layout a due colonne asimmetriche (60% + 40%).', 'sage'),
    category: 'theme',
    icon: 'columns',
    attributes: {
      align: 'wide',
    },
    innerBlocks: [
      ['core/column', { width: '60%' }],
      ['core/column', { width: '40%' }],
    ],
    scope: ['inserter'],
  })

  // Variation: Columns → "3 Colonne uguali"
  registerBlockVariation('core/columns', {
    name: 'theme-cols-thirds',
    title: __('3 Colonne uguali', 'sage'),
    description: __('Tre colonne di larghezza identica.', 'sage'),
    category: 'theme',
    icon: 'columns',
    attributes: {
      align: 'wide',
    },
    innerBlocks: [
      ['core/column', { width: '33.33%' }],
      ['core/column', { width: '33.33%' }],
      ['core/column', { width: '33.33%' }],
    ],
    scope: ['inserter'],
  })
})

// ── Shared helpers ────────────────────────────────────────────────────────────

const bgOptions = [
  { label: __('Chiaro (surface)', 'sage'), value: 'surface' },
  { label: __('Crema', 'sage'), value: 'cream' },
  { label: __('Scuro (ink)', 'sage'), value: 'ink' },
]

// Static list mirrors the EFFECTS registry in scroll-effects.js.
// Update both files if you add/remove an effect.
const scrollEffectOptions = [
  { label: __('Nessuno', 'sage'), value: '' },
  { label: __('Slide Up', 'sage'), value: 'slide-up' },
  { label: __('Slide Down', 'sage'), value: 'slide-down' },
  { label: __('Slide Left', 'sage'), value: 'slide-left' },
  { label: __('Slide Right', 'sage'), value: 'slide-right' },
  { label: __('Fade', 'sage'), value: 'fade' },
  { label: __('Zoom In', 'sage'), value: 'zoom-in' },
  { label: __('Text Reveal', 'sage'), value: 'text-reveal' },
  { label: __('Line In', 'sage'), value: 'line-in' },
  { label: __('Parallax Immagine', 'sage'), value: 'anim-image-parallax' },
]

function MediaPanel({ imageId, imageUrl, onSelect, onRemove }) {
  return el(
    MediaUploadCheck,
    null,
    el(MediaUpload, {
      onSelect,
      allowedTypes: ['image'],
      value: imageId || 0,
      render: ({ open }) =>
        el(
          Fragment,
          null,
          el(
            Button,
            {
              onClick: open,
              variant: imageId ? 'secondary' : 'primary',
              style: { marginBottom: '8px', display: 'block', width: '100%' },
            },
            imageId ? __('Cambia immagine', 'sage') : __('Scegli immagine', 'sage'),
          ),
          imageUrl &&
            el('img', {
              src: imageUrl,
              alt: '',
              style: { maxWidth: '100%', height: 'auto', marginBottom: '8px', borderRadius: '4px' },
            }),
          imageId &&
            el(
              Button,
              {
                isDestructive: true,
                variant: 'link',
                onClick: onRemove,
                style: { display: 'block' },
              },
              __('Rimuovi immagine', 'sage'),
            ),
        ),
    }),
  )
}

// ── theme/hero ────────────────────────────────────────────────────────────────

registerBlockType('theme/hero', {
  edit({ attributes, setAttributes }) {
    const {
      imageId,
      imageUrl,
      label,
      heading,
      subtext,
      ctaLabel,
      ctaUrl,
      cta2Label,
      cta2Url,
      overlayOpacity,
      minHeight,
      contentAlign,
      scrollEffect,
    } = attributes

    return el(
      Fragment,
      null,

      el(
        InspectorControls,
        null,

        el(
          PanelBody,
          { title: __('Immagine di sfondo', 'sage'), initialOpen: true },
          el(MediaPanel, {
            imageId,
            imageUrl,
            onSelect: (media) =>
              setAttributes({
                imageId: media.id,
                imageUrl: media.url,
                imageAlt: media.alt ?? '',
              }),
            onRemove: () => setAttributes({ imageId: 0, imageUrl: '', imageAlt: '' }),
          }),
          el(RangeControl, {
            label: __('Opacità overlay', 'sage'),
            value: overlayOpacity ?? 40,
            onChange: (val) => setAttributes({ overlayOpacity: val }),
            min: 0,
            max: 100,
          }),
        ),

        el(
          PanelBody,
          { title: __('Contenuto', 'sage'), initialOpen: true },
          el(TextControl, {
            label: __('Label piccola', 'sage'),
            value: label ?? '',
            onChange: (val) => setAttributes({ label: val }),
          }),
          el(TextControl, {
            label: __('Titolo principale', 'sage'),
            value: heading ?? '',
            onChange: (val) => setAttributes({ heading: val }),
          }),
          el(TextareaControl, {
            label: __('Sottotitolo', 'sage'),
            value: subtext ?? '',
            onChange: (val) => setAttributes({ subtext: val }),
          }),
        ),

        el(
          PanelBody,
          { title: __('Pulsanti CTA', 'sage'), initialOpen: false },
          el(TextControl, {
            label: __('Testo CTA principale', 'sage'),
            value: ctaLabel ?? '',
            onChange: (val) => setAttributes({ ctaLabel: val }),
          }),
          el(TextControl, {
            label: __('URL CTA principale', 'sage'),
            value: ctaUrl ?? '',
            type: 'url',
            onChange: (val) => setAttributes({ ctaUrl: val }),
          }),
          el(TextControl, {
            label: __('Testo CTA secondario', 'sage'),
            value: cta2Label ?? '',
            onChange: (val) => setAttributes({ cta2Label: val }),
          }),
          el(TextControl, {
            label: __('URL CTA secondario', 'sage'),
            value: cta2Url ?? '',
            type: 'url',
            onChange: (val) => setAttributes({ cta2Url: val }),
          }),
        ),

        el(
          PanelBody,
          { title: __('Layout', 'sage'), initialOpen: false },
          el(SelectControl, {
            label: __('Allineamento contenuto', 'sage'),
            value: contentAlign ?? 'center',
            options: [
              { label: __('Sinistra', 'sage'), value: 'left' },
              { label: __('Centro', 'sage'), value: 'center' },
              { label: __('Destra', 'sage'), value: 'right' },
            ],
            onChange: (val) => setAttributes({ contentAlign: val }),
          }),
          el(TextControl, {
            label: __('Altezza minima', 'sage'),
            value: minHeight ?? '100svh',
            help: __('es. 100svh, 600px, 80vh', 'sage'),
            onChange: (val) => setAttributes({ minHeight: val }),
          }),
          el(SelectControl, {
            label: __('Effetto scroll', 'sage'),
            value: scrollEffect ?? '',
            options: scrollEffectOptions,
            onChange: (val) => setAttributes({ scrollEffect: val }),
          }),
        ),
      ),

      el('div', useBlockProps(), el(ServerSideRender, { block: 'theme/hero', attributes })),
    )
  },
  save: () => null,
})

// ── theme/testimonial ───────────────────────────────────────────────────────

registerBlockType('theme/testimonial', {
  edit({ attributes, setAttributes }) {
    const {
      quote,
      authorName,
      authorRole,
      authorImageId,
      authorImageUrl,
      rating,
      bg,
      style: cardStyle,
      scrollEffect,
    } = attributes

    return el(
      Fragment,
      null,

      el(
        InspectorControls,
        null,

        el(
          PanelBody,
          { title: __('Citazione', 'sage'), initialOpen: true },
          el(TextareaControl, {
            label: __('Citazione', 'sage'),
            value: quote ?? '',
            onChange: (val) => setAttributes({ quote: val }),
          }),
          el(RangeControl, {
            label: __('Valutazione (stelle)', 'sage'),
            value: rating ?? 5,
            onChange: (val) => setAttributes({ rating: val }),
            min: 0,
            max: 5,
          }),
        ),

        el(
          PanelBody,
          { title: __('Autore', 'sage'), initialOpen: true },
          el(TextControl, {
            label: __('Nome autore', 'sage'),
            value: authorName ?? '',
            onChange: (val) => setAttributes({ authorName: val }),
          }),
          el(TextControl, {
            label: __('Ruolo / azienda', 'sage'),
            value: authorRole ?? '',
            onChange: (val) => setAttributes({ authorRole: val }),
          }),
          el(MediaPanel, {
            imageId: authorImageId,
            imageUrl: authorImageUrl,
            onSelect: (media) =>
              setAttributes({ authorImageId: media.id, authorImageUrl: media.url }),
            onRemove: () => setAttributes({ authorImageId: 0, authorImageUrl: '' }),
          }),
        ),

        el(
          PanelBody,
          { title: __('Stile', 'sage'), initialOpen: false },
          el(SelectControl, {
            label: __('Stile card', 'sage'),
            value: cardStyle ?? 'card',
            options: [
              { label: __('Card', 'sage'), value: 'card' },
              { label: __('Minimal', 'sage'), value: 'minimal' },
              { label: __('Grande', 'sage'), value: 'large' },
            ],
            onChange: (val) => setAttributes({ style: val }),
          }),
          el(SelectControl, {
            label: __('Sfondo', 'sage'),
            value: bg ?? 'surface',
            options: bgOptions,
            onChange: (val) => setAttributes({ bg: val }),
          }),
          el(SelectControl, {
            label: __('Effetto scroll', 'sage'),
            value: scrollEffect ?? '',
            options: scrollEffectOptions,
            onChange: (val) => setAttributes({ scrollEffect: val }),
          }),
        ),
      ),

      el('div', useBlockProps(), el(ServerSideRender, { block: 'theme/testimonial', attributes })),
    )
  },
  save: () => null,
})

// ── theme/stat ──────────────────────────────────────────────────────────────

registerBlockType('theme/stat', {
  edit({ attributes, setAttributes }) {
    const { value, label, description, prefix, suffix, align, bg, scrollEffect } = attributes

    return el(
      Fragment,
      null,

      el(
        InspectorControls,
        null,

        el(
          PanelBody,
          { title: __('Dati', 'sage'), initialOpen: true },
          el(TextControl, {
            label: __('Prefisso (es. +, €)', 'sage'),
            value: prefix ?? '',
            onChange: (val) => setAttributes({ prefix: val }),
          }),
          el(TextControl, {
            label: __('Valore (es. 100+)', 'sage'),
            value: value ?? '',
            onChange: (val) => setAttributes({ value: val }),
          }),
          el(TextControl, {
            label: __('Suffisso (es. %, k)', 'sage'),
            value: suffix ?? '',
            onChange: (val) => setAttributes({ suffix: val }),
          }),
          el(TextControl, {
            label: __('Etichetta', 'sage'),
            value: label ?? '',
            onChange: (val) => setAttributes({ label: val }),
          }),
          el(TextControl, {
            label: __('Descrizione opzionale', 'sage'),
            value: description ?? '',
            onChange: (val) => setAttributes({ description: val }),
          }),
        ),

        el(
          PanelBody,
          { title: __('Stile', 'sage'), initialOpen: false },
          el(SelectControl, {
            label: __('Allineamento', 'sage'),
            value: align ?? 'left',
            options: [
              { label: __('Sinistra (con linea)', 'sage'), value: 'left' },
              { label: __('Centro', 'sage'), value: 'center' },
            ],
            onChange: (val) => setAttributes({ align: val }),
          }),
          el(SelectControl, {
            label: __('Sfondo sezione', 'sage'),
            value: bg ?? 'surface',
            options: bgOptions,
            onChange: (val) => setAttributes({ bg: val }),
          }),
          el(SelectControl, {
            label: __('Effetto scroll', 'sage'),
            value: scrollEffect ?? '',
            options: scrollEffectOptions,
            onChange: (val) => setAttributes({ scrollEffect: val }),
          }),
        ),
      ),

      el('div', useBlockProps(), el(ServerSideRender, { block: 'theme/stat', attributes })),
    )
  },
  save: () => null,
})

// ── theme/icon-box ──────────────────────────────────────────────────────────

registerBlockType('theme/icon-box', {
  edit({ attributes, setAttributes }) {
    const {
      iconImageId,
      iconImageUrl,
      iconSize,
      title,
      text,
      linkLabel,
      linkUrl,
      bg,
      layout,
      bordered,
      scrollEffect,
    } = attributes

    return el(
      Fragment,
      null,

      el(
        InspectorControls,
        null,

        el(
          PanelBody,
          { title: __('Icona', 'sage'), initialOpen: true },
          el(MediaPanel, {
            imageId: iconImageId,
            imageUrl: iconImageUrl,
            onSelect: (media) => setAttributes({ iconImageId: media.id, iconImageUrl: media.url }),
            onRemove: () => setAttributes({ iconImageId: 0, iconImageUrl: '' }),
          }),
          el(RangeControl, {
            label: __('Dimensione icona (px)', 'sage'),
            value: iconSize ?? 48,
            onChange: (val) => setAttributes({ iconSize: val }),
            min: 16,
            max: 120,
          }),
        ),

        el(
          PanelBody,
          { title: __('Contenuto', 'sage'), initialOpen: true },
          el(TextControl, {
            label: __('Titolo', 'sage'),
            value: title ?? '',
            onChange: (val) => setAttributes({ title: val }),
          }),
          el(TextareaControl, {
            label: __('Testo', 'sage'),
            value: text ?? '',
            onChange: (val) => setAttributes({ text: val }),
          }),
          el(TextControl, {
            label: __('Testo link', 'sage'),
            value: linkLabel ?? '',
            onChange: (val) => setAttributes({ linkLabel: val }),
          }),
          el(TextControl, {
            label: __('URL link', 'sage'),
            value: linkUrl ?? '',
            type: 'url',
            onChange: (val) => setAttributes({ linkUrl: val }),
          }),
        ),

        el(
          PanelBody,
          { title: __('Stile', 'sage'), initialOpen: false },
          el(SelectControl, {
            label: __('Layout', 'sage'),
            value: layout ?? 'vertical',
            options: [
              { label: __('Verticale', 'sage'), value: 'vertical' },
              { label: __('Orizzontale', 'sage'), value: 'horizontal' },
            ],
            onChange: (val) => setAttributes({ layout: val }),
          }),
          el(SelectControl, {
            label: __('Sfondo', 'sage'),
            value: bg ?? 'surface',
            options: bgOptions,
            onChange: (val) => setAttributes({ bg: val }),
          }),
          el(ToggleControl, {
            label: __('Bordo card', 'sage'),
            checked: bordered ?? false,
            onChange: (val) => setAttributes({ bordered: val }),
          }),
          el(SelectControl, {
            label: __('Effetto scroll', 'sage'),
            value: scrollEffect ?? '',
            options: scrollEffectOptions,
            onChange: (val) => setAttributes({ scrollEffect: val }),
          }),
        ),
      ),

      el('div', useBlockProps(), el(ServerSideRender, { block: 'theme/icon-box', attributes })),
    )
  },
  save: () => null,
})

// ── theme/accordion ────────────────────────────────────────────────────────────
registerBlockType('theme/accordion', {
  edit({ attributes, setAttributes }) {
    const { items, style, openFirst } = attributes

    const styleOptions = [
      { value: 'lines', label: __('Linee (default)', 'sage') },
      { value: 'cards', label: __('Card con sfondo', 'sage') },
      { value: 'filled', label: __('Filled (titolo scuro)', 'sage') },
    ]

    const updateItem = (index, field, value) => {
      const next = items.map((item, i) => (i === index ? { ...item, [field]: value } : item))
      setAttributes({ items: next })
    }

    const addItem = () => {
      setAttributes({
        items: [
          ...items,
          { question: __('Nuova domanda?', 'sage'), answer: __('Risposta...', 'sage') },
        ],
      })
    }

    const removeItem = (index) => {
      setAttributes({ items: items.filter((_, i) => i !== index) })
    }

    return el(
      Fragment,
      null,
      el(
        InspectorControls,
        null,
        el(
          PanelBody,
          { title: __('Stile', 'sage'), initialOpen: true },
          el(SelectControl, {
            label: __('Stile accordion', 'sage'),
            value: style ?? 'lines',
            options: styleOptions,
            onChange: (val) => setAttributes({ style: val }),
          }),
          el(ToggleControl, {
            label: __('Apri il primo item di default', 'sage'),
            checked: openFirst ?? true,
            onChange: (val) => setAttributes({ openFirst: val }),
          }),
        ),
        el(
          PanelBody,
          { title: __('Domande', 'sage'), initialOpen: true },
          ...(items ?? []).map((item, index) =>
            el(
              'div',
              {
                key: index,
                style: {
                  borderBottom: '1px solid #e0e0e0',
                  paddingBottom: '12px',
                  marginBottom: '12px',
                },
              },
              el(TextControl, {
                label: `${__('Domanda', 'sage')} ${index + 1}`,
                value: item.question ?? '',
                onChange: (val) => updateItem(index, 'question', val),
              }),
              el(TextareaControl, {
                label: __('Risposta', 'sage'),
                value: item.answer ?? '',
                rows: 3,
                onChange: (val) => updateItem(index, 'answer', val),
              }),
              items.length > 1 &&
                el(
                  Button,
                  { variant: 'link', isDestructive: true, onClick: () => removeItem(index) },
                  __('Rimuovi', 'sage'),
                ),
            ),
          ),
          el(
            Button,
            { variant: 'secondary', onClick: addItem },
            `+ ${__('Aggiungi domanda', 'sage')}`,
          ),
        ),
      ),
      el('div', useBlockProps(), el(ServerSideRender, { block: 'theme/accordion', attributes })),
    )
  },
  save: () => null,
})

// ── theme/products-carousel ──────────────────────────────────────────────────

registerBlockType('theme/products-carousel', {
  edit({ attributes, setAttributes }) {
    const { title, subtitle, limit, categories, orderby, bg } = attributes

    // Fetch all WooCommerce product categories from WP REST
    const allCategories = useSelect((select) => {
      return select('core').getEntityRecords('taxonomy', 'product_cat', {
        per_page: 100,
        hide_empty: false,
        _fields: 'id,name,slug',
      })
    }, [])

    const orderbyOptions = [
      { label: __('Più recenti', 'sage'), value: 'date' },
      { label: __('Più venduti', 'sage'), value: 'popularity' },
      { label: __('Meglio votati', 'sage'), value: 'rating' },
      { label: __('Prezzo crescente', 'sage'), value: 'price' },
      { label: __('Prezzo decrescente', 'sage'), value: 'price-desc' },
      { label: __('Alfabetico A→Z', 'sage'), value: 'title' },
      { label: __('Casuale', 'sage'), value: 'rand' },
    ]

    const toggleCategory = (slug, checked) => {
      const next = checked ? [...categories, slug] : categories.filter((s) => s !== slug)
      setAttributes({ categories: next })
    }

    return el(
      Fragment,
      null,

      el(
        InspectorControls,
        null,

        // ── Testo ──────────────────────────────────────────────────────
        el(
          PanelBody,
          { title: __('Testo', 'sage'), initialOpen: true },
          el(TextControl, {
            label: __('Titolo', 'sage'),
            value: title ?? '',
            onChange: (val) => setAttributes({ title: val }),
          }),
          el(TextControl, {
            label: __('Etichetta sopra il titolo', 'sage'),
            value: subtitle ?? '',
            onChange: (val) => setAttributes({ subtitle: val }),
          }),
        ),

        // ── Prodotti ────────────────────────────────────────────────────
        el(
          PanelBody,
          { title: __('Prodotti', 'sage'), initialOpen: true },
          el(RangeControl, {
            label: __('Numero prodotti', 'sage'),
            value: limit ?? 8,
            min: 2,
            max: 24,
            onChange: (val) => setAttributes({ limit: val }),
          }),
          el(SelectControl, {
            label: __('Ordina per', 'sage'),
            value: orderby ?? 'date',
            options: orderbyOptions,
            onChange: (val) => setAttributes({ orderby: val }),
          }),
        ),

        // ── Categorie ────────────────────────────────────────────────────
        el(
          PanelBody,
          { title: __('Filtra per categoria', 'sage'), initialOpen: false },
          allCategories === null
            ? el('p', { style: { color: '#999', fontSize: '13px' } }, __('Caricamento…', 'sage'))
            : allCategories.length === 0
              ? el(
                  'p',
                  { style: { color: '#999', fontSize: '13px' } },
                  __('Nessuna categoria trovata.', 'sage'),
                )
              : allCategories.map((cat) =>
                  el(CheckboxControl, {
                    key: cat.slug,
                    label: cat.name,
                    checked: categories.includes(cat.slug),
                    onChange: (checked) => toggleCategory(cat.slug, checked),
                  }),
                ),
          categories.length > 0 &&
            el(
              'p',
              { style: { fontSize: '11px', color: '#999', marginTop: '8px' } },
              __('Filtri attivi: ', 'sage') + categories.join(', '),
            ),
        ),

        // ── Stile ────────────────────────────────────────────────────────
        el(
          PanelBody,
          { title: __('Sfondo', 'sage'), initialOpen: false },
          el(SelectControl, {
            label: __('Colore sfondo', 'sage'),
            value: bg ?? 'surface',
            options: bgOptions,
            onChange: (val) => setAttributes({ bg: val }),
          }),
        ),
      ),

      el(
        'div',
        useBlockProps({ style: { minHeight: '120px' } }),
        el(ServerSideRender, { block: 'theme/products-carousel', attributes }),
      ),
    )
  },
  save: () => null,
})
