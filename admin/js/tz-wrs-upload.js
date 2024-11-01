jQuery(function($){
 
	// on upload button click
	$('body').on( 'click', '.misha-upl', function(e){
 
		e.preventDefault();
 
		var button = $(this),
		custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on('select', function() { // it also has "open" and "close" events
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('.def_image.logo').hide();
			button.removeClass( "button-secondary" );
			button.html('<img height="200" src="' + attachment.url + '">').next().val(attachment.id).next().show();
			$('#submit').trigger('click');
		}).open();

	});
 
	// on remove button click
	$('body').on('click', '.misha-rmv', function(e){
 
		e.preventDefault();
 
		var button = $(this);
		button.next().val(''); // emptying the hidden field
		button.hide().prev().html('Upload image');
		$('#submit').trigger('click');
	});
 
	// on upload button click
	$('body').on( 'click', '.misha-upl-bg', function(e){
 
		e.preventDefault();
 
		var button = $(this),
		custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on('select', function() { // it also has "open" and "close" events
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('.def_image.bg').hide();
			button.removeClass( "button-secondary" );
			button.html('<img height="200" src="' + attachment.url + '">').next().val(attachment.id).next().show();
			$('#submit').trigger('click');
		}).open();

	});
 
	// on remove button click
	$('body').on('click', '.misha-rmv-bg', function(e){
 
		e.preventDefault();
 
		var button = $(this);
		button.next().val(''); // emptying the hidden field
		button.hide().prev().html('Upload image');
		$('#submit').trigger('click');
	});
 
	// on upload button click
	$('body').on( 'click', '.misha-upl-pbg', function(e){
 
		e.preventDefault();
 
		var button = $(this),
		custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on('select', function() { // it also has "open" and "close" events
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('.def_image.pbg').hide();
			button.removeClass( "button-secondary" );
			button.html('<img height="200" src="' + attachment.url + '">').next().val(attachment.id).next().show();
			$('#submit').trigger('click');
		}).open();

	});
 
	// on remove button click
	$('body').on('click', '.misha-rmv-pbg', function(e){
 
		e.preventDefault();
 
		var button = $(this);
		button.next().val(''); // emptying the hidden field
		button.hide().prev().html('Upload image');
		$('#submit').trigger('click');
	});
 
});