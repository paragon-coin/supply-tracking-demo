@php
/**
 * @var $eth \App\Components\Ethereum
 */
@endphp

@extends('layouts.app')

@section('content')
    {{--<table class="table table-striped table-inverse">--}}
        {{--<thead>--}}
            {{--<tr>--}}
                {{--<th>#</th>--}}
                {{--<th>Function</th>--}}
                {{--<th>Result</th>--}}
            {{--</tr>--}}
        {{--</thead>--}}
        {{--<tbody>--}}
            {{--<tr>--}}
                {{--<th scope="row">1</th>--}}
                {{--<td>$eth->web3_clientVersion()</td>--}}
                {{--<td>Otto</td>--}}
            {{--</tr>--}}
        {{--</tbody>--}}
    {{--</table>--}}

    @php
        var_dump($eth->web3_clientVersion());
        var_dump($eth->web3_sha3('0x68656c6c6f20776f726c64'));
        var_dump($eth->net_version());
        var_dump($eth->decode_hex($eth->net_peerCount()));
        var_dump($eth->net_listening());
        var_dump($eth->eth_protocolVersion());
        var_dump($eth->eth_syncing());
        var_dump($eth->eth_coinbase());
        var_dump($eth->eth_mining());
        var_dump($eth->eth_gasPrice());
        var_dump($eth->eth_accounts());
        var_dump($eth->eth_blockNumber());
        var_dump($eth->eth_getBalance($eth->eth_coinbase(), 'latest'));
    @endphp



@endsection