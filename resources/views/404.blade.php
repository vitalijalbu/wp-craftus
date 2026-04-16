@extends('layouts.app')

@section('content')
<section class="min-h-[70vh] flex items-center bg-surface" aria-labelledby="error-heading">
  <div class="container">

    <x-empty-state
      code="404"
      :title="__('Pagina non trovata', 'sage')"
      :message="__('La pagina che stai cercando potrebbe essere stata spostata, rinominata o non esiste più.', 'sage')"
      :showSearch="true"
      :buttons="[
        ['url' => home_url('/'), 'label' => __('← Torna in Homepage', 'sage'), 'style' => 'primary'],
        ['url' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop'), 'label' => __('Sfoglia i prodotti', 'sage'), 'style' => 'outline'],
        ['url' => get_permalink(get_page_by_path('contatti')) ?: home_url('/contatti/'), 'label' => __('Contattaci', 'sage'), 'style' => 'ghost'],
      ]"
    />

  </div>
</section>
@endsection
