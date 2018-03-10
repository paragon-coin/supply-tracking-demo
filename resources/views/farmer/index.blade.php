@extends('layouts.app')

@section('breadcrumbs')
    {!! breadcrumbs(trans('Farmers')) !!}
@endsection

@section('content')
    <div class="pageHolder">
        <div class="pagePanelHolder">
            <div class="pagePanelHeading">
                <div class="captionHolder">
                    <div class="icoHolder">
                        <i class="material-icons">account_circle</i>
                    </div>
                    <div class="caption">Farmers</div>
                </div>
                <div class="btnHolder">
                    <a href="{{route('farmer.create')}}">
                        <i class="material-icons">add</i>
                        <span>Add New</span>
                    </a>
                </div>
            </div>
            <div class="pagePanelBody">
                <div class="tableHolder">
                    @if(count($farmers)>0)
                        <table class="table">
                            <thead class="text-warning">
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Files</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach( $farmers as $farmer)
                                <tr>
                                    <td>{{ $farmer->firstname }} {{ $farmer->lastname }}</td>
                                    <td>{{ $farmer->address }}</td>
                                    <td>
                                        @if( count($farmer->files_batched) )
                                            @foreach( $farmer->files_batched as $farmer_file)
                                                <span class="fileName">
                                                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    <a href="#">{{$farmer_file['filename']}}.{{$farmer_file['extension']}}</a>
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>

                                    <td class="td-actions">
                                        <a href="{{route('harvest.create', $farmer) }}" rel="tooltip" class="actionBtn" data-original-title="" title="">
                                            <i class="material-icons">art_track</i>
                                        </a>
                                        <a href="{{ route('farmer.show', $farmer) }}" rel="tooltip" class="actionBtn" data-original-title="" title="">
                                            <i class="material-icons">person</i>
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