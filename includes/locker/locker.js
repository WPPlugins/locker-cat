/* jshint asi: true */
jQuery( document ).ready( function($) {
	var loop = false
	var cookieDuration = 90 //days
	
	//CHECK FOR COOKIE RIGHT AWAY AND SHOW
	if (  window.location.search.indexOf( 'contentlocker=' ) === -1 ) {
		//DONT DO THIS FOR PREVIEWS
		var cookie = get_cookie( 'fca_slc_unlocked' )
		
		for ( var i = 0; i < cookie.length; i++) {
			after_share( $( '.fca-lockercat-wrapper' ).filter('#' + cookie[i] ) )
		}
	}
	
	$( '.fca-lockercat-share-link' ).click( function(event) {
		
		event.preventDefault()
		var url = $(this).prop('href')				
		window.open(url, '_blank', 'resizable=yes,scrollbars=yes,titlebar=yes, width=560, height=443, top=100, left=50')
		
		if ( loop === false ) {
			loop = setInterval(focusCheck, 200, $(this).closest('.fca-lockercat-wrapper') )
		}
		
	})
	
	//SHOW CONTENT, SET COOKIE.. PROBABLY OTHER STUFF LATER
	function after_share( $element ) {
		$element.hide()
		$element.siblings('.fca-lockercat-content').show(350)
		//DONT SET COOKIE FOR PREVIEW
		if ( window.location.search.indexOf( 'contentlocker=' ) === -1 ) {
			add_to_shared( parseInt( $element.attr('id') ) )
		}
	}
	
	function focusCheck( $element ) {
		if ( document.hasFocus() ) { 
			clearInterval( loop )
			after_share( $element )
		}
	}
	
	function add_to_shared ( id ) {
		var cookie = get_cookie( 'fca_slc_unlocked' )
		
		if ( cookie.indexOf( id ) === -1 )  {
			cookie.push(id) 
			set_cookie( 'fca_slc_unlocked', JSON.stringify( cookie ) )
		}
		
	}
	
	function set_cookie( name, value ) {
		if ( cookieDuration === 0 ) {
			document.cookie = name + "=" + value + ";" + "path=/;"
		} else {
			var d = new Date()
			d.setTime( d.getTime() + ( cookieDuration*24*60*60*1000 ) )
			document.cookie = name + "=" + value + ";" + "path=/;" + "expires=" + d.toUTCString()
		}
	}
	
	function get_cookie( name ) {
		var value = "; " + document.cookie
		var parts = value.split( "; " + name + "=" )
		
		if ( parts.length === 2 ) {
			return JSON.parse( parts.pop().split(";").shift() )
		} else {
			return []
		}
	}
		
})