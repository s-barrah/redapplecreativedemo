
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
