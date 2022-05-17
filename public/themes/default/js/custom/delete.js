
	$( document ).ready(function() {

        $('.deleteuser').on('click', function(){

           $('#deleteaction').attr('href', $(this).attr('data-url'));
           $('#userdeleted').text($(this).attr('data-email'));

        });


    });