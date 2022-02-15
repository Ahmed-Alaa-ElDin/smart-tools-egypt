@if ($sortBy !== $field)
    <i class="inline-block text-red-300 fas fa-sort"></i>
@elseif ($sortDirection == 'DESC')
    <i class="inline-block text-red-400 fas fa-sort-up"></i>
@else
    <i class="inline-block text-red-400 fas fa-sort-down"></i>
@endif
