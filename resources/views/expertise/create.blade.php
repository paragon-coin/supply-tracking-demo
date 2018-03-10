@extends('layouts.app')
@php
    $formTitle = 'Publicate Expertise Results';
    $formTitleSmall = 'Expertise results of ' . $lab->name;
    $formAction = route('expertise.store', $lab);
    $formMethod = 'POST';
@endphp

@section('breadcrumbs')
    {!! breadcrumbs([
        ['label' => 'Laboratories', 'url' => route('lab.index')],
        ['label' => $lab->name, 'url' => route('lab.show', $lab)],
        'New Expertise'
        ]) !!}
@endsection

@section('content')
    <div class="pageHolder">
        <div class="minContainer">
            <div class="pagePanelHolder">
                <div class="pagePanelBody">
                    <!--      Wizard container        -->
                    @include('expertise._form.index')
                    <!-- wizard container -->
                </div>
            </div>
        </div>
    </div>
@endsection
