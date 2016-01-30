$(document).ready(function() {
	
	$('.date').datepicker({ dateFormat: 'dd-mm-yy' });

    $("#accordion").accordion({
        collapsible: true,
        heightStyle: "content"
    });

	$('.well-attendance').on('click', function(){

		div = $(this);

		user_id = $('#user_id').val();
		event_id = $(this).children('.event_id').val();
		checked = $(this).hasClass('checked');

		if (checked){
			action = "remove";
		}else{
			action = "add";
		}

		$.ajax( "attendance/"+action+"/"+user_id+'/'+event_id )
			.done(function(data) {
				if (data['result'] == 'ok'){
					if (action == "remove"){
						div.removeClass('checked');
					}else if (action == "add"){
						div.addClass('checked');
					} 
				}
			})
			.fail(function() {
			})
			.always(function() {
		});
	})

});