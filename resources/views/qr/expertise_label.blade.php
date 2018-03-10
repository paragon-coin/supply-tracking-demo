@extends('layouts.qr_passport')

@section('content')
    <div class="customCard">
        <div class="cardHeader">
            <div class="icoHolder">
                <i class="fa fa-check"></i>
            </div>
            <div class="titleHolder">
                <h2>{{$lab['name']}}
                    <span class="smaller">({{\Carbon\Carbon::parse($expertise['created_at']['date'])->format('d.m.Y')}}) </span>
                </h2>
            </div>
        </div>
    </div>

    <div class="customCard">
        <div class="cardHeader">
            <h2>Conclusion</h2>
        </div>
        <div class="cardContent">
            <p>{{ $expertise['conclusion'] }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="customCard">
                <div class="cardHeader">
                    <h2>Laboratory profile</h2>
                </div>
                <div class="cardContent">
                    <ul class="customList">
                        @include('qr._label.qr_list_item',['iName' => "Name",    'iValue' => $lab['name']])
                        @include('qr._label.qr_list_item',['iName' => "Address", 'iValue' => $lab['address']])

                        @foreach($lab['props_batched'] as $item)
                            @include('qr._label.qr_list_item',['iName' => $item['name'], 'iValue' => $item['value']])
                        @endforeach

                        @if(!empty($lab['files_batched']))
                            <li>
                                <span class="bold">Files:</span>
                            </li>
                            @foreach($lab['files_batched'] as $file)
                                @include('qr._label.qr_list_file_item',[
                                    'iDownloadlink' => $file['download_link'],
                                    'iFilename' => $file['filename'],
                                    'iExtension' => $file['extension'],
                                    'iBytes' => $file['bytes'],
                                ])
                            @endforeach
                        @endif
                    </ul>

                </div>
                <div class="mapHolder">
                    @if(!empty($lab['gm_lat']))
                        <div id="lab-location" class="mapLike map" data-lat="{{$lab['gm_lat']}}" data-lon="{{$lab['gm_lon']}}"></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="customCard">
                <div class="cardHeader">
                    <h2>Grower profile</h2>
                </div>
                <div class="cardContent">
                    <ul class="customList">
                        @include('qr._label.qr_list_item',['iName' => "Name", 'iValue' => ($farmer['data']['firstname']).' '.($farmer['data']['lastname'])])
                        @include('qr._label.qr_list_item',['iName' => "Address", 'iValue' => $farmer['data']['address']])

                        @if($farmer['existing'])
                            @foreach($farmer['data']['props_batched'] as $item)
                                @include('qr._label.qr_list_item',['iName' => $item['name'], 'iValue' => $item['value']])
                            @endforeach

                            @if(!empty($farmer['data']['files_batched']))
                                <li>
                                    <span class="bold">Files:</span>
                                </li>
                                @foreach($farmer['data']['files_batched'] as $file)
                                        @include('qr._label.qr_list_file_item',[
                                        'iDownloadlink' => $file['download_link'],
                                        'iFilename' => $file['filename'],
                                        'iExtension' => $file['extension'],
                                        'iBytes' => $file['bytes'],
                                    ])
                                @endforeach
                            @endif
                        @endif
                    </ul>
                </div>
                <div class="mapHolder">
                    @if($farmer['existing'] and !empty($farmer['data']['gm_lat']))
                        <div id="farmer-location" class="mapLike map" data-lat="{{$farmer['data']['gm_lat']}}"
                             data-lon="{{$farmer['data']['gm_lon']}}"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="customCard">
        <div class="cardHeader">
            <h2>Harvest</h2>
        </div>
        <div class="cardContent">
            <ul class="customList">
                @include('qr._label.qr_list_item',['iName' => 'Strain Harvested', 'iValue' => $harvest['strain_harvested']])
                @include('qr._label.qr_list_item',['iName' => 'Number of Plants', 'iValue' => isset($harvest['number_of_plants']) ? $harvest['number_of_plants'] : 0])
                @include('qr._label.qr_list_item',['iName' => 'Wet Plant', 'iValue' => (isset($harvest['wet_plant']) ? $harvest['wet_plant'] : 0).' '.$harvest['weight_measurement']])
                @include('qr._label.qr_list_item',['iName' => 'Wet Trim', 'iValue' => (isset($harvest['wet_trim']) ? $harvest['wet_trim'] : 0).' '.$harvest['weight_measurement']])
                @include('qr._label.qr_list_item',['iName' => 'Wet Flower', 'iValue' => (isset($harvest['wet_flower']) ? $harvest['wet_flower'] : 0).' '.$harvest['weight_measurement']])
                @include('qr._label.qr_list_item',['iName' => 'Dry Trim', 'iValue' => (isset($harvest['dry_trim']) ? $harvest['dry_trim'] : 0).' '.$harvest['weight_measurement']])
                @include('qr._label.qr_list_item',['iName' => 'Dry Flower', 'iValue' => (isset($harvest['dry_flower']) ? $harvest['dry_flower'] : 0).' '.$harvest['weight_measurement']])
                @include('qr._label.qr_list_item',['iName' => 'Seeds', 'iValue' => $harvest['seeds']])
                @include('qr._label.qr_list_item',['iName' => 'Total Usable Flower', 'iValue' => (isset($harvest['total_usable_flower']) ? $harvest['total_usable_flower'] : 0).' '.$harvest['weight_measurement']])
                @include('qr._label.qr_list_item',['iName' => 'Total Usable Trim', 'iValue' => (isset($harvest['total_usable_trim']) ? $harvest['total_usable_trim'] : 0).' '.$harvest['weight_measurement']])
            </ul>
            <div class="date">
                DECLARED AT: {{\Carbon\Carbon::parse($harvest['created_at']['date'])->format('d.m.Y')}}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        
        function initAutocomplete() {

            if(document.getElementById('lab-location')){

                makePreviewOnlyGoogleMapWithMarker(
                    'lab-location',
                    document.getElementById('lab-location').getAttribute('data-lat'),
                    document.getElementById('lab-location').getAttribute('data-lon')
                );
            }
            if(document.getElementById('farmer-location')){
                makePreviewOnlyGoogleMapWithMarker(
                    'farmer-location',
                    document.getElementById('farmer-location').getAttribute('data-lat'),
                    document.getElementById('farmer-location').getAttribute('data-lon')
                );
            }

        }

    </script>
    @include('component.google-app.google-places-preview-only')
    @include('component.google-app.google-places')
@endpush
