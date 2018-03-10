@extends('layouts.app')

@section('breadcrumbs')
    {!! breadcrumbs(trans('Harvest')) !!}
@endsection

@section('content')
    <div class="pageHolder">
        @include('harvest.index.content-widget')
    </div>
@endsection