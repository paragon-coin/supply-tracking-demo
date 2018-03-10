@php
    /** @var $lookUpAt string => json([key1, .., keyN])     if batched model is nested, here goes nesting keys path  */
@endphp
<button id="compare-data" type="button" class="btnGradRed" data-path="{{ $lookUpAt or ""}}" data-compare="{{ $tx }}">
    Check data identity
</button>