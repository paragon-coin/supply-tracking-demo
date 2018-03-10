@php
$data = array_get($tx, 'decoded_input');

@endphp

<ul class="timeLine">
    <li class="timeLineItem metadata">
        <div class="icoHolder">
            <i class="material-icons">card_travel</i>
        </div>
        <div class="timeLineContent">
            <div class="titleHolder">
                <div class="titleLabel">
                    transaction metadata
                </div>
            </div>
            <ul class="posList">
                <li>
                    <span class="bold">Block Number:</span>
                    <span>{{ array_get($tx, 'blockNumber') }}</span>
                </li>
                <li>
                    <span class="bold">Block Hash:</span>
                    <span>{{ array_get($tx, 'blockHash') }}</span>
                </li>
                <li>
                    <span class="bold">Tx Hash:</span>
                    <span><a target="_blank" href="https://rinkeby.etherscan.io/tx/{{ array_get($tx, 'hash') }}">{{ array_get($tx, 'hash') }}</a></span>

                </li>
                <li>
                    <span class="bold">From:</span>
                    <span>{{ array_get($tx, 'from') }}</span>
                </li>
                <li>
                    <span class="bold">To:</span>
                    <span>{{ array_get($tx, 'to') }}</span>
                </li>
                <li>
                    <span class="bold">Gas:</span>
                    <span>{{ array_get($tx, 'gas') }}</span>
                </li>
                <li>
                    <span class="bold">Gas Price:</span>
                    <span>{{ array_get($tx, 'gasPrice') }}</span>
                </li>
            </ul>
        </div>
    </li>
    <li class="timeLineItem decoded">
        <div class="icoHolder">
            <i class="material-icons">fingerprint</i>
        </div>
        <div class="timeLineContent">
            <div class="titleHolder">
                <div class="titleLabel red">
                    transaction decoded input
                </div>
            </div>
            <ul class="posList">
                <li>
                    <span class="bold">Method:</span>
                    <span>{{ array_get($data, 'method') }}</span>
                </li>
                @if(is_array($data['data']))
                    <script type="application/json" data-tx="{{$tx['hash']}}">{!! json_encode($data['data']) !!}</script>
                    @php
                        $dataBC = array_dot($data['data']);
                    @endphp

                    @foreach($dataBC as $dotKey => $dotValue)
                        <li>
                            <span class="bold">{{ $dotKey }}:</span>
                            <span>{{ is_array($dotValue) ? json_encode($dotValue) : $dotValue }}</span>
                        </li>
                    @endforeach

                @else
                    <li>
                        <span>{{ $data['data'] }}</span>
                    </li>
                @endif
            </ul>
        </div>
    </li>
    <li class="timeLineItem encoded">
        <div class="icoHolder ">
            <i class="material-icons">extension</i>
        </div>
        <div class="timeLineContent">
            <div class="titleHolder">
                <div class="titleLabel">
                    transaction encoded input
                </div>
            </div>
            <ul class="posList">
                <li>
                    <span class="small">{{ array_get($tx, 'input') }}</span>
                </li>
            </ul>
        </div>
    </li>
</ul>