/*jslint regexp: true, confusion: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */

;(function($, window, document, undefined) {
	window.wp = window.wp || {};
	window.wp.gdatt = window.wp.gdatt || {};

	window.wp.gdatt.attachments = {
		init: function() {
		  
			$('#bbp-attachment-toggle').click(function () {
				$('#bbp-attachment-upload').slideToggle();
				$(this).toggleClass('bbp-attachment-toggle-active');
				$('#bbp-attachment-toggle-wrap, .fep-form-field-fep_upload').toggleClass('palebg');
			});            
			$( document ).on( 'click', '.d4p-attachment-remove', function( e ) {
				e.preventDefault();
				$(this).closest('.bbp-attachment-field-input').replaceWith($(".bbp-attachment-field-input").val('').clone(true));
				
			});

			$("form#new-post").attr("enctype", "multipart/form-data");

			$(document).on("click", ".d4p-bba-actions a", function(e){
				return confirm(bbpatt_str.are_you_sure);
			});

			$(document).on("click", ".d4p-attachment-addfile", function(e){
				e.preventDefault();

				var now = $(".bbp-attachments-form input[type=file]").length,
					max = parseInt(bbpatt_str.max_files);

				if (now < max) {
					$(this).before('<br><input type="file" size="40" name="d4p_attachment[]" class="bbp-attachment-field-input"><a href="#" class="d4p-attachment-remove">Remove</a><br>');
				}

				if (now + 1 >= max) {
					$(this).remove();
				}
			});
		}
	};

	$(document).ready(function() {
		wp.gdatt.attachments.init();
	});
})(jQuery, window, document);
