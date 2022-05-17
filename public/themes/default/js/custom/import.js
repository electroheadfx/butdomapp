
	$( document ).ready(function() {

        $("#listfile").find(".btn.render").each(function(){

            $(this).bind('click', function(){

				$("#render").attr('value', $(this).find('input').attr('value'));

            });

        });

        $('#importlist').on('show.bs.modal', function (e) {
        	// alert('yes');
        	if ( $("#render").attr('value') == "" ) {
    	    	$('#importlist').modal('toggle');
	        }
        })

    });