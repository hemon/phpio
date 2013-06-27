$(function(){
	var class_stat = {};
	$('tbody tr').each(function(){
		var cls = $(this).attr('class');
		if ( !(cls in class_stat) ) class_stat[cls] = 0;
		class_stat[cls]++;
	});
	
	for (var cls in class_stat) {
		$('.nav').append('<li><a href="#'+cls+'" data="'+cls+'">'+cls+'<span class="label label-'+cls+'">'+class_stat[cls]+'</span></a></li>');
	}

	$('.nav li').click(function(){
		$('.nav li.active').removeClass('active');
		$(this).addClass('active');

		var cls = $("a", this).attr('data');
		if( cls != undefined ) {
			$('tbody tr').hide();
			$('tbody tr.'+cls).show();
		} else {
			$('tbody tr').show();
		}
	});
})
