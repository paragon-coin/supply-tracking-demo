@extends('layouts.app')
@section('content')
    @php
        $formTitle = 'Edit Farmer Profile';
        $formTitleSmall = $farmer->firstname . ' ' . $farmer->lastname;
        $formAction = route('farmer.update', $farmer);
        $formMethod = 'PUT';
    @endphp

    <div class="pageHolder">
        <div class="minContainer">
            <div class="pagePanelHolder">
                <div class="pagePanelBody">
                    <!--      Wizard container        -->
                    @include('farmer._form.index',['farmer'=>\App\Models\Farmer::blockChainFormat($farmer)])
                    <!-- wizard container -->
                </div>
            </div>
        </div>
    </div>

@endsection
