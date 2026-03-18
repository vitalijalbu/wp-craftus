{{-- Reusable table fragment for size-guide.blade.php --}}
@php $table = $table ?? []; $bg = $bg ?? 'surface'; @endphp
<div class="size-guide-table-wrap">
  <table class="size-guide-table {{ $bg === 'ink' ? 'size-guide-table--dark' : '' }}">
    @if(!empty($table['headers']))
      <thead>
        <tr>
          @foreach($table['headers'] as $header)
            <th scope="col">{{ esc_html($header) }}</th>
          @endforeach
        </tr>
      </thead>
    @endif
    <tbody>
      @foreach(($table['rows'] ?? []) as $row)
        <tr>
          @foreach($row as $cell)
            <td>{{ esc_html($cell) }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
