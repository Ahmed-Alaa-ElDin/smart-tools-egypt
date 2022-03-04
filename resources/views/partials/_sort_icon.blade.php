@if ($sortBy !== $field)
    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
        preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32" class="inline-block text-red-300">
        <path fill="currentColor" d="m24 24l-8 8l-8-8zM8 8l8-8l8 8z" />
    </svg>
@elseif ($sortDirection == 'DESC')
    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
        preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32" class="inline-block text-red-600">
        <path fill="currentColor" d="m8 8l8-8l8 8z" />
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
        preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32" class="inline-block text-red-600">
        <path fill="currentColor" d="m24 24l-8 8l-8-8z" />
    </svg>
@endif
