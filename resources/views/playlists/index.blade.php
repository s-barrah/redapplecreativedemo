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

                    <li class="active">
                        <i class="fa fa-list-ul"></i> {{ $pageTitle }}
                    </li>

                    <li>
                        <a href="javascript:void(0)" onclick="location.href='/tracks'" title="Tracks">
                            <i class="fa fa-music"></i> Tracks
                        </a>
                    </li>
                </ol>
            </div>
        </div>
        <!-- /breadcrumb -->


        <br/>

        <h1 class="text-center">{{ $pageTitle  }}</h1>


        <div class="row">
            <div class="col-sm-6">
                <p><a id="btn-add" name="btn-add" class="btn btn-primary" onclick="addPlaylist();"><i class="fa fa-plus"></i> Create Playlist</a></p>

            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-sm-12">
                <div id="notif" class="floating-alert-box alert alert-success text-center hidden"></div>
                <input type="hidden" id="model" name="model" value="playlists">
            </div>
        </div>


    {{ Form::open(array('name'=>'playlists-form','id'=>'playlists-form',)) }}

        <!-- .table-responsive -->
        <div class="table-responsive">
            <!-- #playlists-table-->
            <table id="playlists-table" frame="box" class="display table-hover table-striped" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th width="10%">
                        <!-- Check all button -->
                        <button type="button" class="btn btn-default btn-sm checkbox-toggle" title="Select All" ><i class="fa fa-square-o"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="multiDelete();" id="delButton" ><i class="fa fa-trash-o"></i></button>
                    </th>

                    <th>Name</th>
                    <th>Track(s)</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>#</th>
                </tr>
                </thead>
                <tbody id="playlists-list">
                @foreach($playlists as $playlist)
                    <tr id="playlist{{$playlist->id}}">
                        <td>
                            <div class="checkbox checkbox-primary" onclick="checkBox(this);">
                                <input type="checkbox" name="cb[]" class="cb" value="{{$playlist->id}}"><label for="cb"></label>
                            </div>
                        </td>

                        <td><a class="link" href="javascript: void(0)" data-action="view" data-content="{{$playlist->id}}" onclick="getPlaylist(this);" title="View {{$playlist->name}}">{{$playlist->name}} </a></td>
                        <td>
                            <small>
                                <ul class="list-unstyled">
                                @if(!$playlist->tracks->isEmpty())
                                    <?php $i = 1 ;?>
                                    @foreach($playlist->tracks as $track)
                                        <li>{{ $i}}. {{$track->title}}</li>
                                            <?php $i++;?>
                                    @endforeach
                                @else
                                        <li>No tracks</li>
                                @endif
                                </ul>
                            </small>
                        </td>
                        <td><small>{{ date('F j, Y', strtotime($playlist->created_at)) }}</small></td>
                        <td><small>{{ date('F j, Y', strtotime($playlist->updated_at)) }}</small></td>
                        <td>
                            <button type="button" data-content="{{$playlist->id}}" onclick="getPlaylist(this);" class="btn btn-info btn-xs" title="Edit {{$playlist->name}}"><i class="fa fa-pencil"></i> Edit</button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <!-- /#schools-table-->
        </div>
        <!-- /.table-responsive -->

    {{ Form::close() }}




    <!-- View Playlist Modal -->
        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 id="playlistName" align="center"></h3>
                    </div>
                    <div class="modal-body">


                        <table class="display table-hover table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="15%">#</th>
                                <th width="35%">Title</th>
                                <th width="35%">Artists</th>
                                <th width="15%">Remove</th>
                            </tr>
                            </thead>
                            <tbody id="playlist-tracks">

                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /View Playlist Modal -->



        <!-- Playlist Modal - Add and Edit Playlists -->
        <div class="modal fade" id="playlistModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <!-- .modal-dialog -->
            <div class="modal-dialog" role="document">

                <!-- .modal-content -->
                <div class="modal-content">

                    <!-- .modal-header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 id="modal-title" align="center">Add New Playlist</h3>
                    </div>
                    <!-- /.modal-header -->

                    <!-- .modal-body -->
                    <div class="modal-body">
                        <div class="form-errors"></div>
                        <div class="alert alert-danger form_errors hidden">
                            <ul class="error-list"></ul>
                        </div>

                        {{ Form::open(array('url'=>'playlist.add','name'=>'playlistForm','id'=>'playlistForm','class'=>'form-horizontal form-label-left')) }}

                        <div class="form-group">

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="name">Playlist Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Playlist Name">
                                <input type="hidden" id="playlist_id" name="playlist_id" value="0">
                            </div>
                        </div>

                        <div class="form-group tracks-group">

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="tracks">Tracks <small>(hold ctrl to select multiple)</small></label>
                                <select name="tracks[]" id="tracks" class="form-control" multiple>
                                    <option value="" disabled selected>Select Tracks</option>
                                   @if($tracks)
                                       @foreach($tracks as $track)
                                    <option value="{{ $track->id }}">{{ $track->title }} - {{ $track->artist_names }}</option>
                                       @endforeach
                                    @else
                                        <option value="">No Tracks</option>
                                    @endif
                                </select>

                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                    <!-- /.modal-body -->

                    <!-- .modal-footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="playlist-btn-save" value="add">Add Playlist</button>


                    </div>
                    <!-- /.modal-footer -->

                </div>
                <!-- /.modal-content -->

            </div>
            <!-- /.modal-dialog -->

        </div>
        <!-- /Playlist Modal-->



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




    </div>
@endsection
