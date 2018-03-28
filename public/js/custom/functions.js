
/*
* Display modal form for creating new Schools
*
* */
function addTrack() {

    //HIDE ERRORS
    $('.form_errors').addClass("hidden");

    //RESET THE FORM
    $('#trackForm').trigger("reset");
    $('#track_id').val('0');

    //CHANGE HEADER
    $('#modal-title').html("Add New Track");

    //UPDATE BUTTON
    $('#track-btn-save').val("add");
    $('#track-btn-save').html("Add Track");

    //SHOW MODAL
    $('#trackModal').modal('show');

}


/*
* Display modal for editing
*
* */
function getTrack(obj) {

    //CLEAR ERROR MESSAGE
    $('#form_errors').html('');
    $('.error-list').html('');

    //SHOW SPINNER
    $( "#load" ).show();

    var formURL = "track/details";

    //get action type, Edit or View or Add
    var action = $(obj).attr('data-action');

    //GET ID FROM ELEMENT
    var track_id = $(obj).val();

    //ENSURE NO EMPTY VALUES
    if(track_id === '' || formURL === ''){
        //DISPLAY ALERT NOTIFICATION
        printSingleErrorMsg("#notif", "Missing variables");
        return;
    }

    //SETUP CSRF TOKEN FOR AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    //GET DATA TO POST
    //PLAYLIST ID
    var formData = {
        id: track_id,
    }

    //AJAX CALL
    $.ajax({

        type: "POST",
        url: formURL,
        data: formData,
        dataType: 'json',
        success: function (data) {

            //HIDE SPINNER
            $( "#load" ).hide();

            if(action == 'add-to-playlist'){
                //POPULATE FORM WITH DATA
                $('#trackID').val(data.id);
                $('#track-title').html(data.name);
                $('#playlist').html(data.playlist_name);

                //UPDATE SELECT
                $('#playlistID').html(data.select);

                //SHOW MODAL
                $('#addToPlaylistModal').modal('show');

            }else{

                //POPULATE FORM WITH DATA
                $('#track_id').val(data.id);
                $('#title').val(data.title);
                $('#artist_names').val(data.artist_names);

                //CHANGE HEADER
                $('#modal-title').html("Edit - "+data.title);

                //UPDATE BUTTON
                $('#track-btn-save').val("update");
                $('#track-btn-save').html("Save changes");

                //SHOW MODAL
                $('#trackModal').modal('show');
            }


        },
        error: function (xhr, status, error) {
            console.log('Status:', status);
            console.log('Error:', error);
        }
    });
}


/*
* Submit Add/Update Track
* to db
* */
function saveTrack() {

    //SHOW SPINNER
    $( "#load" ).show();

    var title = $('#title').val();
    var artist_names = $('#artist_names').val();
    var id = $('#track_id').val();

    //ENSURE NO EMPTY VALUES
    if(id === '' || title === '' || artist_names === '' ){
        //HIDE SPINNER
        $( "#load" ).hide();

        //DISPLAY ERROR MESSAGE
        printErrorMsg(".form_errors","All fields are required");
        return;
    }

    //var formData = new FormData(document.getElementById('memberForm'));
    var formData = {
        title: title,
        artist_names: artist_names,
        id: id,
    }


    //SETUP CSRF TOKEN FOR AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    var my_url = 'track/add';//for creating new resource; default url

    //used to determine the url to use, add or update
    var state = $('#track-btn-save').val();
    if (state == "update"){
        my_url = 'track/update';//edit/update url
    }
    //console.log(formData);

    $.ajax({

        type: "POST",
        url: my_url,
        data: formData,
        dataType: 'json',
        success: function (data) {

            //HIDE SPINNER
            $( "#load" ).hide();

            //console.log(data);

            //ACTION SUCCESSFUL
            if(data.success == true) {
                //console.log(data);

                //RESET FORM
                $('#trackForm').trigger("reset");

                //HIDE MODAL
                $('#trackModal').modal('hide');

                var track = '<tr id="track' + data.id + '">';
                track += '<td><div class="checkbox checkbox-primary" onclick="checkBox(this);"><input type="checkbox" name="cb[]" class="cb" value="' + data.id + '"><label for="cb"></label></div></td>'
                track += '<td>' + data.title + '</td>';
                track += '<td>' + data.artist_names + '</td>';
                track += '<td>' + data.created_at + '</td>';
                track += '<td>' + data.updated_at + '</td>';
                track += '<td><a class="btn btn-info btn-xs" title="Edit ' + data.title + '" href="javascript:void(0)"  value="' + data.id + '" onclick="editTrack(this);" ><i class="fa fa-pencil"></i></a></td></tr>';

                if (state == "add"){ //if user added a new record
                    $('#tracks-list').append(track);

                    //UPDATE RECORD COUNT
                    $('#records-count').html(data.records_count);
                }else{ //if member updated an existing record

                    $("#track" +  data.id).replaceWith(track);
                }

                //DISPLAY ALERT NOTIFICATION
                printSuccessMsg('#notif',data.message);

                //HIDE ALERT AND RELOAD PAGE AFTER WAITING
                hideAndReload('#notif');

            }//ELSE
            else if(data.success == false){
                //HIDE SPINNER
                $( "#load" ).hide();

                //DISPLAY ERROR MESSAGE
                printErrorMsg(".form_errors",data.errors);


            }

        },
        error: function (xhr, status, error) {
            console.log('Error:', error);
        }
    });

}



