@extends('layouts.app')
@section('breadcrumbs')
    {!! breadcrumbs(trans('Laboratories')) !!}
@endsection

@section('content')
    <div class="pageHolder">
        <div class="pagePanelHolder">
            <div class="pagePanelHeading">
                <div class="captionHolder">
                    <div class="icoHolder">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="caption">Laboratories</div>
                </div>
                <div class="btnHolder">
                    <a href="{{route('lab.create')}}">
                        <i class="material-icons">add</i>
                        <span>Add New</span>
                    </a>
                </div>
            </div>
            <div class="pagePanelBody">
                <div class="tableHolder">
                    @if(count($laboratories)>0)
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
                            @foreach( $laboratories as $laboratory)
                                <tr>
                                    <td>{{ $laboratory->name }}</td>
                                    <td>{{ $laboratory->address }}</td>
                                    <td>

                                        @if( count($laboratory->files_batched) )
                                            @foreach( $laboratory->files_batched as $laboratory_file)
                                                <span class="fileName">
                                                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    <a href="#">{{$laboratory_file['filename']}}.{{$laboratory_file['extension']}}</a>
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>

                                    <td class="td-actions">
                                        <a href="{{route('expertise.create', $laboratory) }}" rel="tooltip" class="actionBtn" data-original-title="" title="">
                                            <i class="material-icons">art_track</i>
                                        </a>
                                        <a href="{{ route('lab.show', $laboratory) }}" rel="tooltip" class="actionBtn" data-original-title="" title="">
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