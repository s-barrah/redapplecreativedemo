@extends('layouts.default')


@section('title', $pageTitle)

@section('pageID', $pageID)

@section('content')

    <!-- .container -->
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

        <!-- .panel -->
        <div class="panel panel-primary">

            <!-- .panel-heading -->
            <div class="panel-heading">
                <h3>{{ $playlist->name }} Tracks</h3>
            </div>
            <!-- /.panel-heading -->
            <!-- .panel-body -->
            <div class="panel-body">

                <ul class="list-unstyled">
                    @if($tracks->count() > 0)
                        {{ $i = 1 }}
                        @foreach($tracks as $track)

                            <li>{{ $i }}. {{ $track->title }} - {{ $track->artist_names }} <span class="pull-right"><button class="btn btn-warning btn-xs" data-content="{{ $playlist->id }}" value="{{ $track->id }}" onclick="removeTrackFromPlaylist(this);"  title="Remove {{ $track->title }} from Playlist"><i class="fa fa-ban"></i></button></span></li>

                            {{ $i++ }}
                        @endforeach
                    @else
                        <li><div class="alert alert-default"><i class="fa fa-ban"></i> {{ $playlist->name }} has no tracks yet!</div></li>
                    @endif
                </ul>

            </div>
            <!-- /.panel-body -->

        </div>
        <!-- /.panel -->

    </div>
    <!-- /.container -->

@stop