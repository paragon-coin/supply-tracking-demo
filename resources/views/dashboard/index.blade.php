@php
/**
 * @var $eth \App\Components\Ethereum
 */
@endphp

@extends('layouts.app')

@section('content')
    <div class="pageHolder">
        <div class="pagePanelHolder">
            <div class="pagePanelHeading start">
                <div class="captionHolder">
                    <div class="icoHolder">
                        <i class="material-icons">receipt</i>
                    </div>
                    <div class="caption">Smart Contract Logs</div>
                </div>
                <div class="addressHolder">
                    <a target="_blank" href="https://rinkeby.etherscan.io/address/{{app('spcv2')->getAddress()}}">{{ app('spcv2')->getAddress() }}</a>
                </div>
            </div>
            <div class="pagePanelBody">
                <ul class="linksList">
                    <li><a target="_blank" href="https://rinkeby.etherscan.io/address/{{app('spcv3')->getAddress()}}#readContract">
                            Main Contract
                        </a></li>
                    <li><a target="_blank" href="https://rinkeby.etherscan.io/address/0x76632F7072b2e4286B60cD9B7261C2c5735Ab02C#readContract">Growers Storage</a></li>
                    <li><a target="_blank" href="https://rinkeby.etherscan.io/address/0x4739fc192792417C9fe942dF007FAA38d57b8552#readContract">Raw Materials Storage</a></li>
                    <li><a target="_blank" href="https://rinkeby.etherscan.io/address/0x3CD2a486042910521Ee6E583dAa3ce16AD004fD7#readContract">Labs Storage</a></li>
                    <li><a target="_blank" href="https://rinkeby.etherscan.io/address/0x1dB56C6dC20D0A0B8586f6f65f6aDBCF19cbcdD5#readContract">Expertises Storage</a></li>
                    {{--<li><a target="_blank" href="https://rinkeby.etherscan.io/address/0x3B9dB6408739e5e3d36EEE0353a4330019452416#readContract">Growers Storage</a></li>--}}
                    {{--<li><a target="_blank" href="https://rinkeby.etherscan.io/address/0x2EDf1Fe74e65338EA139A89054F5cc8f4935cb40#readContract">Raw Materials Storage</a></li>--}}
                    {{--<li><a target="_blank" href="https://rinkeby.etherscan.io/address/0xC2c49dC671d7D851eaD546159889a9cd89E2Cb8F#readContract">Labs Storage</a></li>--}}
                    {{--<li><a target="_blank" href="https://rinkeby.etherscan.io/address/0xC05194d555b82e70260Ee4F3a165412900B07B43#readContract">Expertises Storage</a></li>--}}
                </ul>

                <div class="btnHolder">
                    @if(auth()->user()->farmers()->count())
                        <a href="{{ route('drop') }}" class="btnRed submit-spinner">Delete all data from DB</a>
                    @else
                        <a href="{{ route('recovery') }}" class="btnRed submit-spinner">Restore data from blockchain</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush