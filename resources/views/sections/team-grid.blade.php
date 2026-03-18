@php
  $section_label = $section_label ?? '';
  $section_title = $section_title ?? __('Il nostro team', 'sage');
  $section_sub   = $section_sub   ?? '';
  $bg            = $bg            ?? 'surface';
  $number        = $number        ?? -1;
  $department    = $department    ?? ''; // team_department slug
  $cols          = $cols          ?? 4;  // 3 | 4
  $show_role     = $show_role     ?? true;
  $cta_label     = $cta_label     ?? '';
  $cta_url       = $cta_url       ?? '';

  $args = [
    'post_type'      => 'team',
    'posts_per_page' => (int) $number,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
  ];
  if ($department) {
    $args['tax_query'] = [['taxonomy' => 'team_department', 'field' => 'slug', 'terms' => (array) $department]];
  }
  $team_query = new WP_Query($args);
  $members    = $team_query->posts ?? [];
  wp_reset_postdata();

  $bg_class    = match($bg) { 'cream' => 'bg-cream', 'ink' => 'bg-ink', default => 'bg-surface' };
  $title_class = $bg === 'ink' ? 'text-white'   : 'text-ink';
  $sub_class   = $bg === 'ink' ? 'text-white/50' : 'text-muted';
  $label_class = $bg === 'ink' ? 'text-gold'    : 'text-muted';
  $cols_class  = $cols === 3
    ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'
    : 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4';
@endphp

@if(!empty($members))
<section class="section-luxury {{ $bg_class }}" aria-label="{{ strip_tags($section_title) }}">
  <div class="max-w-360 mx-auto px-6 lg:px-10">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-14">
      <div>
        @if($section_label)
          <span class="section-label {{ $label_class }}" data-scroll="fade">{{ $section_label }}</span>
        @endif
        <h2 class="section-title {{ $title_class }}" data-scroll="text-reveal">{!! $section_title !!}</h2>
        @if($section_sub)
          <p class="section-subtitle mt-4 {{ $sub_class }}" data-scroll="slide-up">{{ $section_sub }}</p>
        @endif
      </div>
      @if($cta_label && $cta_url)
        <a href="{{ esc_url($cta_url) }}" class="btn-ghost shrink-0 self-start {{ $bg === 'ink' ? 'text-white/50 border-white/20 hover:text-white hover:border-white' : '' }}" data-scroll="fade">
          {{ $cta_label }} →
        </a>
      @endif
    </div>

    <ul class="grid {{ $cols_class }} gap-8 lg:gap-10" role="list" data-scroll="stagger">
      @foreach($members as $member)
        @php
          $pid       = $member->ID;
          $thumb_id  = get_post_thumbnail_id($pid);
          $thumb_alt = $thumb_id ? esc_attr(get_post_meta($thumb_id, '_wp_attachment_image_alt', true)) : esc_attr(get_the_title($pid));
          $role      = get_post_meta($pid, '_team_role', true);
          $linkedin  = get_post_meta($pid, '_team_linkedin', true);
          $perma     = esc_url(get_permalink($pid));
        @endphp
        <li class="team-card group" data-scroll-item role="listitem">
          <a href="{{ $perma }}" class="block">
            <div class="team-card__img overflow-hidden aspect-[3/4] mb-4">
              @if($thumb_id)
                <x-picture
                  :id="(int) $thumb_id"
                  :alt="$thumb_alt"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                  sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw"
                />
              @else
                <div class="w-full h-full bg-cream flex items-center justify-center">
                  <span class="font-serif text-4xl text-border">{{ mb_substr(get_the_title($pid), 0, 1) }}</span>
                </div>
              @endif
            </div>
          </a>
          <div>
            <h3 class="font-serif text-lg font-light {{ $bg === 'ink' ? 'text-white' : 'text-ink' }} leading-snug">
              <a href="{{ $perma }}" class="hover:text-primary transition-colors">{{ esc_html(get_the_title($pid)) }}</a>
            </h3>
            @if($show_role && $role)
              <p class="font-sans text-xs tracking-[0.12em] uppercase mt-1 {{ $bg === 'ink' ? 'text-white/40' : 'text-muted' }}">{{ esc_html($role) }}</p>
            @endif
            @if($linkedin)
              <a href="{{ esc_url($linkedin) }}" target="_blank" rel="noopener noreferrer"
                 class="mt-2 inline-block font-sans text-[10px] font-semibold tracking-[0.15em] uppercase {{ $bg === 'ink' ? 'text-gold' : 'text-primary' }} hover:underline"
                 aria-label="{{ __('LinkedIn di', 'sage') }} {{ esc_attr(get_the_title($pid)) }}">
                LinkedIn
              </a>
            @endif
          </div>
        </li>
      @endforeach
    </ul>

  </div>
</section>
@endif
