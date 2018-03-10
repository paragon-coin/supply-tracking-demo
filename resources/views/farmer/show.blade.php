@php
    /**
     * @var $eth \App\Components\Ethereum
     * @var $farmer \App\Models\Farmer
     */
@endphp

@section('breadcrumbs')
    {!! breadcrumbs([
        ['label' => 'Farmers', 'url' => route('farmer.index')],
            $farmer->firstname . ' ' . $farmer->lastname
        ]) !!}
@endsection

@extends('layouts.app')

@section('content')
    <div class="pageHolder">
        <div class="farmersProfileHolder">
            <div class="verticalTabsHolder">
                <ul class="tabsNav nav">
                    <li>
                        <a class="active" href="#profile" data-toggle="tab">
                            <span class="icoHolder">
                                <i class="material-icons">dashboard</i>
                            </span>
                            <span class="caption">Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#harvest" data-toggle="tab">
                            <span class="icoHolder">
                                <i class="material-icons">wallpaper</i>
                            </span>
                            <span class="caption">Harvest</span>
                        </a>
                    </li>
                    <li>
                        <a href="#blockchain" data-toggle="tab">
                            <span class="icoHolder">
                                <i class="material-icons">extension</i>
                            </span>
                            <span class="caption">Blockchain</span>
                        </a>
                    </li>
                </ul>

                <div class="tabsContentHolder tab-content">
                    @if($farmer->tx_farm)
                        <div class="btnPanel">
                            <div class="btnGroup">
                                <button id="compare-data" type="button" class="btnGradRed">Check data identity</button>
                            </div>

                            <button id="hack-data" type="button" class="btnGradRed">Hack data</button>
                            {{--<a href="{{ route('farmer.edit', $farmer) }}" class="btnGrey"><i class="fa fa-pencil"></i> Edit</a>--}}
                        </div>
                    @endif

                    <div id="profile" class="tabsContent tab-pane active" role="tabpanel">
                        <div class="pagePanelHolder">
                            <div class="pagePanelBody">
                                <div class="partBlock">
                                    <div class="statusInfo">
                                        <div class="caption">Status:</div>
                                        @include('component.blockchain.section-status', ['tx' => $farmer->tx_farm])
                                    </div>
                                </div>
                                <div class="partBlock">
                                    <div class="heading">Profile</div>
                                    <ul class="posList">
                                        <li>
                                            <span class="bold">First name:</span>
                                            <span>{{$farmer->firstname}}</span>
                                        </li>
                                        <li>
                                            <span class="bold">Last name:</span>
                                            <span>{{$farmer->lastname}}</span>
                                        </li>
                                        <li>
                                            <span class="bold">Email:</span>
                                            <span>{{$farmer->email}}</span>
                                        </li>
                                        <li>
                                            <span class="bold">Address:</span>
                                            <span>{{$farmer->address}}</span>
                                        </li>
                                        <li>
                                            <span class="bold">Ethereum address:</span>
                                            <span>{{$farmer->eth_address}}</span>
                                        </li>
                                    </ul>
                                </div>

                                @if( count($farmer->props_batched)>0)
                                    <div class="partBlock" id="props">
                                        <div class="heading">Properties</div>
                                        <ul class="posList">
                                            @foreach($farmer->props_batched as $infoRecord)
                                                <li>
                                                    <span class="bold">{{$infoRecord['name']}}:</span>
                                                    <span>{{$infoRecord['value']}}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if( count($farmer->files)>0)
                                    <div class="partBlock">
                                        <div class="heading">Documents</div>
                                        <ul class="documentsList">
                                            @foreach($farmer->files as $docRecord)
                                                <li>
                                                <span class="name">
                                                    <span class="bold">{{$docRecord->filename}}.{{$docRecord->extension}}</span> ({{$docRecord->converted_size}})
                                                </span>
                                                    <a href="{{$docRecord->download_link}}" class="icoHolder">
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div id="harvest" class="tabsContent tab-pane" role="tabpanel">
                        @include('harvest.index.content-widget',['harvest'=>$farmer->harvest])
                    </div>
                    <div id="blockchain" class="tabsContent tab-pane" role="tabpanel">
                        <div class="pagePanelHolder">
                            <div class="pagePanelBody">
                                @if($farmer->tx_farm)
                                <div class="blockChainInfoHolder" data-tx-type="@setGrower" data-tx-id="{{$farmer->tx_farm}}"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('farmer.show.modal-data-hack')

    @push('scripts')
    <script>
        $(document).ready(function () {

            $('#compare-data').on('click', function () {
                $.getJSON("{{ route('farmer.check-data-identity', $farmer) }}", function (data) {
                    if (data.error != undefined) {
                        swal('Warning', 'Transaction data not present', 'warning');
                    } else {
                        if (data.equal)
                            swal('All ok!', 'Blockchain data is identical to database', 'success');
                        else
                            swal('Oops!', 'Blockchain data differs with database', 'error');
                    }
                })
            });

            $('#hack-data').on('click', function() {
                $('#change-and-compare').modal('show');
            });

            $('.submit-spinner:not(.disabled)').on('click', function(){
                //$('#loading').removeClass('hidden');

                $(this)
                    .addClass('disabled')
                    .html(
                        '<i class="fa fa-spinner fa-spin"></i> ' +
                        $(this).html()
                    );

            })

            $('.wizard-container form').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });


            var url = "{{ route('tx.render',['type'=>'%type%', 'tx'=>'%tx%', 'render' => 'true'])  }}";
            $.each($('[data-tx-id]'), function(i, el){
                $(el).load( url
                    .split('%tx%').join($(el).attr('data-tx-id'))
                    .split('%type%').join($(el).attr('data-tx-type'))
                );
            });
        });
    </script>
    @endpush

@endsection