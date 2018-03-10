@extends('layouts.app')

@php
    $formTitle = "Harvest";
    $formTitleSmall = 'Tell us more about your harvest';
    $formAction = route('harvest.store',$farmer);
    $formMethod = 'POST';
@endphp

@section('breadcrumbs')
    {!! breadcrumbs([
        ['label' => 'Farmers', 'url' => route('farmer.index')],
        ['label' => $farmer->firstname . ' ' . $farmer->lastname, 'url' => route('farmer.show', $farmer)],
        'New harvest'
        ]) !!}
@endsection

@section('content')
    <div class="pageHolder">
        <div class="minContainer">
            <div class="pagePanelHolder">
                <div class="pagePanelBody">
                    <!--      Wizard container        -->
                    @include('harvest._form.index')
                    <!-- wizard container -->
                </div>
            </div>
        </div>
    </div>
@endsection
