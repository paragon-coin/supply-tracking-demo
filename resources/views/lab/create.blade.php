@extends('layouts.app')
@php
    $formTitle = 'Create Laboratory Profile';
    $formTitleSmall = 'Just fill the form';
    $formAction = route('lab.store');
    $formMethod = 'POST';
@endphp

@section('breadcrumbs')
    {!! breadcrumbs([
        ['label' => 'Laboratories', 'url' => route('lab.index')],
        'New Lab'
    ]) !!}
@endsection

@section('content')

    <div class="pageHolder">
        <div class="minContainer">
            <div class="pagePanelHolder">
                <div class="pagePanelBody">
                    <!-- Wizard container -->
                    @include('lab._form.index')
                    <!-- wizard container -->
                </div>
            </div>
        </div>
    </div>
@endsection
