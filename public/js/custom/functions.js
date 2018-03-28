
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

function editTrack(obj) {
    //CLEAR ERROR MESSAGE
    $('#form_errors').html('');

    //RESET ALERT
    $("#notif").addClass('hidden');
    $("#notif").addClass('alert-success');

    //SHOW SPINNER
    $( "#load" ).show();

    var formURL = "track/details";

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
    //MEMBER ID
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