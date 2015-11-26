$(function(){
	var class_stat = {};
	$('#function_table >tbody >tr.prof_item').each(function(){
		var cls = $(this).attr('data');
		if ( !(cls in class_stat) ) class_stat[cls] = 0;
		class_stat[cls]++;
	}).click(function(){
		if ( $(this).hasClass('show_detail') ){
			$(this).removeClass('show_detail');
			$(this).next('tr').hide();
		} else {
			$(this).addClass('show_detail');
			$(this).next('tr').show();
		}
	});
	
	for (var cls in class_stat) {
		$('#hooks').append('<li><a href="#'+cls+'" data="'+cls+'">'+cls+'<span class="label label-'+cls+'">'+class_stat[cls]+'</span></a></li>');
	}

	$('#hooks li').click(function(){
		$('.show_detail').removeClass('show_detail');
		$('#hooks li.active').removeClass('active');
		$(this).addClass('active');
		
		var cls = $("a", this).attr('data').replace('\\','_');
		if( cls != undefined ) {
			$('#function_table >tbody >tr').hide();
			$('#function_table >tbody >tr[data="'+cls+'"]').show();
		} else {
			$('#function_table >tbody >tr.prof_item').show();
		}
		return false;
	});

	$('#tabbable .tabbable .nav li>a').click(function (e) {
	    e.preventDefault();
	    $(this).tab('show');
    });

    $('#profiles').click(function(){
    	if ( $(this).hasClass('open') ) return;
    	
    	var menu = $('#profiles .dropdown-menu');
    	menu.empty().append('<li><a href="?">Loading...</a></li>');
		$.getJSON("?op=profiles",function(data){
			if ( data.length == 0 ) return;

			menu.empty().append('<li><a id="autoLast" href="?">Auto(last)</a><a id="clearAll" href="?op=flush" title="Remove all profiles!" onclick="return confirm(\'Remove all profiles! Are you sure ?\')">Clear</a></li><li class="divider"></li>');
            $.each(data,function(id,profile){
            	var profile_id = profile[0];
            	var uri = profile[1];
				var date = new Date(parseInt(profile_id.substr(0,8),16)*1000);
                menu.append('<li><a href="?profile_id='+profile_id+'" title="'+uri+'"><span class="label-time">'+
                	date.toTimeString().substr(0,5) +'</span>'+uri+'</a></li>');
            });
            //menu.append('<li class="divider"></li><li></li>')
        });
    });

    $('#tree span').click(function(){
    	var tree = $('#tree ul.tree');
    	if ( tree.css('display') == 'none' ) {
    		$(this).removeClass('zoomin');
    		$('#tree').css('padding','25px');
    		tree.show();
    	} else {
    		$(this).addClass('zoomin');
    		$('#tree').css('padding','8px');
    		tree.hide();
    	}
    });

    $(document).on('click','#vars li.active',function(){
    	$('#vars .active').removeClass('active');
    });
})
