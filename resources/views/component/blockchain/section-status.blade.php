<span data-tx="{{$tx}}">
    @if($tx)
        @php
        $tx = \App\Models\TransactionWrapper::find($tx);
        @endphp

        @if($tx->isPending())
            <div class="status">
                <i class="fa fa-clock-o"></i> Pending... ({{ $tx->hash() }})
            </div>
        @elseif($tx->isAccepted())
            <div class="status">
                <i class="fa fa-check"></i> Confirmed!
            </div>
        @else
            <div class="status">
                <i class="fa fa-times"></i> Failed!
            </div>
        @endif

    @else
        <div class="status">
            <i class="fa fa-clock-o"></i> Transaction not executed yet
        </div>
    @endif
</span>