@if ($sortNewBy !== $field)
    <span class="material-icons text-green-400">
        unfold_more
    </span>
@elseif ($sortNewDirection == 'DESC')
    <span class="material-icons text-white">
        arrow_drop_up
    </span>
@else
    <span class="material-icons text-white">
        arrow_drop_down
    </span>
@endif
