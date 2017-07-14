<?php


function fca_slc_locker_enqueue() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'fca_slc_locker_css', FCA_SLC_PLUGINS_URL . '/includes/locker/locker.min.css', array(), FCA_SLC_PLUGIN_VER );
	wp_enqueue_script( 'fca_slc_locker_js', FCA_SLC_PLUGINS_URL . '/includes/locker/locker.min.js', array( 'jquery'), FCA_SLC_PLUGIN_VER, true );	
}

function fca_slc_do_shortcode( $atts, $content = null ) {
	fca_slc_locker_enqueue();
	$post_id = get_option('fca_lockercat_active_post');
	$meta = get_post_meta( $post_id, 'fca_slc', true );
	
	$fb_share = empty( $meta['unlock_fbshare'] ) ? false : true;
	$tweet = empty( $meta['unlock_twitter'] ) ? false : true;
	//sanity check?
	if ( $fb_share OR $tweet ) {
		
		$attribution = '<!-- SVG lock icon by Twitter Emoji for Everyone http://twitter.github.io/twemoji provided under MIT license -->';
		$svg = '<?xml version="1.0" encoding="UTF-8" standalone="no"?><svg width="25" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.5 47.5" style="enable-background:new 0 0 47.5 47.5;" xml:space="preserve" version="1.1" id="svg2"><metadata id="metadata8"><rdf:RDF><cc:Work rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"/></cc:Work></rdf:RDF></metadata><defs id="defs6"><clipPath id="clipPath16" clipPathUnits="userSpaceOnUse"><path id="path18" d="M 0,38 38,38 38,0 0,0 0,38 Z"/></clipPath></defs><g transform="matrix(1.25,0,0,-1.25,0,47.5)" id="g10"><g id="g12"><g clip-path="url(#clipPath16)" id="g14"><g transform="translate(14,32.9995)" id="g20"><path id="path22" style="fill:#bcbec0;fill-opacity:1;fill-rule:nonzero;stroke:none" d="m 0,0 c -5.523,0 -10,-4.477 -10,-10 l 0,-10 4,0 0,10 c 0,3.313 2.687,6 6,6 3.313,0 6,-2.687 6,-6 l 0,-10 4,0 0,10 C 10,-4.477 5.523,0 0,0"/></g><g transform="translate(26,5)" id="g24"><path id="path26" style="fill:#ffac33;fill-opacity:1;fill-rule:nonzero;stroke:none" d="m 0,0 c 0,-2.209 -1.791,-4 -4,-4 l -16,0 c -2.209,0 -4,1.791 -4,4 l 0,10 c 0,2.209 1.791,4 4,4 l 16,0 c 2.209,0 4,-1.791 4,-4 L 0,0 Z"/></g><g transform="translate(27,30.9995)" id="g28"><path id="path30" style="fill:#c1694f;fill-opacity:1;fill-rule:nonzero;stroke:none" d="M 0,0 C -1.104,0 -2,0.896 -2,2 -2,3.104 -1.104,4 0,4 1.104,4 2,3.104 2,2 2,0.896 1.104,0 0,0 m 0,6 c -4.971,0 -9,-4.029 -9,-9 0,-4.971 4.029,-9 9,-9 4.971,0 9,4.029 9,9 0,4.971 -4.029,9 -9,9"/></g><g transform="translate(30,15)" id="g32"><path id="path34" style="fill:#c1694f;fill-opacity:1;fill-rule:nonzero;stroke:none" d="M 0,0 0,7 C 0,8.104 -0.896,9 -2,9 -2.748,9 -3.393,8.584 -3.735,7.976 -5.004,7.856 -6,6.8 -6,5.5 l 0,-17 c 0,-1.381 1.119,-2.5 2.5,-2.5 1.213,0 2.223,0.864 2.45,2.01 0.018,-10e-4 0.032,-0.01 0.05,-0.01 0.553,0 1,0.447 1,1 l 0,1 c 0,0.553 -0.447,1 -1,1 l 0,1 c 0.553,0 1,0.447 1,1 l 0,2 c 0,0.553 -0.447,1 -1,1 l 0,2.277 C -0.404,-1.376 0,-0.738 0,0"/></g></g></g></g></svg>';
		ob_start(); ?>
			<div class="fca-lockercat-wrapper" id="<?php echo $post_id ?>">
				<div class="fca-lockercat-headline"><?php echo html_entity_decode ( $meta['headline'] )?><span class="fca-lockercat-icon"><?php echo $attribution; echo $svg ?></span></div>
				<div class="fca-lockercat-copy"><?php echo html_entity_decode ( $meta['copy'] )?></div>
				<?php
					echo fca_slc_share_div( $meta );
				?>
				
			</div>
			<div class="fca-lockercat-content" style="display:none">
				<?php echo do_shortcode( $content ) ?>
			</div>
		
		<?php
		return ob_get_clean();
	} else {
		//do nothing?
		return '<p><strong>' . __('Locker Cat: Turn on one or more social media share options to enable content locker', 'locker-cat') . '</strong><p>';
		
	}
}
add_shortcode( 'lockercat', 'fca_slc_do_shortcode' );

