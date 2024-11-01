(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

jQuery(document).ready(function($){
	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: '000000',
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){},
		// a callback to fire when the input is emptied or an invalid color
		clear: function() {},
		// hide the color picker controls on load
		hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
    jQuery('.text-color-field').wpColorPicker(myOptions);
	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: '45a6ad',
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){},
		// a callback to fire when the input is emptied or an invalid color
		clear: function() {},
		// hide the color picker controls on load
		hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
    jQuery('.accent-color-field').wpColorPicker(myOptions);
	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: '6D6D6D',
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){},
		// a callback to fire when the input is emptied or an invalid color
		clear: function() {},
		// hide the color picker controls on load
		hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
    jQuery('.secondary-color-field').wpColorPicker(myOptions);
	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: 'DCD7CA',
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){},
		// a callback to fire when the input is emptied or an invalid color
		clear: function() {},
		// hide the color picker controls on load
		hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
    jQuery('.border-color-field').wpColorPicker(myOptions);
	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: 'F5EFE0',
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){},
		// a callback to fire when the input is emptied or an invalid color
		clear: function() {},
		// hide the color picker controls on load
		hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
    jQuery('.background-color-field').wpColorPicker(myOptions);
	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: 'FFFFFF',
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){},
		// a callback to fire when the input is emptied or an invalid color
		clear: function() {},
		// hide the color picker controls on load
		hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
    jQuery('.header-footer-background-color-field').wpColorPicker(myOptions);
	
	jQuery("#wrs_wp_picker_reset").click(function () {
		$('#tzwrs_wrs_text_color').wpColorPicker('color', '#000000');
		$('#tzwrs_wrs_accent_color').wpColorPicker('color', '#CD2653');
		$('#tzwrs_wrs_secondary_color').wpColorPicker('color', '#C4C4C4');
		$('#tzwrs_wrs_border_color').wpColorPicker('color', '#DCD7CA');
		$('#tzwrs_wrs_background_color').wpColorPicker('color', '#F5EFE0');
		$('#tzwrs_wrs_header_footer_background_color').wpColorPicker('color', '#FFFFFF');
		$('.settings_page_tz-weekly-radio-schedule form span.colour_head').css('color', '#CD2653');
	});
	jQuery("#wrs_wp_picker_2017").click(function () {
		$('#tzwrs_wrs_text_color').wpColorPicker('color', '#000000');
		$('#tzwrs_wrs_accent_color').wpColorPicker('color', '#767676');
		$('#tzwrs_wrs_secondary_color').wpColorPicker('color', '#a8a8a8');
		$('#tzwrs_wrs_border_color').wpColorPicker('color', '#DCD7CA');
		$('#tzwrs_wrs_background_color').wpColorPicker('color', '#F5EFE0');
		$('#tzwrs_wrs_header_footer_background_color').wpColorPicker('color', '#FFFFFF');
		$('.settings_page_tz-weekly-radio-schedule form span.colour_head').css('color', '#767676');
	});
	jQuery("#wrs_wp_picker_2019").click(function () {
		$('#tzwrs_wrs_text_color').wpColorPicker('color', '#000000');
		$('#tzwrs_wrs_accent_color').wpColorPicker('color', '#0073aa');
		$('#tzwrs_wrs_secondary_color').wpColorPicker('color', '#C4C4C4');
		$('#tzwrs_wrs_border_color').wpColorPicker('color', '#DCD7CA');
		$('#tzwrs_wrs_background_color').wpColorPicker('color', '#F5EFE0');
		$('#tzwrs_wrs_header_footer_background_color').wpColorPicker('color', '#FFFFFF');
		$('.settings_page_tz-weekly-radio-schedule form span.colour_head').css('color', '#0073aa');
	});
	jQuery("#wrs_wp_picker_2021").click(function () {
		$('#tzwrs_wrs_text_color').wpColorPicker('color', '#000000');
		$('#tzwrs_wrs_accent_color').wpColorPicker('color', '#45a6ad');
		$('#tzwrs_wrs_secondary_color').wpColorPicker('color', '#c6c6c6');
		$('#tzwrs_wrs_border_color').wpColorPicker('color', '#b1e2dd');
		$('#tzwrs_wrs_background_color').wpColorPicker('color', '#d1e4dd');
		$('#tzwrs_wrs_header_footer_background_color').wpColorPicker('color', '#FFFFFF');
		$('.settings_page_tz-weekly-radio-schedule form span.colour_head').css('color', '#45a6ad');
	});
	jQuery("#wrs_wp_picker_rs").click(function () {
		$('#tzwrs_wrs_text_color').wpColorPicker('color', '#d3d3d3');
		$('#tzwrs_wrs_accent_color').wpColorPicker('color', '#edd83b');
		$('#tzwrs_wrs_secondary_color').wpColorPicker('color', '#5b5a00');
		$('#tzwrs_wrs_border_color').wpColorPicker('color', '#3f4233');
		$('#tzwrs_wrs_background_color').wpColorPicker('color', '#6a6d64');
		$('#tzwrs_wrs_header_footer_background_color').wpColorPicker('color', '#000000');
		$('.settings_page_tz-weekly-radio-schedule form span.colour_head').css('color', '#edd83b');
	});
});

jQuery(function () {
	jQuery("#tzwrs_wrs_insert_demo_data_confirm.tzwrs_certain").hide();
	jQuery("label[for='tzwrs_wrs_insert_demo_data_confirm.tzwrs_certain']").hide();
	jQuery("#tzwrs_wrs_insert_demo_data").click(function () {
		if (jQuery(this).is(":checked")) {
			jQuery("#tzwrs_wrs_insert_demo_data_confirm.tzwrs_certain").show();
			jQuery("label[for='tzwrs_wrs_insert_demo_data_confirm.tzwrs_certain']").show();
		} else {
			jQuery("#tzwrs_wrs_insert_demo_data_confirm.tzwrs_certain").hide();
			jQuery("label[for='tzwrs_wrs_insert_demo_data_confirm.tzwrs_certain']").hide();
		}
	});
});

function elementSupportsAttribute(element, attribute) {
  var test = document.createElement(element);
  if (attribute in test) {
    return true;
  } else {
    return false;
  }
}

if (!elementSupportsAttribute('textarea', 'placeholder')) {
  // Fallback for browsers that don't support HTML5 placeholder attribute
  jQuery("#user_acast")
    .data("originalText", jQuery("#user_acast").text())
    .css("color", "#999")
    .focus(function() {
        var $el = $(this);
        if (this.value == $el.data("originalText")) {
          this.value = "";
        }
    })
    .blur(function() {
      if (this.value == "") {
          this.value = jQuery(this).data("originalText");
      }
    });
} else {
  // Browser does support HTML5 placeholder attribute, so use it.
  jQuery("#user_acast")
    .attr("placeholder", jQuery("#user_acast").text())
    .text("");
}

