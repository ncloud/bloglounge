$(window).ready( function() {
	/* input �������� */
	$(".faderInput").each(function() {
		$(this).focus( function() {
			$(this).animate({backgroundColor:'#fefefe'}, 400);
		}).blur( function() {			
			$(this).animate({backgroundColor:'#efefef'}, 400);
		});
	});
});