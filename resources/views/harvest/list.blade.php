@extends('layouts.app')

@section('breadcrumbs')
    {!! breadcrumbs(trans('Harvest')) !!}
@endsection

@section('content')
    <div class="pageHolder">
        <div class="pagePanelHolder">
            <div class="pagePanelHeading">
                <div class="captionHolder">
                    <div class="icoHolder">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="caption">Harvest</div>
                </div>
            </div>
            <div class="pagePanelBody">
                <div class="tableHolder">
                    @if( count($harvests)>0)
                        <table class="table">
                            <thead class="text-warning">
                            <tr>
                                <th>Strain Harvested</th>
                                <th>Declared at</th>
                                <th class="text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $harvests as $crop)
                                <tr>
                                    <td>{{ $crop->strain_harvested }}</td>
                                    <td>{{ $crop->created_at }}</td>
                                    <td class="td-actions text-right">
                                        <a href="{{ route('harvest.show', [$crop->farmer, $crop])  }}" rel="tooltip" class="actionBtn">
                                            <i class="material-icons">remove_red_eye</i>
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
    </div>
@endsection