/*
* Add Track to Playlist
*
* */
function addTrackToPlaylist() {

    //SHOW SPINNER
    $( "#load" ).show();

    var playlist_id = $('#playlistID').val();
    var track_id = $('#trackID').val();

    //ENSURE NO EMPTY VALUES
    if(playlist_id === '' || track_id === ''){
        //HIDE SPINNER
        $( "#load" ).hide();

        //DISPLAY ERROR MESSAGE
        printSingleErrorMsg('#form_errors','All fields are required!');
        return;
    }

    var formData = {
        playlist_id: playlist_id,
        track_id: track_id,
    }

    var my_url = "track/playlist/add";

    //console.log(formData);

    $.ajax({

        type: "POST",
        url: my_url,
        data: formData,
        dataType: 'json',
        success: function (data) {

            //HIDE SPINNER
            $( "#load" ).hide();

            //console.log(data);

            //ACTION SUCCESSFUL
            if(data.success == true) {
                //console.log(data);

                //RESET FORM
                $('#addToPlaylistForm').trigger("reset");

                //HIDE MODAL
                $('#addToPlaylistModal').modal('hide');

                //DISPLAY ALERT NOTIFICATION
                printSuccessMsg('#notif',data.message);

                //HIDE ALERT AND RELOAD PAGE AFTER WAITING
                hideAndReload('#notif');

            }//ELSE
            else if(data.success == false){
                //HIDE SPINNER
                $( "#load" ).hide();

                //DISPLAY ERROR MESSAGE
                printSingleErrorMsg('#form_errors',data.errors);

            }

        },
        error: function (xhr, status, error) {
            console.log('Error:', error);
        }
    });

}


function removeTrackFromPlaylist(obj) {

    //GET IDs FROM ELEMENTS
    var playlist_id = $(obj).attr('data-content');

    var track_id = $(obj).val();

    var formURL = "track/playlist/remove";

    //ENSURE NO EMPTY VALUES
    if(track_id === '' || playlist_id === ''){
        //LOG
        console.log("Missing variables");
        return;
    }

    //SETUP CSRF TOKEN FOR AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    //GET DATA TO POST
    var formData = {
        track_id: track_id,
        playlist_id: playlist_id,
    }

    $.ajax({

        type: "POST",
        url: formURL,
        data: formData,
        dataType: 'json',
        success: function (data) {

            //HIDE SPINNER
            $( "#load" ).hide();

            //console.log(data);

            //ACTION SUCCESSFUL
            if(data.success == true) {
                //console.log(data);

                //DISPLAY ALERT NOTIFICATION
                printSuccessMsg('#notif',data.message);

                //HIDE ALERT AND RELOAD PAGE AFTER WAITING
                hideAndReload('#notif');

            }//ELSE
            else if(data.success == false){
                //HIDE SPINNER
                $( "#load" ).hide();

                //DISPLAY ERROR MESSAGE
                printSingleErrorMsg('#form_errors',data.errors);

            }

        },
        error: function (xhr, status, error) {
            console.log('Error:', error);
        }
    });

}


function filterByPlaylist(obj) {
    var playlist_id = $(obj).val();
    var playlist_name = $("option:selected",obj).text();

    if(playlist_id != '0'){
        var search = playlist_name;
        var i = 3;
        $('#tracks-table').DataTable().column(i).search(
            search, true, true, true
        ).draw();
    }else{
        $('#tracks-table').DataTable().search('').columns().search('').draw();
    }
}


