@extends('layouts.app')
@php
    $formTitle = 'Create Farmer Profile';
    $formTitleSmall = 'Just fill the form';
    $formAction = route('farmer.store');
    $formMethod = 'POST';
@endphp

@section('breadcrumbs')
    {!! breadcrumbs([
        ['label' => 'Farmers', 'url' => route('farmer.index')],
        'New Farmer'
    ]) !!}
@endsection

@section('content')
    <div class="pageHolder">
        <div class="minContainer">
            <div class="pagePanelHolder">
                <div class="pagePanelBody">
                    <!--      Wizard container        -->
                    @include('farmer._form.index', ['farmer'=>[]])
                    <!-- wizard container -->
                </div>
            </div>
        </div>
    </div>
@endsection
