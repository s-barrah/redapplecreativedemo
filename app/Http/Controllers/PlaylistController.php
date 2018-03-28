<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
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
            ->with('playlists', Playlist::orderBy('id', 'asc')->get())
            ->with('tracks', Track::orderBy('id', 'asc')->get());

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
            ->with('school', $playlist)
            ->with('tracks', $playlist->tracks);

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
            $track = Track::find($request->track_id);

            if($playlist && $track){

                $track->playlists()->attach($playlist->id);
                //$school->members()->syncWithoutDetaching([$playlist->id]);

                $message = $track->name.' has been added to '.$playlist->name.'!';
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
     * Edit Playlist
     * @variable $id.
     */
    public function edit($id, $random){

        $playlist = Playlist::find($id);

        $data = array(
            'pageTitle' => 'Edit - '.ucwords($playlist->name),
            'pageID' => 'playlists',
            'playlist' => $playlist
        );
        return view('playlists.edit', $data);

    }















}