function addPlaylist() {

    //SHOW SPINNER
    $( "#load" ).show();


    $('#modal-title').text("Add New Playlist");
    $('#playlist-btn-save').show();
    $('#playlist-btn-save').val("add");
    $('#playlist-btn-save').html("Add Playlist");

    //RESET THE FORM
    $('#playlistForm').trigger("reset");
    $('#playlist_id').val('0');

    //HIDE SPINNER
    $( "#load" ).hide();

    //SHOW MODAL
    $('#playlistModal').modal('show');
}


function getPlaylist(obj) {

    //CLEAR ERROR MESSAGE
    $('#form_errors').html('');

    //RESET ALERT
    $("#notif").addClass('hidden');
    $("#notif").addClass('alert-success');

    //SHOW SPINNER
    $( "#load" ).show();


    var formURL = "playlist/details";

    //get action type, Edit or View
    var action = $(obj).attr('data-action');

    //GET ID FROM ELEMENT
    var playlist_id = $(obj).attr('data-content');

    console.log(playlist_id);

    //ENSURE NO EMPTY VALUES
    if(playlist_id === '' || formURL === ''){
        //DISPLAY ALERT NOTIFICATION
        printSingleErrorMsg("#notif", "Missing variables");
        return;
    }

    //SETUP CSRF TOKEN FOR AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    //GET DATA TO POST
    //PLAYLIST ID
    var formData = {
        id: playlist_id,
    }

    //AJAX CALL
    $.ajax({

        type: "POST",
        url: formURL,
        data: formData,
        dataType: 'json',
        success: function (data) {

            //HIDE SPINNER
            $( "#load" ).hide();

            if(action == 'edit'){

                //POPULATE FORM WITH DATA
                $('#playlist_id').val(data.id);
                $('#name').val(data.name);

                //CHANGE HEADER
                $('#modal-title').html("Edit - "+data.name);

                //UPDATE BUTTON
                $('#playlist-btn-save').val("update");
                $('#playlist-btn-save').html("Save changes");

                //SHOW MODAL
                $('#playlistModal').modal('show');

            }else{

                var s = 'track';
                if(data.countTracks > 1){
                    s = 'tracks';
                }
                $('#playlistName').html(data.name+' <small>('+data.countTracks+' '+s+')</small>');

                //DISPLAY TRACKS
                $('#playlist-tracks').html(data.tracks);

                //SHOW MODAL
                $('#viewModal').modal('show');
            }


        },
        error: function (xhr, status, error) {
            console.log('Status:', status);
            console.log('Error:', error);
        }
    });

}



