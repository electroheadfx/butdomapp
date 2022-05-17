
	$( document ).ready(function() {

		$('.datepicker').datepicker({

			language: 'fr',
			format: 'dd/mm/yyyy',
			autoclose: true,
			todayHighlight: true

		});
		
		$('#formloto').parsley({
            errors: {
                errorsWrapper: '<div class="label label-success loterie error"></div>',
                errorElem: '<span></span>'
            }
        });

        $('.parrain').popover({
            placement:'bottom',
            trigger:'hover'
        });

        $('.dropdown-toggle').dropdown();

        $('.filter-mod').click(function(e) {

            $('#filter').text('Filtrer par '+$(this).attr('value'));
            $('#filter').attr('value', $(this).attr('value'));
            $("body").trigger(e);
            return false;
        });


    });