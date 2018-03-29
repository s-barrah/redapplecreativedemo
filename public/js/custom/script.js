$(document).ready(function () {

    // Material Select Initialization
    //$('.mdb-select').material_select();


    //HIDE SPINNER
    $( "#load" ).hide();

    // NAVBAR FUNCTION
    // MAKE CURRENT URL MENU ITEM ACTIVE
    var url = window.location;
    $('ul.nav a[href="'+ url +'"]').parent().addClass('active');
    $('ul.nav a').filter(function() {
        return this.href == url;
    }).parent().addClass('active');


    //create / update playlist
    $("#playlist-btn-save").click(function (e) {
        savePlaylist();
    });

    //create / update track
    $("#track-btn-save").click(function (e) {
        saveTrack();
    });

    //default disable delete button
    $('#delButton').attr('disabled', 'disabled');


    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
        var clicks = $(this).data('clicks');
        if (clicks) {
            //Uncheck all checkboxes
            $("input[type='checkbox']").prop('checked', false);

            //CHANGE BUTTON ICON
            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');

            //DISABLE DELETE BUTTON
            $('#delButton').attr('disabled', 'disabled');

        } else {
            //ENABLE DELETE BUTTON
            $('#delButton').removeAttr('disabled');

            //Check all checkboxes
            $("input[type='checkbox']").prop('checked', true);

            //CHANGE BUTTON ICON
            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
        }
        $(this).data("clicks", !clicks);

    });

});