/*
* Submit Add/Update Playlist
* to db
* */
function savePlaylist() {

    $( ".form_errors" ).addClass('hidden');

    //SHOW SPINNER
    $( "#load" ).show();

    var name = $('#name').val();
    var email = $('#email').val();
    var school_id = $('#school_id').val();
    var id = $('#member_id').val();

    var my_url = "member/add";//for creating new resource; default url

    //used to determine the url to use, add or update
    var state = $('#btn-save').val();
    if (state == "update"){
        my_url = "member/update";//edit/update member url
    }

    //ENSURE NO EMPTY VALUES
    if(id === '' || email === ''){
        //HIDE SPINNER
        $( "#load" ).hide();

        //DISPLAY ERROR MESSAGE
        printSingleErrorMsg('.form-errors','All fields are required!');
        return;
    }

    //ENSURE SELECTED SCHOOL
    if(state != "update" && (school_id === '' || school_id.length < 1)){
        //HIDE SPINNER
        $( "#load" ).hide();

        //DISPLAY ERROR MESSAGE
        printSingleErrorMsg('.form-errors','Please select a school!');
        return;
    }

    //var formData = new FormData(document.getElementById('memberForm'));
    var formData = {
        name: name,
        email: email,
        school_id: school_id,
        id: id,
    }


    //SETUP CSRF TOKEN FOR AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    //console.log(formData);

    $.ajax({

        type: "POST",
        url: my_url,
        data: formData,
        dataType: 'json',
        success: function (data) {

            //HIDE SPINNER
            $( "#load" ).hide();

            //console.log(data);

            //ACTION SUCCESSFUL
            if(data.success == true) {
                //console.log(data);

                //RESET FORM
                $('#memberForm').trigger("reset");

                //HIDE MODAL
                $('#memberModal').modal('hide');

                var member = '<tr id="member' + data.id + '">';
                member += '<td><div class="checkbox checkbox-primary" onclick="checkBox(this);"><input type="checkbox" name="cb[]" class="cb" value="' + data.id + '"><label for="cb"></label></div></td>'
                member += '<td>' + data.id + '</td>';
                member += '<td><a href="javascript: void(0)" data-action="view" data-content="' + data.id + '" onclick="getMember(this);" title="View ' + data.name + '">' + data.name + '</a></td>';
                member += '<td>' + data.email + '</td>';
                member += '<td><small>' + data.created_at + '<small></td>';
                member += '<td><small>' + data.updated_at + '</small></td>';
                member += '<td><button type="button" data-action="add-to-school" class="btn btn-primary btn-xs add-to-school" data-content="' + data.id + '" onclick="getMember(this);" title="Add to School"><i class="fa fa-plus"></i> Add to School</button> ';
                member += '<button type="button" data-action="edit" class="btn btn-info btn-xs" data-content="' + data.id + '" onclick="getMember(this);" title="Edit ' + data.name + '"><i class="fa fa-pencil"></i> Edit</button> ';
                member += '<button type="button" class="btn btn-danger btn-xs" value="' + data.id + '" onclick="deleteItem(this);" title="Delete ' + data.name + '"><i class="fa fa-trash-o"></i> Delete</button></td></tr>';

                if (state == "add"){ //if user added a new record
                    $('#members-list').append(member);
                }else{ //if member updated an existing record
                    $("#member" +  data.id).replaceWith(member);
                }

                //DISPLAY ALERT NOTIFICATION
                printSuccessMsg('#notif',data.message);

                //HIDE ALERT AND RELOAD PAGE AFTER WAITING
                hideAndReload('#notif');

            }//ELSE
            else if(data.success == false){
                //HIDE SPINNER
                $( "#load" ).hide();

                //DISPLAY ERROR MESSAGE
                printErrorMsg(".form_errors",data.errors);

            }

        },
        error: function (xhr, status, error) {
            console.log('Error:', error);
        }
    });

}




//Function to print success messages
//after ajax completion
function printSuccessMsg(el, msg) {
    //DISPLAY MESSAGE
    $(el).html('<i class="fa fa-check-circle"></i> '+msg);

    //REMOVE HIDDEN CLASS
    $(el).removeClass('hidden');
}


//Function to print error messages
//from post validation
function printErrorMsg(el, msg) {

    //CLEAR PREVIOUS MESSAGE
    $(el).find('ul').html('');

    //SHOW ELEMENT
    $(el).removeClass('hidden');

    //DISPLAY EACH ERROR AS A LIST ITEM
    $.each(msg, function (key, value) {
        $(el).find('ul').append('<li> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+value+'</li>');
    });
}


//Function to print error messages
//from post validation
function printSingleErrorMsg(el, msg) {
    //DISPLAY
    $(el).html('<div class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle"></i> '+msg+'</div>');

}

//Function to hide notifications
//and reload page ajax completion
function hideAndReload(el) {
    setTimeout(function() {
        //SLIDE UP NOTIFICATION
        //$(el).slideUp({ opacity: "hide" }, "slow");
        $(el).fadeOut();
        //RELOAD CURRENT WINDOW/PAGE
        window.location.reload(true);
    }, 6000);
}


//CHECK AND UNCHECK CLICKED CHECKBOX
function checkBox(obj) {

    //GET THE CHECKBOX
    var checkbox = $(obj).find("input[type='checkbox']");

    //IF CHECKED
    if(checkbox.is(':checked')){
        //UNCHECK
        checkbox.prop('checked', false);
    }else{
        //CHECK THE CHECKBOX
        checkbox.prop('checked', true);
    }
    //Handle delete and select all buttons
    checkBoxState();
}

//Check all checkboxes state
//enable and disable delete button
//toggle select all button
function checkBoxState() {

    //ENABLE AND DISABLE BUTTONS ACCORDINGLY
    //IF ANY CHECKBOX IS CHECKED
    if($(".checkbox input[type='checkbox']:checked").length > 0){

        //ENABLE DELETE BUTTON
        $('#delButton').removeAttr('disabled');

        //CHANGE BUTTON ICON
        $(".checkbox-toggle .fa").removeClass("fa-square-o").addClass('fa-check-square-o');
    }else{

        //CHANGE BUTTON ICON
        $(".checkbox-toggle .fa").removeClass("fa-check-square-o").addClass('fa-square-o');

        //DISABLE DELETE BUTTON
        $('#delButton').attr('disabled', 'disabled');
    }
}