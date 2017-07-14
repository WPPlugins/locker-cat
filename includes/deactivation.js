/* jshint asi: true */

jQuery(document).ready(function($){
	
	var $deactivateButton = $('#the-list tr.active').filter( function() { return $(this).data('plugin') === 'locker-cat/locker-cat.php' } ).find('.deactivate a')
		
	$deactivateButton.click(function(e){
		e.preventDefault()
		$deactivateButton.unbind('click')
		$('body').append(fca_slc.html)
		fca_slc_uninstall_button_handlers( $deactivateButton.attr('href') )
	})
}) 

function fca_slc_uninstall_button_handlers( url ) {
	var $ = jQuery
	$('#fca-slc-deactivate-skip').click(function(){
		$(this).prop( 'disabled', true )
		window.location.href = url
	})
	$('#fca-slc-deactivate-send').click(function(){
		$(this).prop( 'disabled', true )
		$(this).html('...')
		$('#fca-slc-deactivate-skip').hide()
		$.ajax({
			url: fca_slc.ajaxurl,
			type: 'POST',
			data: {
				"action": "fca_slc_uninstall",
				"nonce": fca_slc.nonce,
				"msg": $('#fca-slc-deactivate-textarea').val()
			}
		}).done( function( response ) {
			console.log ( response )
			window.location.href = url			
		})	
	})
	
}