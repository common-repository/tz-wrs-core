jQuery(document).ready(function(){

	// Message DJ
	jQuery(document).on("click","#on_air_panel.message_dj",function(){ 
		var dj = jQuery(this).data('dj');
		jQuery.ajax({
			url : wrsLocal.ajax_url,
			type : 'post',
			data : {
				action : 'tzwrs_msgdj',
				dj : dj
			},
			beforeSend: function() {
				jQuery('#msg-modal-content .wpcf7').fadeIn().html('<div class="placeholder-item"></div>');
				jQuery('#msg-modal-content h2').fadeIn().html('<div class="placeholder-heading"></div>');
				jQuery('#msg-modal-content .djtomsg').fadeIn().html('<div class="placeholder-image"></div>');
			},
			success : function( response ) {
				var str_array = response.split('||');
				jQuery('#msg-modal-content h2.onAirDJ').html( str_array[0] );
				jQuery('#msg-modal-content .djtomsg').html( str_array[1] );
				jQuery('#msg-modal-content .wpcf7').replaceWith( str_array[2] );
				jQuery( ".djtomsg" ).data( 'footl', str_array[3] );
				const forms = document.querySelectorAll( '#id05 div.wpcf7 > form' );
				forms.forEach( form => wpcf7.init( form ) );
			}
		});
	});
	
	// update no shows div
	window.setInterval(fetch_quotes, 7100);
	function fetch_quotes() {
		if ( document.hasFocus() ) {
			if ( jQuery( ".noshows" ).length ) {
				jQuery.ajax({
					url : wrsLocal.ajax_url,
					type : 'post',
					data : {
						ajaxed : 1,
						pic_width : jQuery('#noshows').data('pic_width'),
						textsize : jQuery('#noshows').data('textsize'),
						action : 'tzwrs_shows_coming_up'
					},
					beforeSend: function() {
						jQuery('#shows').fadeOut(500);
					},
					success: function (response) {
						jQuery('#shows').html(response);
						jQuery('#shows').fadeIn(500);
					}
				});
			}
		}
	}

	// update some shows div
	window.setInterval(fetch_some_quotes, 7100);
	function fetch_some_quotes() {
		if ( document.hasFocus() ) {
			if ( jQuery( ".some_shows" ).length ) {
				jQuery.ajax({
					url : wrsLocal.ajax_url,
					type : 'post',
					data : {
						ajaxed : 1,
						pic_width : jQuery('#some_shows').data('pic_width'),
						textsize : jQuery('#some_shows').data('textsize'),
						action : 'tzwrs_shows_coming_up'
					},
					beforeSend : function() {
						jQuery('#some_shows').fadeOut(500);
					},
					success : function (response) {
						jQuery('#some_shows').html(response);
						jQuery('#some_shows').fadeIn(500);
					}
				});
			}
		}
	}

	// Join the Team
	jQuery(document).on("click",".wrs_join_the_team a",function(){ 
		var dj = jQuery(this).data('dj');
		jQuery.ajax({
			url : wrsLocal.ajax_url,
			type : 'post',
			data : {
				action : 'tzwrs_msgjoin',
				dj : dj
			},
			beforeSend: function() {
				jQuery('#msg-modal-content .wpcf7').fadeIn().html('<div class="placeholder-item"></div>');
				jQuery('#msg-modal-content h2').fadeIn().html('<div class="placeholder-heading"></div>');
				jQuery('#msg-modal-content .djtomsg').fadeIn().html('<div class="placeholder-image"></div>');
			},
			success : function( response ) {
				var str_array = response.split('||');
				jQuery('#msg-modal-content h2.onAirDJ').html( str_array[0] );
				jQuery('#msg-modal-content .djtomsg').html( str_array[1] );
				jQuery('#msg-modal-content .wpcf7').replaceWith( str_array[2] );
				jQuery( ".djtomsg" ).data( 'footl', str_array[3] );
				const forms = document.querySelectorAll( '#id05 div.wpcf7 > form' );
				forms.forEach( form => wpcf7.init( form ) );
			}
		});
	});

	// Manage schedule slots
	jQuery(document).on("click","td span.action",function(){ 

		var hour_id = jQuery(this).data('hour'),
			week = jQuery(this).data('week'),
			actiondiv = jQuery(this),
			act = jQuery(this).data('act'),
			slotid = jQuery(this).data('slotid'),
			confirmArray = ['wrs_clear_this_slot', 'wrs_deny'],
			index = jQuery.inArray(act, confirmArray);
		if(index == -1) {
			jQuery.ajax({
				url : wrsLocal.ajax_url,
				type : 'post',
				data : {
					action : 'tzwrs_update_cell',
					act: act,
					hour_id : hour_id,
					week : week,
					slotid : slotid
				},
				beforeSend: function() {
					actiondiv.fadeIn().html('<span class="working working-' + hour_id + '"><span class="ltz-1"></span><span class="ltz-2"></span><span class="ltz-3"></span><span class="ltz-4"></span><span class="ltz-5"></span><span class="ltz-6"></span></span>');
				},
				success : function( response ) {
					var n = response.includes("<td");
					if ( n ) {
						actiondiv.closest("td").replaceWith( response );
					}
					else
					{
						jQuery( "#wrs_updateOnair" ).html( response );
						jQuery( "#wrs_updateOnairCrossroads" ).html( response );
					}
				}
			});
		}
		else 
		{
			var name = jQuery( "span." + week + ".nameinslot.h-" + hour_id + " i" ).html()
			if ( name ) { name = name.split('?').join(''); }
			if ( !name ) {
				name = jQuery( "span." + week + ".nameinslot.h-" + hour_id ).html();
			}
			jQuery.ajax({
				url : wrsLocal.ajax_url,
				type : 'post',
				data : {
					action : 'tzwrs_confirm_first',
					act: act,
					hour_id : hour_id,
					name : name,
					week : week,
					slotid : slotid
				},
				beforeSend: function() {
					actiondiv.fadeIn().html('<span class="working working-' + hour_id + '"><span class="ltz-1"></span><span class="ltz-2"></span><span class="ltz-3"></span><span class="ltz-4"></span><span class="ltz-5"></span><span class="ltz-6"></span></span>');
				},
				success : function( response ) {
					actiondiv.closest("td").replaceWith( response );
				}
			});
		}
	
	return false;
	});

	// Manage schedule slots via panel (Add me now)
	jQuery(document).on("click","div.wrs_on_air_wrapper span.action",function(){ 

		var hour_id = jQuery(this).data('hour'),
			week = jQuery(this).data('week'),
			actiondiv = jQuery(this),
			act = jQuery(this).data('act'),
			slotid = jQuery(this).data('slotid'),
			confirmArray = ['wrs_clear_this_slot', 'wrs_deny'],
			index = jQuery.inArray(act, confirmArray);
		if(index == -1) {
			jQuery.ajax({
				url : wrsLocal.ajax_url,
				type : 'post',
				data : {
					action : 'tzwrs_update_cell',
					act: act,
					hour_id : hour_id,
					week : week,
					slotid : slotid
				},
				beforeSend: function() {
					actiondiv.fadeIn().html('<span class="working working-' + hour_id + '"><span class="ltz-1"></span><span class="ltz-2"></span><span class="ltz-3"></span><span class="ltz-4"></span><span class="ltz-5"></span><span class="ltz-6"></span></span>');
				},
				success : function() {
					jQuery.ajax({
						url : wrsLocal.ajax_url,
						type : 'post',
						data : {
							action : 'tzwrs_on_air_update'
						},
						success: function( response ) {
							var str_array = response.split('||'); // wrapper, pic, on_air_text, || panel_header_data || msg || share
							jQuery('#wrs_updateOnair').animate({'opacity': 0}, 500, function () {
								jQuery('#wrs_updateOnair').add(jQuery('.dr-trigger'));
								jQuery('#wrs_updateOnair').html(str_array[0]);
							}).delay(500).animate({'opacity': 1}, 500);
							jQuery('#wrs_updateOnairCrossroads').animate({'opacity': 0}, 500, function () {
								jQuery('#wrs_updateOnairCrossroads').add(jQuery('.dr-trigger'));
								jQuery('#wrs_updateOnairCrossroads').html(str_array[0]);
							}).delay(500).animate({'opacity': 1}, 500);
							jQuery('.dr-trigger').animate({'opacity': 0}, 500, function () {
								jQuery('.dr-label').html(str_array[1]);
								jQuery('.dr-icon-message').html(str_array[2]);
								jQuery('.dr-icon-share').replaceWith(str_array[3]);
							}).delay(500).animate({'opacity': 1}, 500);
						}
					})
				}
			});
		}
		else 
		{
			var name = jQuery( "span." + week + ".nameinslot.h-" + hour_id + " i" ).html()
			if ( name ) { name = name.split('?').join(''); }
			if ( !name ) {
				name = jQuery( "span." + week + ".nameinslot.h-" + hour_id ).html();
			}
			jQuery.ajax({
				url : wrsLocal.ajax_url,
				type : 'post',
				data : {
					action : 'tzwrs_confirm_first',
					act: act,
					hour_id : hour_id,
					name : name,
					week : week,
					slotid : slotid
				},
				beforeSend: function() {
					actiondiv.fadeIn().html('<span class="working working-' + hour_id + '"><span class="ltz-1"></span><span class="ltz-2"></span><span class="ltz-3"></span><span class="ltz-4"></span><span class="ltz-5"></span><span class="ltz-6"></span></span>');
				},
				success : function( response ) {
					actiondiv.closest("td").replaceWith( response );
				}
			});
		}
	
	return false;
	});

	// Generate team drop down
	jQuery( document ).on( 'click', '.schedule_slot_time', function() {
		var week = jQuery(this).data('week'),
			hour_id = jQuery(this).data('hour'),
			pretext = jQuery('.' + week + '.nameinslot.h-' + hour_id).html(),
			prcontent = jQuery('<span />').append(jQuery('.' + week + '.nameinslot.h-' + hour_id).clone()).html(),
			precontent = prcontent + jQuery('<span />').append(jQuery('.' + week + '.nameinslot.h-' + hour_id).next().clone()).html(),
			slotdiv = jQuery('.' + week + '.nameinslot.h-' + hour_id);
		jQuery.ajax({
			url : wrsLocal.ajax_url,
			type : 'post',
			data : {
				action : 'tzwrs_dj_dropdown',
				hour_id : hour_id,
				pretext : pretext,
				precontent : precontent,
				week : week
			},
			beforeSend: function() {
				slotdiv.fadeIn().html('<span class="working working-' + hour_id + '"><span class="ltz-1"></span><span class="ltz-2"></span><span class="ltz-3"></span><span class="ltz-4"></span><span class="ltz-5"></span><span class="ltz-6"></span></span>');
			},
			success : function( response ) {
				jQuery('.working-' + hour_id).fadeOut();
				slotdiv.html( response );
			}
		});

		return false;
	})

	// Select dj for slot
	jQuery('.wrstable').on( 'change', 'form .djs', function() {
		var week = jQuery(this).parent().data('week'),
			selectedDj = jQuery(this).children("option:selected").val(),
			hour_id = jQuery(this).data('hour'),
			precontent = jQuery('.' + week + '.precontent_' + hour_id).html(),
			actiondiv = jQuery('.' + week + '.caseSpace-' + hour_id),
			slotdiv = jQuery('.' + week + '.nameinslot.h-' + hour_id),
			slotid = jQuery(this).data('slotid');

		jQuery.ajax({
			url : wrsLocal.ajax_url,
			type : 'post',
			data : {
				action : 'tzwrs_updateSlot',
				selecteddj : selectedDj,
				hour_id : hour_id,
				precontent : precontent,
				week : week,
				slotid : slotid
			},
			beforeSend: function() {
				slotdiv.fadeIn().html('<span class="working working-' + hour_id + '"><span class="ltz-1"></span><span class="ltz-2"></span><span class="ltz-3"></span><span class="ltz-4"></span><span class="ltz-5"></span><span class="ltz-6"></span></span>');
			},
			success : function( response ) {
				actiondiv.replaceWith( response );
			}
		});
		return false;

	})

	// Refresh on air panel
	jQuery(document).ready(function(){

		window.setInterval(update_time, 60000);
		function update_time() {
			if ( document.hasFocus() ) {
				jQuery.ajax({
					url : wrsLocal.ajax_url,
					type : 'post',
					data : {
						action : 'tzwrs_on_air_update'
					},
					success: function( response ) {
						var str_array = response.split('||'); // wrapper, pic, on_air_text, || panel_header_data || msg || share
						jQuery('#wrs_updateOnair').add(jQuery('.dr-trigger')).fadeOut(500);
						jQuery('#wrs_updateOnair').html(str_array[0]);
						jQuery('#wrs_updateOnairCrossroads').add(jQuery('.dr-trigger')).fadeOut(500);
						jQuery('#wrs_updateOnairCrossroads').html(str_array[0]);
						jQuery('.dr-label').html(str_array[1]);
						jQuery('.dr-icon-message').html(str_array[2]);
						jQuery('.dr-icon-share').replaceWith(str_array[3]);
						jQuery('#wrs_updateOnair').add(jQuery('.dr-trigger')).fadeIn(500);
						jQuery('#wrs_updateOnairCrossroads').add(jQuery('.dr-trigger')).fadeIn(500);
					}
				})
			}
		}
	});

	window.setInterval(update_time, 60000);
	function update_time() {
		var actiondiv = jQuery('#wp-admin-bar-schedule a span'),str_array;
		jQuery.ajax({
			url : wrsLocal.ajax_url,
			type : 'post',
			data : {
				action : 'tzwrs_schedule_alert'
			},
			success: function( response ) {
				str_array = response.split('||');
				actiondiv.html(str_array[0]);
			}
		})
	}

	// Generate team shows drop down
	jQuery( document ).on( 'click', '.selecta .picka', function() {
		var week = jQuery(this).data('week'),
			hour_id = jQuery(this).data('hour'),
			pretext = jQuery('.' + week + '.nameinslot.h-' + hour_id).html(),
			prcontent = jQuery('<span />').append(jQuery('.' + week + '.nameinslot.h-' + hour_id).clone()).html(),
			precontent = prcontent + jQuery('<span />').append(jQuery('.' + week + '.nameinslot.h-' + hour_id).next().clone()).html(),
			slotdiv = jQuery('.' + week + '.nameinslot.h-' + hour_id);
		jQuery.ajax({
			url : wrsLocal.ajax_url,
			type : 'post',
			data : {
				action : 'tzwrs_dj_dropdown',
				hour_id : hour_id,
				pretext : pretext,
				precontent : precontent,
				week : week
			},
			beforeSend: function() {
				slotdiv.fadeIn().html('<span class="working working-' + hour_id + '"><span class="ltz-1"></span><span class="ltz-2"></span><span class="ltz-3"></span><span class="ltz-4"></span><span class="ltz-5"></span><span class="ltz-6"></span></span>');
			},
			success : function( response ) {
				jQuery('.working-' + hour_id).fadeOut();
				slotdiv.html( response );
			}
		});

		return false;

	})

	// Select dj's shows
	jQuery( document ).on( 'change', 'form #selector_shows', function() {
		var week = jQuery(this).parent().data('week'),
			selectedDj = jQuery(this).children("option:selected").val(),
			actiondiv = jQuery('.load-state');

		jQuery.ajax({
			url : wrsLocal.ajax_url,
			type : 'post',
			data : {
				action : 'tzwrs_updateShows',
				selecteddj : selectedDj,
				week : week
			},
			beforeSend: function() {
				//actiondiv.slideUp('slow');
				actiondiv.fadeIn().html('<span class="working working-shows"><span class="ltz-1"></span><span class="ltz-2"></span><span class="ltz-3"></span><span class="ltz-4"></span><span class="ltz-5"></span><span class="ltz-6"></span></span>');
			},
			success : function( response ) {
				actiondiv.hide();
				actiondiv.fadeOut();
				actiondiv.html( response );
				actiondiv.slideDown('slow');
			}
		});
		return false;
	})

});
