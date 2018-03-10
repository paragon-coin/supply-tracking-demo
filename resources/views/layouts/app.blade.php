@php
    if(!isset($dashboard_params)){
        $dashboard_params = [];
            $dashboard_params['active_li_main'] = null;
    }
@endphp
<!doctype html>
<html lang="{{ config('app.name') }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{!! csrf_token() !!}" />
    <link rel="icon" type="image/png" href="https://paragoncoin.com/favicon/favicon-16x16.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="mainPageWrapper">
        <aside class="asideMenu">
            <div class="heading">
                <h3 class="title">
                    <span class="logoPart">ST</span>
                    <span class="namePart">SUPPLY TRACKING</span>
                </h3>
            </div>
            <ul class="menu">
                <li class="@if($dashboard_params['active_li_main']=='dashboard') active @endif">
                    <a href="{{url('/')}}">
                        <span class="icoHolder">
{{--                            <img src="{{url('img/mi1.png')}}" alt="">--}}
                            <i class="material-icons">event_note</i>

                        </span>
                        <span class="caption">Contract</span>
                    </a>
                </li>
                @foreach(\App\Theme\Sidebar::instance()->getItems() as $item)

                    {{--"name" => "Laboratories"--}}
                    {{--"img" => "path"--}}
                    {{--"active" => 0--}}
                    {{--"route" => "lab.index"--}}
                    {{--"params" => []--}}

                    <li class="{{ $item['active'] ? 'active' : null }}">
                        <a href="{{route($item['route'], $item['params'])}}">
                            <span class="icoHolder">
                                <i class="material-icons">{{$item['img']}}</i>

                                {{--<img src="{{url($item['img'])}}" alt="">--}}
                            </span>
                            <span class="caption">{{ $item['name'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>
        <div class="pageLayerHolder">
            <header class="mainHeader">
                <div class="fluidContainer">
                    <div class="wrapper">
                        <a href="#" class="asideMenuTrigger" id="asideMenuTrigger">
                            <span class="icoHolder">
                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                            </span>
                            <span class="caption">{{ array_get($dashboard_params, 'title', \App\Theme\Sidebar::instance()->active('name','Welcome'))}}</span>
                        </a>
                        <a href="{{url('/')}}" class="logo"></a>
                        <div class="btnHolder">
                            <a target="_blank" href="{{ asset('help.pdf') }}" class="helpBtn">
                            <span class="icoHolder">
                                <i class="material-icons">play_for_work</i>
                            </span>
                                <span class="caption">Help</span>
                            </a>
                            <a class="helpBtn" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                @lang('Logout')
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <main class="main">


                <div class="fluidContainer">
                    @yield('breadcrumbs')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
@include('_partials.alert')
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

{{--<script>--}}

    {{--window.comparisonHelper = {--}}

        {{--message: {--}}
            {{--noDataPresent: function () {--}}

                {{--swal(--}}
                    {{--'Warning',--}}
                    {{--'Transaction data not present',--}}
                    {{--'warning'--}}
                {{--);--}}

            {{--},--}}

            {{--success: function () {--}}

                {{--swal(--}}
                    {{--'All ok!',--}}
                    {{--'Blockchain data are identical to database',--}}
                    {{--'success'--}}
                {{--);--}}

            {{--},--}}

            {{--error: function () {--}}

                {{--swal(--}}
                    {{--'Oops!',--}}
                    {{--'Blockchain data differs with database',--}}
                    {{--'error'--}}
                {{--);--}}

            {{--},--}}
        {{--},--}}

        {{--getTxData: function (tx) {--}}

            {{--var data = $('script[type="application/json"][data-tx="' + tx + '"]');--}}

            {{--if( data.length > 0){--}}
                {{----}}
                {{--return JSON.parse(data.html());--}}
                {{----}}
            {{--}else{--}}

                {{--this.message.noDataPresent();--}}
                {{----}}
                {{--return false;--}}
                {{----}}
            {{--}--}}
            {{----}}
        {{--},--}}
        {{----}}
        {{--getDBData: function(tx){--}}
                {{----}}
            {{--var data = $('script[type="application/json"][data-db-for-tx="' + tx + '"]');--}}

            {{--if( data.length > 0){--}}

                {{--return helper.js.object.sortByKey(--}}
                    {{--JSON.parse(--}}
                        {{--data.html()--}}
                    {{--)--}}
                {{--);--}}

            {{--}else{--}}

                {{--this.message.noDataPresent();--}}

                {{--return false;--}}

            {{--}--}}
            {{----}}
        {{--},--}}

        {{--checkData: function(obj1, obj2){--}}

            {{--obj1 = helper.js.object.sortByKey(--}}
                {{--helper.js.object.eachPropertyAsString(obj1)--}}
            {{--);--}}
            {{--obj2 = helper.js.object.sortByKey(--}}
                {{--helper.js.object.eachPropertyAsString(obj2)--}}
            {{--);--}}

            {{--return !!(JSON.stringify(obj1) === JSON.stringify(obj2));--}}

        {{--}--}}

    {{--};--}}

    {{--$(document).ready(function () {--}}

        {{--$('.submit-spinner:not(.disabled)').on('click', function(){--}}
            {{--//$('#loading').removeClass('hidden');--}}

            {{--$(this)--}}
                {{--.addClass('disabled')--}}
                {{--.html(--}}
                    {{--'<i class="fa fa-spinner fa-spin"></i> ' +--}}
                    {{--$(this).html()--}}
                {{--);--}}

        {{--})--}}

        {{--$('.wizard-container form').on('keyup keypress', function(e) {--}}
            {{--var keyCode = e.keyCode || e.which;--}}
            {{--if (keyCode === 13) {--}}
                {{--e.preventDefault();--}}
                {{--return false;--}}
            {{--}--}}
        {{--});--}}

    {{--});--}}

{{--</script>--}}
@stack('scripts')
</body>
</html>