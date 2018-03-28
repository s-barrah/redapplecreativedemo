@extends('layouts.app')

@section('content')
    <div class="container">
        <br/>
        <!-- breadcrumb -->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="javascript:void(0)" onclick="location.href='/'" title="Home">
                            <i class="fa fa-home"></i> Home
                        </a>
                    </li>

                    <li>
                        <a href="javascript:void(0)" onclick="location.href='/playlists'" title="Playlists">
                             Playlists
                        </a>
                    </li>
                    <li class="active">
                        {{ $pageTitle }}
                    </li>
                </ol>
            </div>
        </div>
        <!-- /breadcrumb -->


        <br/>

        <h1 class="text-center">{{ $pageTitle  }}</h1>


        <div class="row">
            <div class="col-sm-6">
                <p><a id="btn-add" name="btn-add" class="btn btn-primary" onclick="addTrack();"><i class="fa fa-plus"></i> Add Track</a></p>

            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-sm-12">
                <div id="notif" class="floating-alert-box alert alert-success text-center hidden"></div>
                <input type="hidden" id="model" name="model" value="tracks">
            </div>
        </div>


        <!-- .table-responsive -->
        <div class="table-responsive">
            <!-- #tracks-table-->
            <table id="tracks-table" frame="box" class="display table-hover table-striped" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th width="10%">
                        <!-- Check all button -->
                        <button type="button" class="btn btn-default btn-sm checkbox-toggle" title="Select All" ><i class="fa fa-square-o"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="multiDelete();" id="delButton" ><i class="fa fa-trash-o"></i></button>
                    </th>

                    <th>Title</th>
                    <th>Artist Names</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Edit / Delete</th>
                </tr>
                </thead>
                <tbody id="track-list">
                @foreach($tracks as $track)
                    <tr id="track{{$track->id}}">
                        <td>
                            <div class="checkbox checkbox-primary" onclick="checkBox(this);">
                                <input type="checkbox" name="cb[]" class="cb" value="{{$track->id}}"><label for="cb"></label>
                            </div>
                        </td>

                        <td><a class="link" href="javascript: void(0)" >{{$track->title}} </a></td>

                        <td><a class="link" href="javascript: void(0)" >{{$track->artist_names}} </a></td>

                        <td><small>{{ date('F j, Y', strtotime($track->created_at)) }}</small></td>
                        <td><small>{{ date('F j, Y', strtotime($track->updated_at)) }}</small></td>
                        <td>
                            <button type="button" data-action="edit" class="btn btn-info btn-xs" title="Edit {{$track->title}}"><i class="fa fa-pencil"></i> Edit</button>
                            <button type="button" class="btn btn-danger btn-xs" value="{{$school->id}}" title="Delete {{$track->title}}"><i class="fa fa-trash-o"></i> Delete</button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <!-- /#schools-table-->
        </div>
        <!-- /.table-responsive -->




    </div>
@endsection
