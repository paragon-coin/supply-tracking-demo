@extends('layouts.app')

@section('content')
    <div class="pageHolder">
        <div class="pagePanelHolder">
            <div class="pagePanelHeading">
                <div class="captionHolder">
                    <div class="icoHolder">
                        <i class="material-icons">colorize</i>
                    </div>
                    <div class="caption">Expertises</div>
                </div>
            </div>

            <div class="pagePanelBody">
                <div class="tableHolder">
                    @if( count($expertises)>0)
                        <table class="table">
                            <thead class="text-warning">
                            <tr>
                                <th>Harvest</th>
                                <th>Farmer</th>
                                <th>Labaratory</th>
                                <th class="text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $expertises as $expertise)
                                <tr>
                                    <td>
                                        <a href="{{ route('harvest.show',[ 'farmer'=>$expertise->harvest->farmer, $expertise->harvest ])  }}">
                                            {{ $expertise->harvest->strain_harvested }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('farmer.show', $expertise->harvest->farmer)  }}">
                                            {{ $expertise->harvest->farmer->firstname }}
                                            {{ $expertise->harvest->farmer->lastname }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $expertise->harvest->farmer->address }}
                                    </td>

                                    <td class="td-actions text-right">
                                        <a href="{{ route('expertise.show', ['lab' => $expertise->laboratory->id, $expertise])  }}" rel="tooltip" class="actionBtn">
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