/* jshint asi: true */
jQuery(document).ready(function($){
	if ( $( '#fca_preview_url').val() !== '' ) {
		window.location.href = $( '#fca_preview_url').val()
	} else {
		//SHOW OUR MAIN DIV
		$( '#poststuff').show()		
	}
})