<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\PlaylistTrack;
use App\Models\Track;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /**
     * Tracks Index page view.
     */
    public function index(){

        return view('tracks.index')
            ->with('pageTitle', 'Tracks')
            ->with('pageID', 'tracks')
            ->with('tracks', Track::orderBy('id', 'asc')->get());

    }


    /**
     * Get Track Details.
     *
     * @param  int  $id
     * @return \App\Models\Track
     */
    public function get_track2(Request $request){

        //GET TRACK OBJECT
        $track = Track::find($request->id);

        //RETURN DATA
        $data = array(
            'id' => $track->id,
            'title' => $track->title,
            'artist_names' => $track->artist_names,
        );
        return response()->json($data);
    }


    /**
     * Get Track Details.
     *
     * @param  Request object
     * @return \App\Models\Track
     */

    public function get_track(Request $request){

        //GET TRACK OBJECT
        $track = Track::find($request->id);
        $playlistArray = [];
        foreach($track->playlists as $playlist){
            $playlistArray[] = $playlist->name;
        }

        //SELECT DROPDOWN FOR PLAYLISTS
        //AND PREVENT DUPLICATES
        $select_playlists = '<option value="">Select Playlist</option>';
        //GET ALL SCHOOLS
        $playlists = Playlist::all();
        foreach ($playlists as $playlist){
            if(!in_array($playlist->name, $playlistArray)){
                $select_playlists .= '<option value="'.$playlist->id.'">'.$playlist->name.'</option>';
            }
        }
        //RETURN DATA
        $data = array(
            'id' => $track->id,
            'title' => $track->title,
            'artist_names' => $track->artist_names,
            'created' => date('F j, Y g:i a', strtotime($track->created_at)),
            'updated' => date('F j, Y g:i a', strtotime($track->updated_at)),
            'playlists' => implode('; ', $playlistArray),
            'select' => $select_playlists,
        );

        //RETURN DATA AS JSON
        return response()->json($data);
        //return response()->json($member);
    }


    /**
     * Add track with validation.
     * @variable Request object
     *@return $data
     */
    public function create(Request $request){

        $data = [];

        //VALIDATE FORM DATA
        $validation = Track::validate($request->all());

        //VALIDATION FAILS
        if($validation->fails()){

            //GET VALIDATION ERRORS
            $errors = $validation->errors()->all();

            $data['errors'] = $errors;
            $data['success'] = false;
        }else{
            $track = Track::create(array(
                'title' => $request->title,
                'artist_names' => $request->artist_names,
            ));

            $data['id'] = $track->id;
            $data['title'] = $track->title;
            $data['artist_names'] = $track->artist_names;
            $data['created_at'] = date('F j, Y g:i a', strtotime($track->created_at));
            $data['updated_at'] = date('F j, Y g:i a', strtotime($track->updated_at));


            $message = 'Track has been added!';
            $data['message'] = $message;
            $data['records_count'] = Track::count();
            $data['success'] = true;
        }
        //RETURN DATA AS JSON
        //return Response::json($data);
        return response()->json($data);
    }



    /**
     * Update Track with validation.
     * @variable AJAX Request object
     *@return $data
     */
    public function update(Request $request){

        //$id = Input::get('id');
        //INITIALISE VARIABLES
        $data = [];

        //VALIDATE ALL INPUTS
        $validation = Track::validate($request->all());

        //VALIDATION FAILS
        if($validation->fails()){

            //GET VALIDATION ERRORS
            $errors = $validation->errors()->all();
            $data['errors'] = $errors;
            $data['success'] = false;
        }else{

            //UPDATE DB
            Track::whereId($request->id)->update(array(
                'title' => $request->title,
                'artist_names' => $request->artist_names,
            ));

            //GET OBJECT USING ID
            $track = Track::find($request->id);

            $data['id'] = $track->id;
            $data['title'] = $track->title;
            $data['artist_names'] = $track->artist_names;
            $data['created_at'] = date('F j, Y g:i a', strtotime($track->created_at));
            $data['updated_at'] = date('F j, Y g:i a', strtotime($track->updated_at));

            $message = 'Track updated successfully!';
            $data['message'] = $message;
            $data['success'] = true;

        }
        //RETURN DATA AS JSON
        //return Response::json($data);
        return response()->json($data);
    }


    /**
     * Delete Tracks .
     * @variable Request object
     *@return $data
     */
    public function delete_tracks(Request $request){

        //get checked items from post
        $checked = $request->cb;

        if($checked){

            //start count
            $i = 0;

            //LOOP THROUGH CHECKED VALUES
            //AND DELETE
            foreach($checked as $each){

                //Track::find($each)->delete();
                $track = Track::find($each);

                //DETACH ID FROM PIVOT TABLE
                $track->playlists()->detach($each);

                $track->delete();

                $i++;
            }
            //Track::whereIn('id',$request->cb)->delete();

            //NOTIFICATION MESSAGE
            $message = 'Track deleted successfully!';
            if($i > 1){
                $message = $i.' tracks deleted successfully!';
            }
            //RETURN DATA AS JSON
            $data['message'] = $message;
            $data['success'] = true;
            //return Response::json($data);
        }

        $data['message'] = 'Track(s) could not be deleted!';

        return response()->json($data);
    }


    /**
     * Add track to a new playlist.
     * @variable Request object
     *@return $data
     */
    public function add_to_playlist(Request $request)
    {

        //INITIALISE VARIABLES
        $data = [];

        $track = Track::find($request->track_id);

        //take a playlist and add a track
        $playlist = Playlist::find($request->playlist_id);

        if($track && $playlist){

            //CHECK IF TRACK ALREADY ON PLAYLIST
            $playlistTrack = PlaylistTrack::where('track_id',$request->track_id)
                ->where('playlist_id',$request->playlist_id)
                ->first();

            if(!$playlistTrack){
                $track->playlists()->attach($playlist->id);
                //$track->playlists()->syncWithoutDetaching([$playlist->id]);

                $message = $track->title.' has been added to '.$playlist->name.'!';
                $data['message'] = $message;
                $data['success'] = true;
            }else{
                $message = $track->title.' is already a member of '.$playlist->name.'!';
                $data['errors'] = $message;
                $data['success'] = false;
            }


        }else{
            $data['errors'] = 'Request Error!';
            $data['success'] = false;
        }

        //RETURN DATA AS JSON
        //return Response::json($data);
        return response()->json($data);
    }


    /**
     * Remove track from playlist.
     * @variable Request object
     *@return $data
     */
    public function remove_from_playlist(Request $request){
        //INITIALISE VARIABLES
        $data = [];

        $track = Track::find($request->track_id);

        //get playlist and remove  track
        $playlist = Playlist::find($request->playlist_id);

        if($track && $playlist){

            $track->playlists()->detach($track->id);
            //$track->playlists()->syncWithoutDetaching([$track->id]);

            $message = $track->title.' has been removed from '.$playlist->name.'!';
            $data['message'] = $message;
            $data['success'] = true;

        }else{
            $data['errors'] = 'Request Error!';
            $data['success'] = false;
        }

        //RETURN DATA AS JSON
        //return Response::json($data);
        return response()->json($data);
    }





}