function fca_slc_share_div( $meta ) {

	$fb_share = empty( $meta['unlock_fbshare'] ) ? false : true;
	$tweet = empty( $meta['unlock_twitter'] ) ? false : true;
	$permalink = get_permalink();
	
	$fb_svg = '<svg width="18" height="18" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 29"><path d="M26.4 0H2.6C1.714 0 0 1.715 0 2.6v23.8c0 .884 1.715 2.6 2.6 2.6h12.393V17.988h-3.996v-3.98h3.997v-3.062c0-3.746 2.835-5.97 6.177-5.97 1.6 0 2.444.173 2.845.226v3.792H21.18c-1.817 0-2.156.9-2.156 2.168v2.847h5.045l-.66 3.978h-4.386V29H26.4c.884 0 2.6-1.716 2.6-2.6V2.6c0-.885-1.716-2.6-2.6-2.6z"/></svg>';
	$twitter_svg = '<svg width="18" height="18" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62a15.093 15.093 0 0 1-8.86-2.32c2.702.18 5.375-.648 7.507-2.32a5.417 5.417 0 0 1-4.49-3.64c.802.13 1.62.077 2.4-.154a5.416 5.416 0 0 1-4.412-5.11 5.43 5.43 0 0 0 2.168.387A5.416 5.416 0 0 1 2.89 4.498a15.09 15.09 0 0 0 10.913 5.573 5.185 5.185 0 0 1 3.434-6.48 5.18 5.18 0 0 1 5.546 1.682 9.076 9.076 0 0 0 3.33-1.317 5.038 5.038 0 0 1-2.4 2.942 9.068 9.068 0 0 0 3.02-.85 5.05 5.05 0 0 1-2.48 2.71z"/></svg>';	
	
	$style = ( $fb_share XOR $tweet ) ? 'wide' : '';
	
	$html = "<div class='fca-lockercat-share'>";
	
	if ( $fb_share ) {
		$html .= "<a class='fca-lockercat-share-link $style' id='fca-lockercat-share-link-facebook' href='https://www.facebook.com/sharer/sharer.php?u=$permalink' rel='nofollow' target='_blank'>";
		$html .= "$fb_svg<span class='fca-lockercat-share-name'>" . __('Share', 'quiz-cat') . "</span>";
		$html .= "</a>";
	}
	if ( $tweet ) {
		$html .= "<a class='fca-lockercat-share-link $style' id='fca-lockercat-share-link-twitter' href='https://twitter.com/intent/tweet?url=$permalink&text=' target='_blank'>";
		$html .= "$twitter_svg<span class='fca-lockercat-share-name'>" . __('Tweet', 'quiz-cat') . "</span>";
		$html .= "</a>";		
	}
	
	$html .= '</div>';
	
	return $html;	
}
