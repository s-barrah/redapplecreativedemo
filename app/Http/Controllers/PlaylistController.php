<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\PlaylistTrack;
use App\Models\Track;
use Illuminate\Http\Request;
use Redirect;

class PlaylistController extends Controller
{
    /**
     * Users page view.
     */
    public function index(){

        return view('playlists.index')
            ->with('pageTitle', 'Playlists')
            ->with('pageID', 'playlists')
            //->with('members', Member::all());
            ->with('playlists', Playlist::orderBy('id', 'asc')->get());

    }


    /**
     * View Playlist
     * @variable $id.
     */
    public function view($id, $random){

        $playlist = Playlist::find($id);

        return view('playlists.view')
            ->with('pageTitle', 'View - '.ucwords($playlist->name))
            ->with('pageID', 'playlists')
            ->with('playlist', $playlist)
            ->with('tracks', $playlist->tracks);

    }

    /**
     * Get Playlist Details.
     *
     * @param  int  $id
     * @return \App\Models\Playlist
     */
    public function get_playlist(Request $request){

        //GET PLAYLIST OBJECT
        $playlist = Playlist::find($request->id);

        $tracks = '';
        $countTracks = 0;
        //GENERATE LIST OF TRACKS
        if($playlist->tracks->count() > 0){
            $i = 1;
            foreach ($playlist->tracks as $track){
                $tracks .= '<tr id="'.$track->id.'">';
                $tracks .= '<td>'.$i.'</td>';
                $tracks .= '<td>'.$track->title.'</td>';
                $tracks .= '<td>'.$track->artist_names.'</td>';
                $tracks .= '<td><button class="btn btn-warning btn-xs" data-content="'.$request->id.'" value="'.$track->id.'" onclick="removeTrackFromPlaylist(this);"  title="Remove '.$track->title.' from '.$playlist->name.' "><i class="fa fa-ban"></i></button></td>';
                $i++;
                $countTracks++;
            }
        }else{
            $tracks .= '<tr>';
            $tracks .= '<td colspan="4"><div class="alert alert-default text-center"><i class="fa fa-ban"></i> '.$playlist->name.' has no tracks yet!</div></td>';
            $tracks .= '</tr>';
        }

        //RETURN DATA
        $data = array(
            'id' => $playlist->id,
            'name' => $playlist->name,
            'countTracks' => $countTracks,
            'tracks' => $tracks,
        );
        return response()->json($data);
    }


    /**
     * Add New Playlist with validation.
     * @variable Request object
     *@return $data
     */
    public function create(Request $request)
    {

        //INITIALISE VARIABLES
        $data = [];

        //VALIDATE FORM DATA
        $validation = Playlist::validate(['name' => $request->name]);

        //VALIDATION FAILS
        if($validation->fails()){

            //return Redirect::route('register')
            //->withErrors($validation)->withInput();
            //GET VALIDATION ERRORS
            $errors = $validation->errors()->all();
            //$errors = json_decode($errors);
            $data['errors'] = $errors;
            $data['success'] = false;

        }else{
            //CREATE NEW USER USING POST DATA
            $playlist = Playlist::create(array(
                'name' => $request->name,
            ));


            //take a track and add a playlist
            //$track = Track::find($request->track_id);

            if($playlist){

                //$track->playlists()->attach($playlist->id);
                //$school->members()->syncWithoutDetaching([$playlist->id]);

                //$message = $track->name.' has been added to '.$playlist->name.'!';
                $message = $playlist->name.' has been created!';
                $data['message'] = $message;
                $data['success'] = true;

            }else{
                $data['errors'] = 'Form Error!';
                $data['success'] = false;
            }

            //return Redirect::route('thanks')
            // ->with('message', $message);
        }
        //RETURN DATA AS JSON
        //return Response::json($data);
        return response()->json($data);
    }



    /**
     * Update Playlist with validation.
     * @variable AJAX Request object
     *@return $data
     */
    public function update(Request $request){

        //$id = Input::get('id');
        //INITIALISE VARIABLES
        $data = [];

        //VALIDATE ALL INPUTS
        $validation = Playlist::validate($request->all());

        //VALIDATION FAILS
        if($validation->fails()){

            //GET VALIDATION ERRORS
            $errors = $validation->errors()->all();
            $data['errors'] = $errors;
            $data['success'] = false;
        }else{

            //UPDATE DB
            Playlist::whereId($request->id)->update(array(
                'name' => $request->name,
            ));

            //GET OBJECT USING ID
            $playlist = Playlist::find($request->id);

            $data['id'] = $playlist->id;
            $data['title'] = $playlist->name;
            $data['created_at'] = date('F j, Y g:i a', strtotime($track->created_at));
            $data['updated_at'] = date('F j, Y g:i a', strtotime($track->updated_at));

            $message = 'Playlist updated successfully!';
            $data['message'] = $message;
            $data['success'] = true;

        }
        //RETURN DATA AS JSON
        //return Response::json($data);
        return response()->json($data);
    }


    /**
     * Delete Playlists .
     * @variable Request object
     *@return $data
     */
    public function delete_playlists(Request $request){

        //get checked items from post
        $checked = $request->cb;

        if($checked){

            //start count
            $i = 0;

            //LOOP THROUGH CHECKED VALUES
            //AND DELETE
            foreach($checked as $each){

                //Playlist::find($each)->delete();
                $playlist = Playlist::find($each);

                //DETACH ID FROM PIVOT TABLE
                $playlist->tracks()->detach($each);

                $playlist->delete();

                $i++;
            }
            //Playlist::whereIn('id',$request->cb)->delete();

            //NOTIFICATION MESSAGE
            $message = 'Playlist deleted successfully!';
            if($i > 1){
                $message = $i.' playlists deleted successfully!';
            }
            //RETURN DATA AS JSON
            $data['message'] = $message;
            $data['success'] = true;
            //return Response::json($data);
        }

        $data['message'] = 'Playlist(s) could not be deleted!';

        return response()->json($data);
    }














}
