@php
    /**
     * @var $eth \App\Components\Ethereum
     * @var $laboratory \App\Models\Laboratory
     */
@endphp

@extends('layouts.app')

@section('breadcrumbs')
    {!! breadcrumbs([
        ['label' => 'Laboratories', 'url' => route('lab.index')],
            $laboratory->name
        ]) !!}
@endsection

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
                                <i class="material-icons">colorize</i>
                            </span>
                            <span class="caption">Expertises</span>
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
                    <div id="profile" class="tabsContent tab-pane active" role="tabpanel">
                        <div class="pagePanelHolder">
                            <div class="pagePanelBody">
                                <div class="partBlock">
                                    <div class="statusInfo">
                                        <div class="caption">Status:</div>
                                        @include('component.blockchain.section-status', ['tx' => $laboratory->tx_lab])
                                    </div>
                                </div>
                                <div class="partBlock">
                                    <div class="heading">Profile</div>
                                    <ul class="posList">
                                        <li>
                                            <span class="bold">Name:</span>
                                            <span>{{$laboratory->name}}</span>
                                        </li>
                                        <li>
                                            <span class="bold">Address:</span>
                                            <span>{{$laboratory->address}}</span>
                                        </li>
                                        <li>
                                            <span class="bold">Ethereum address:</span>
                                            <span>{{$laboratory->eth_address}}</span>
                                        </li>
                                    </ul>
                                </div>

                                @if( count($laboratory->props_batched)>0)
                                    <div class="partBlock" id="props">
                                        <div class="heading">Properties</div>
                                        <ul class="posList">
                                            @foreach($laboratory->props_batched as $infoRecord)
                                                <li>
                                                    <span class="bold">{{$infoRecord['name']}}:</span>
                                                    <span>{{$infoRecord['value']}}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if( count($laboratory->files)>0)
                                    <div class="partBlock">
                                        <div class="heading">Documents</div>
                                        <ul class="documentsList">
                                            @foreach($laboratory->files as $docRecord)
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
                        @include('expertise.index.content-widget',['lab'=>$laboratory,'list'=>$laboratory->expertise])
                    </div>

                    <div id="blockchain" class="tabsContent tab-pane" role="tabpanel">
                        <div class="pagePanelHolder">
                            <div class="pagePanelBody">
                                @if($laboratory->tx_lab)
                                    <div class="blockChainInfoHolder" data-tx-type="@setGrower" data-tx-id="{{$laboratory->tx_lab}}"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="application/json" data-db-for-tx="{{ $laboratory->tx_lab }}">{!! json_encode(\App\Models\Laboratory::blockChainFormat($laboratory)) !!}</script>
    @push('scripts')
    <script>
        $(function(){

            var url = "{{ route('tx.render',['type'=>'%type%', 'tx'=>'%tx%', 'render' => 'true'])  }}";

            $.each($('[data-tx-id]'), function(i, el){
                $(el).load( url
                    .split('%tx%').join($(el).attr('data-tx-id'))
                    .split('%type%').join($(el).attr('data-tx-type'))
                );
            });

        })
    </script>
    @endpush
@endsection