@extends('layouts.default')

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

    {{ Form::open(array('name'=>'tracks-form','id'=>'tracks-form',)) }}

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
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody id="tracks-list">
                @foreach($tracks as $track)
                    <tr id="track{{$track->id}}">
                        <td>
                            <div class="checkbox checkbox-primary" onclick="checkBox(this);">
                                <input type="checkbox" name="cb[]" class="cb" value="{{$track->id}}"><label for="cb"></label>
                            </div>
                        </td>

                        <td>{{$track->title}}</td>

                        <td>{{$track->artist_names}}</td>

                        <td><small>{{ date('F j, Y', strtotime($track->created_at)) }}</small></td>
                        <td><small>{{ date('F j, Y', strtotime($track->updated_at)) }}</small></td>
                        <td>
                            <button type="button" data-action="add-to-playlist" class="btn btn-primary btn-xs add-to-playlist" data-content="{{$track->id}}" title="Add to Playlist" onclick="getTrack(this);"><i class="fa fa-plus"></i> Add to Playlist</button>
                            <button type="button" data-action="edit" data-content="{{$track->id}}" onclick="getTrack(this);" class="btn btn-info btn-xs" title="Edit {{$track->title}}"><i class="fa fa-pencil"></i> Edit</button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <!-- /#schools-table-->
        </div>
        <!-- /.table-responsive -->


    {{ Form::close() }}

        <!-- Track Modal - Add and Edit Tracks -->
        <div class="modal fade" id="trackModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <!-- .modal-dialog -->
            <div class="modal-dialog" role="document">

                <!-- .modal-content -->
                <div class="modal-content">

                    <!-- .modal-header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 id="modal-title" align="center">Add New Track</h3>
                    </div>
                    <!-- /.modal-header -->

                    <!-- .modal-body -->
                    <div class="modal-body">
                        <div class="alert alert-danger form_errors hidden">
                            <ul class="error-list"></ul>
                        </div>

                        {{ Form::open(array('url'=>'track.add','name'=>'trackForm','id'=>'trackForm','class'=>'form-horizontal form-label-left')) }}

                        <div class="form-group">

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="title">Track Title</label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Enter Track Title">
                            </div>
                        </div>

                        <div class="form-group">

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="artist_names">Artist Names</label>
                                <input type="text" class="form-control" name="artist_names" id="artist_names" placeholder="Enter Artist Names">
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                    <!-- /.modal-body -->

                    <!-- .modal-footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="track-btn-save" value="add">Add Track</button>
                        <input type="hidden" id="track_id" name="track_id" value="0">

                    </div>
                    <!-- /.modal-footer -->

                </div>
                <!-- /.modal-content -->

            </div>
            <!-- /.modal-dialog -->

        </div>
        <!-- /Track Modal-->



        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 id="modal-delete-title" align="center">Delete Records?</h3>
                        <div id="delete-errors"></div>
                    </div>
                    <div class="modal-body">
                        <p id="delete-message"><strong>Are you sure you want to permanently delete the selected records?</strong></p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="hidden" id="delete_id" name="delete_id">

                        <input type="button" id="multi-delete-btn" onclick="confirmMultiDelete();" class="btn btn-danger" value="Delete">
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Modal -->



        <!-- Add To Playlist Modal -->
        <div class="modal fade" id="addToPlaylistModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 align="center">Add <span id="track-title"></span> to a playlist?</h3>
                    </div>
                    <div class="modal-body">
                        <div id="form_errors"></div>
                        {{ Form::open(array('name'=>'addToPlaylistForm','id'=>'addToPlaylistForm','class'=>'form-horizontal form-label-left')) }}

                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-3 col-xs-4"l for="playlist_id">Playlist</label>
                            <div class="col-md-10 col-sm-9 col-xs-8">
                                <input type="text" class="form-control hidden" name="playlist_name" id="playlist_name">
                                <select name="playlist_id" id="playlistID" class="form-control">

                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="hidden" id="trackID" name="trackID">

                        <input type="button" onclick="addTrackToPlaylist();" class="btn btn-primary" value="Add">

                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <!-- /Add to Playlist Modal -->

        <br/><br/>



    </div>
@endsection
