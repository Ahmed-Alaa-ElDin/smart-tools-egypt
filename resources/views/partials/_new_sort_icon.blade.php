@if ($sortNewBy !== $field)
    <i class="text-green-400 fas fa-sort"></i>
@elseif ($sortNewDirection == 'DESC')
    <i class="text-white fas fa-sort-up"></i>
@else
    <i class="text-white fas fa-sort-down"></i>
@endif
