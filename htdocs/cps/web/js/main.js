$(document).ready(function() {
	
	$('.date').datepicker({ dateFormat: 'dd-mm-yy' });

    $( "#sel1, #sel2" ).change(function() {
		$( '#select_event' ).submit();
	});

	$( ".change_role" ).change(function() {

		user_id = $(this).attr('id').split('-')[0];
		position = $(this).val();

		$.ajax( "change_position/"+user_id+'/'+position )
			.done(function(data) {
			})
			.fail(function() {
			})
			.always(function() {
		});
	});

	$('.well-attendance').on('click', function(){

		div = $(this);

		user_id = $('#user_id').val();
		event_id = $(this).children('.event_id').val();
		checked = $(this).hasClass('btn-success');

		if (checked){
			action = "remove";
		}else{
			action = "add";
		}

		$.ajax( action+"/"+user_id+'/'+event_id )
			.done(function(data) {
				if (data['result'] == 'ok'){
					if (action == "remove"){
						div.removeClass('btn-success');
					}else if (action == "add"){
						div.addClass('btn-success');
					} 
				}
			})
			.fail(function() {
			})
			.always(function() {
		});
	})

});