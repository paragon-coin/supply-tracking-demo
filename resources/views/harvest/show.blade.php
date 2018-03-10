@php
    /**
     * @var $eth \App\Components\Ethereum
     * @var $farmer \App\Models\Farmer
     * @var $expertise \App\Models\Harvest
     */
    $expertise = $harvest->expertise;
@endphp

@section('breadcrumbs')
{!! breadcrumbs([
        ['label' => 'Farmers', 'url' => route('farmer.index')],
        ['label' => $farmer->firstname . ' ' . $farmer->lastname, 'url' => route('farmer.show', $farmer)],
        $harvest->uid
        ]) !!}
@endsection

@extends('layouts.app')

@section('content')
    @php
        $qrUrl = route('qr.hLabel',$harvest->uid);
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
                            <span class="caption">Harvest</span>
                        </a>
                    </li>
                    <li>
                        <a href="#expertises" data-toggle="tab">
                            <span class="icoHolder">
                                <i class="material-icons">gavel</i>
                            </span>
                            <span class="caption">Expertise results</span>
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
                                        @include('component.blockchain.section-status', ['tx' => $harvest->tx])
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
                                                    <span class="bold">Farmer:</span>
                                                    <span><a href="{{ route('farmer.show',$farmer)  }}">{{$farmer->firstname}} {{$farmer->lastname}}</a></span>
                                                </li>
                                                <li>
                                                    <span class="bold">Farmer address:</span>
                                                    <span>{{$farmer->address}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="partBlock">
                                            <div class="heading">Harvest</div>
                                            <ul class="posList">
                                                <li>
                                                    <span class="bold">Strain Harvested:</span>
                                                    <span>{{$harvest->strain_harvested}}</span>
                                                </li>
                                                <li>
                                                    <span class="bold">Number of Plants:</span>
                                                    <span>{!! isset($harvest->number_of_plants) ? $harvest->number_of_plants : 0  !!}</span>
                                                </li>

                                                <li>
                                                    <span class="bold">Wet Plant:</span>
                                                    <span>{!! isset($harvest->wet_plant) ? $harvest->wet_plant : 0  !!} {{$harvest->weight_measurement}}</span>
                                                </li>
                                                <li>
                                                    <span class="bold">Wet Trim:</span>
                                                    <span>{!! isset($harvest->wet_trim) ? $harvest->wet_trim : 0  !!} {{$harvest->weight_measurement}}</span>
                                                </li>

                                                <li>
                                                    <span class="bold">Wet Flower:</span>
                                                    <span>{!! isset($harvest->wet_flower) ? $harvest->wet_flower : 0  !!} {{$harvest->weight_measurement}}</span>
                                                </li>
                                                <li>
                                                    <span class="bold">Dry Trim:</span>
                                                    <span>{!! isset($harvest->dry_trim) ? $harvest->dry_trim : 0  !!} {{$harvest->weight_measurement}}</span>
                                                </li>

                                                <li>
                                                    <span class="bold">Dry Flower:</span>
                                                    <span>{!! isset($harvest->dry_flower) ? $harvest->dry_flower : 0  !!} {{$harvest->weight_measurement}}</span>
                                                </li>
                                                <li>
                                                    <span class="bold">Seeds:</span>
                                                    <span>{{$harvest->seeds}}</span>
                                                </li>

                                                <li>
                                                    <span class="bold">Total Usable Flower:</span>
                                                    <span>{!! isset($harvest->total_usable_flower) ? $harvest->total_usable_flower : 0  !!} {{$harvest->weight_measurement}}</span>
                                                </li>

                                                <li>
                                                    <span class="bold">Total Usable Trim:</span>
                                                    <span>{!! isset($harvest->total_usable_trim) ? $harvest->total_usable_trim : 0  !!} {{$harvest->weight_measurement}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="expertises" class="tabsContent tab-pane" role="tabpanel">
                        @if(!empty($expertise))
                            <div class="pagePanelHolder">
                                <div class="pagePanelHeading">
                                    <div class="captionHolder">
                                        <div class="icoHolder">
                                            <i class="material-icons">assignment</i>
                                        </div>
                                        <div class="caption">Expertises</div>
                                    </div>
                                </div>
                                <div class="pagePanelBody">
                                    <div class="tableHolder">
                                        @if( count($expertise)>0)
                                            <table class="table">
                                                <thead class="text-warning">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Laboratory</th>
                                                    <th>Created At</th>
                                                    <th class="text-right">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($expertise as $exp)
                                                    <tr>
                                                        <td>{{ $exp->id }}</td>
                                                        <td>{{ $exp->laboratory->name }}</td>
                                                        <td>{{ $exp->created_at }}</td>
                                                        <td class="td-actions text-right">
                                                            <a href="{{ route('expertise.show',['lab'=>$exp->laboratory->id, $exp]) }}" rel="tooltip" class="actionBtn">
                                                                <i class="material-icons">visibility</i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="blockchain" class="tabsContent tab-pane" role="tabpanel">
                        <div class="pagePanelHolder">
                            <div class="pagePanelBody">
                                @if($harvest->tx)
                                    <div class="blockChainInfoHolder" data-tx-type="@setRawMaterial" data-tx-id="{{$harvest->tx}}"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="application/json" data-db-for-tx="{{ $harvest->tx }}">{!! json_encode(\App\Models\Harvest::blockChainFormat($harvest)) !!}</script>
    @push('scripts')

    <script type="application/json" data-qr-print-text>{!! json_encode([
    [
        'Grower',
        ($farmer)
            ? implode(' ', [
                $farmer->firstname,
                $farmer->lastname,
                ])
            : $expertise->farmer_name
    ],
    ['Strain Harvested', $harvest->strain_harvested],
    ['Number of Plants', $harvest->number_of_plants],
    ['Wet Plant', $harvest->wet_plant.' '.$harvest->weight_measurement],
    ['Wet Trim', $harvest->wet_trim.' '.$harvest->weight_measurement],
    ['Wet Flower', $harvest->wet_flower.' '.$harvest->weight_measurement],
    ['Dry Trim', $harvest->dry_trim.' '.$harvest->weight_measurement],
    ['Dry Flower', $harvest->dry_flower.' '.$harvest->weight_measurement],
    ['Seeds', $harvest->seeds],
    ['Total Usable Flower', $harvest->total_usable_flower.' '.$harvest->weight_measurement],
    ['Total Usable Trim', $harvest->total_usable_trim.' '.$harvest->weight_measurement],
    ['Created at', $harvest->created_at->format('d.m.Y')]
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