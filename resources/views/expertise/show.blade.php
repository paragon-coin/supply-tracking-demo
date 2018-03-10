@extends('layouts.app')

@section('breadcrumbs')
    {!! breadcrumbs([
        ['label' => 'Laboratories', 'url' => route('lab.index')],
        ['label' => $expertise->laboratory->name, 'url' => route('lab.show', $expertise->laboratory)],
            'Expertise: #' . $expertise->uid
        ]) !!}
@endsection

@section('content')
    @php
    $qrUrl = route('qr.eLabel',$expertise->uid);
    @endphp

    <div class="pageHolder">
        <div class="farmersProfileHolder">
            <div class="verticalTabsHolder">
                <ul class="tabsNav nav">
                    <li>
                        <a class="active" href="#profile" data-toggle="tab">
                            <span class="icoHolder">
                                <i class="material-icons">dashboard</i>
                            </span>
                            <span class="caption">Expertise</span>
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
                                    <div class="statusInfo bordered">
                                        <div class="caption">Status:</div>
                                        @include('component.blockchain.section-status', ['tx' => $expertise->tx])
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="qrHolder">
                                            <div class="qr">
                                                <img class="img-thumbnail"
                                                     src="data:image/png;base64,{!! \Milon\Barcode\DNS2D::getBarcodePNG($qrUrl, "QRCODE", 7,7,[85,85,85]);  !!}"
                                                     alt="">
                                            </div>
                                            <div class="btnHolder">
                                                <button class="btnGrad fullWidth"
                                                        data-print-qr="{{ json_encode( with(new \Milon\Barcode\QRcode($qrUrl))->getBarcodeArray() ) }}">
                                                    <i class="fa fa-print"></i>
                                                    print
                                                </button>
                                            </div>
                                            <div class="btnHolder">
                                                <a href="{{$qrUrl}}" class="btnGrad fullWidth"
                                                   target="_blank">
                                                    <i class="fa fa-link"></i>
                                                    Label passport
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xl-8">
                                        <div class="partBlock">
                                            <ul class="posList">
                                                <li>
                                                    <span class="bold">Laboratory:</span>
                                                    <span><a href="{{ route('lab.show',$lab)  }}">{{$lab->name}}</a></span>
                                                </li>

                                                <li>
                                                    <span class="bold">Lab address:</span>
                                                    <span>{{$lab->address}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="partBlock">
                                            <div class="heading">Harvest</div>
                                            <ul class="posList">
                                                <li>
                                                    <span class="bold">Expertise UID:</span>
                                                    <span>#{{$expertise->uid}}</span>
                                                </li>
                                                <li>
                                                    <span class="bold">Created At:</span>
                                                    <span>{{$expertise->created_at}}</span>
                                                </li>
                                                <li>
                                                    <span class="bold">Conclusion:</span>
                                                    <span>{{$expertise->conclusion}}</span>
                                                </li>
                                                <li>
                                                    <span class="bold">Harvest UID:</span>
                                                    <span><a href="{{route('harvest.show', [$expertise->harvest->farmer,$expertise->harvest])}}">#{{$expertise->harvest->uid}}</a></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="blockchain" class="tabsContent tab-pane" role="tabpanel">
                        <div class="pagePanelHolder">
                            <div class="pagePanelBody">
                                @if($expertise->tx)
                                    <div class="blockChainInfoHolder" data-tx-type="@setExpertise" data-tx-id="{{$expertise->tx}}"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="application/json" data-db-for-tx="{{ $expertise->tx }}">{!! json_encode(\App\Models\HarvestExpertise::blockChainFormat($expertise)) !!}</script>
    @push('scripts')

    <script type="application/json" data-qr-print-text>{!! json_encode([

    [
        'Laboratory',
        $expertise->laboratory->name
    ], [
        'Laboratory location',
        $expertise->laboratory->address
    ], [
        'Grower',
        ($expertise->harvest)
            ? implode(' ', [
                $expertise->harvest->farmer->firstname,
                $expertise->harvest->farmer->lastname,
                ])
            : $expertise->farmer_name
    ], [
        'Farm location',
        ($expertise->harvest)
            ? $expertise->harvest->farmer->address
            : $expertise->farmer_address
    ],[
        'Harvest',
        $expertise->harvest->strain_harvested
    ],[
        'Conclusion',
        "{$expertise->conclusion}\r\nDeclared at: {$expertise->created_at->format('d.m.Y')}"
    ],
    ]) !!}</script>


    <script src="/js/qr-printing.js"></script>

    <script>
        $(function(){

            var url = "{{ route('tx.render',['type'=>'%type%', 'tx'=>'%tx%', 'render' => 'true'])  }}";

            $.each($('[data-tx-id]'), function(i, el){
                $(el).load( url
                    .split('%tx%').join($(el).attr('data-tx-id'))
                    .split('%type%').join($(el).attr('data-tx-type'))
                );
            });

            $(document).on('click', '[data-print-qr]', function(){

                var qr = JSON.parse(
                    $(this).attr('data-print-qr')
                );

                var print_text = JSON.parse($('script[data-qr-print-text]').html());
                var output = '';
                $.each(print_text, function(i, item){

                    var option = item[0];
                    var value = item[1];

                    output += '<hr/><span><b>' + option + ':</b> ' + value + '<span></br>';

                });
                console.log(print_text);

                getQRPrinter(qr.bcode)
                    .generate(5, '#e91e63','#fff')
                    .print(
                        '<div style="padding: 15px; border: 1px solid #ccc; display: inline-block;">' +
                        '<div style="padding: 15px; border: 1px solid #ccc; display: inline-block;  float: left; margin-right: 15px; margin-bottom: 15px">',

                        '</div>' + output + '</div>'
                    );
            });
        })
    </script>
    @endpush
@endsection