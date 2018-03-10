<button type="button" class="btn btn-rose" style="display: none"
        data-toggle="modal" data-target="#check-bc-db-modal"
        data-action="compare-block-chain-with-db"
        data-callback="{{$callback}}">
        Check data identity
</button>
<script>$(function () {

    $('button[data-target="#check-bc-db-modal"]').show();

    $(document).on('show.bs.modal','#check-bc-db-modal', function (e) {
        if(typeof e.relatedTarget !== 'undefined' && $(e.relatedTarget).attr('data-callback').length ){
            window[$(e.relatedTarget).attr('data-callback')]();
        }

    })

})</script>