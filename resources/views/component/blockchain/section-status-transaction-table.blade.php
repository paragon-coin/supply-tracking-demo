<span data-tx="{{$tx}}">
    @if($tx)

        @php
        /**
          * @var $tx \App\Models\Transaction
          */
        @endphp

        @if($tx->pending)
            <span class="text-muted" data-bc-status="pending">
                <i class="fa fa-clock-o"></i> Pending...
            </span>
        @elseif($tx->confirmed)
            <span class="text-success" data-bc-status="success">
                <i class="fa fa-check"></i> Confirmed!
            </span>
        @elseif($tx->failed)
            <span class="text-danger" data-bc-status="failed">
                <i class="fa fa-times"></i> Failed!
            </span>
        @endif

    @else
        <span class="text-muted" data-bc-status="none">
            <i class="fa fa-clock-o"></i> Transaction not executed yet
        </span>
    @endif
</span>