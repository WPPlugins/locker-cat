<?php
	
////////////////////////////
// EDITOR PAGE 
////////////////////////////

function fca_slc_editor_page() {
	
	add_menu_page(
        __( 'Social Locker', 'locker-cat' ),
        __( 'Social Locker', 'locker-cat' ),
        'manage_options',
        'locker-cat',
        'fca_slc_render_settings_meta_box',
        FCA_SLC_PLUGINS_URL . '/assets/icon.png',
        118
    );
	
}
add_action( 'admin_menu', 'fca_slc_editor_page' );


//ENQUEUE ANY SCRIPTS OR CSS FOR OUR ADMIN PAGE EDITOR
function fca_slc_admin_cpt_script( $post_id ) {

		wp_enqueue_style('dashicons');
		wp_enqueue_script('jquery');
		
		wp_enqueue_script('fca_slc_wysi_tidy', FCA_SLC_PLUGINS_URL . '/includes/wysi/tidy.min.js', array(), FCA_SLC_PLUGIN_VER, true );		
		wp_enqueue_script('fca_slc_wysi_js_main', FCA_SLC_PLUGINS_URL . '/includes/wysi/wysihtml.min.js', array(), FCA_SLC_PLUGIN_VER, true );		
		wp_enqueue_script('fca_slc_wysi_js_toolbar', FCA_SLC_PLUGINS_URL . '/includes/wysi/wysihtml-toolbar.min.js', array(), FCA_SLC_PLUGIN_VER, true );		
		wp_enqueue_style('fca_slc_wysi_css', FCA_SLC_PLUGINS_URL . '/includes/wysi/wysi.min.css', array(), FCA_SLC_PLUGIN_VER );
		wp_enqueue_script('fca_slc_wysi_js', FCA_SLC_PLUGINS_URL . '/includes/wysi/wysi.min.js', array( 'jquery', 'fca_slc_wysi_tidy', 'fca_slc_wysi_js_main', 'fca_slc_wysi_js_toolbar' ), FCA_SLC_PLUGIN_VER, true );		
		
		wp_enqueue_script('fca_slc_editor_js', FCA_SLC_PLUGINS_URL . '/includes/editor/editor.min.js', array( 'jquery' ), FCA_SLC_PLUGIN_VER, true );		
		wp_enqueue_style('fca_slc_editor_stylesheet', FCA_SLC_PLUGINS_URL . '/includes/editor/editor.min.css', array(), FCA_SLC_PLUGIN_VER );
		
		$admin_data = array (
			'ajaxurl' => admin_url ( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'fca_slc_admin_nonce' ),
			'post_id' => $post_id,
			'stylesheet' => FCA_SLC_PLUGINS_URL . '/includes/wysi/wysi.min.css',
		);
		
		wp_localize_script( 'fca_slc_editor_js', 'fcaSlcData', $admin_data );
		wp_localize_script( 'fca_slc_wysi_js', 'fcaSlcData', $admin_data );
	

}
//add_action( 'admin_enqueue_scripts', 'fca_slc_admin_cpt_script', 10, 1 );  

//ADD META BOXES TO EDIT CPT PAGE
function fca_slc_add_custom_meta_boxes( $post ) {
	
	add_meta_box( 
		'fca_slc_locker_settings_meta_box',
		__( 'Configuration', 'locker-cat' ),
		'fca_slc_render_settings_meta_box',
		null,
		'normal',
		'high'
	);	

}
add_action( 'add_meta_boxes_contentlocker', 'fca_slc_add_custom_meta_boxes' );

function fca_slc_create_post() {
	$meta = array(
		'headline' => __( 'Share to Unlock Content', 'locker-cat' ),
		'copy' => __( 'Unlock this exclusive content by using one of the sharing buttons below.', 'locker-cat' ),
		'unlock_twitter' => 'on',
		'unlock_fbshare' =>'on',
	);
		
	$post = array(
		'post_title'   => '',
		'post_content' => '[lockercat]' . __('Add locked content here', 'locker-cat' ) . '[/lockercat]',
		'post_type'		=> 'contentlocker',
		'post_status'  => 'publish',
		'post_author'  => get_current_user_id(),
		'meta_input'   => array(
			'fca_slc' => $meta,
		),
	);
	
	return wp_insert_post( $post );		
}
function fca_slc_render_settings_meta_box () {
	
	$preview = '';
	if ( array_key_exists( 'fca_slc_post_id', $_POST ) ) {
		$post_id = intVal ( $_POST['fca_slc_post_id'] );
		fca_slc_save_post ( $post_id );
				
		if ( array_key_exists( 'fca_slc_preview_button', $_POST ) ) {
			$preview = get_permalink( $post_id );
		}
	}
	
	$post_id = get_option('fca_lockercat_active_post');
	
	//DEFAULTS
	if ( empty ( $post_id ) ) {
		$post_id = fca_slc_create_post();
	}
	$meta = get_post_meta( $post_id, 'fca_slc', true );

	$settings = array(
		'headline',
		'copy',
		'unlock_twitter',
		'unlock_fbshare',
	);
	
	forEach ( $settings as $s ) {
		$meta[$s] = empty( $meta[$s] ) ? '' : sanitize_text_field( $meta[$s] );
	}
	
	//ENQUEUES
	fca_slc_admin_cpt_script( $post_id );
	
	ob_start(); ?>
	<form id='poststuff' style='display:none' action='' method='post'>
		<h1><?php _e('Social Locker by Locker Cat', 'locker-cat') ?></h1>
		<input type='hidden' name='fca_slc_post_id' id='fca_slc_post_id' value='<?php echo $post_id ?>'>
		<input type='hidden' id='fca_preview_url' value='<?php echo $preview ?>'>

		<table class='fca-slc-setting-table'>
			<tr>
				<th class='fca-slc-setting-table-heading'><?php _e('General', 'locker-cat') ?></th>
			</tr>
			<tr>
				<th><?php _e('Headline', 'locker-cat') ?></th>
				<td><?php echo fca_slc_input( 'headline', __( 'Share to Unlock Content', 'locker-cat' ), $meta['headline'] ) ?></td>
			</tr>
			<tr>
				<th><?php _e('Copy', 'locker-cat') ?></th>
				<td><?php echo fca_slc_input( 'copy', __( 'Unlock this exclusive content by using one of the sharing buttons below.', 'locker-cat' ), $meta['copy'], 'wysi' ) ?></td>
			</tr>
			
			<tr>
				<th class='fca-slc-setting-table-heading'><?php _e('Social Sharing', 'locker-cat') ?></th>
			</tr>
			<tr>
				<th><?php _e('Tweet', 'locker-cat') ?></th>
				<td><?php echo fca_slc_input( 'unlock_twitter', '', $meta['unlock_twitter'], 'checkbox' ) ?></td>
			</tr>
			<tr>
				<th><?php _e('Facebook Share', 'locker-cat') ?></th>
				<td><?php echo fca_slc_input( 'unlock_fbshare', '', $meta['unlock_fbshare'], 'checkbox' ) ?></td>
			</tr>
			
			<tr>
				<th class='fca-slc-setting-table-heading'><?php _e('Publication', 'locker-cat') ?></th>
			</tr>
			<tr>
				<th><?php _e('Shortcode', 'locker-cat') ?></th>
				<td>
					<input type='text' readonly onClick='this.select()' value='[lockercat]<?php _e('Add locked content here', 'locker-cat' ) ?>[/lockercat]' >
					<p class='fca-slc-setting-table-info'><?php _e( 'Add above shortcode to the post or page that should contain your locked content.  Wrap the locked content between the [lockercat] shortcodes.', 'locker-cat' ) ?></p>
				</td>
			</tr>
			
		</table>
		
		<input type="submit" class="button-primary" value="<?php _e('Save', 'locker-cat') ?>">
		<input type="submit" class="button-secondary" formtarget="_blank" name="fca_slc_preview_button" value="<?php _e('Save & Preview', 'locker-cat') ?>">

	
	</form>
	
	<?php
	echo ob_get_clean();
}


//CUSTOM SAVE HOOK
function fca_slc_save_post ( $post_id ) {
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}
	
	//ONLY DO OUR STUFF IF ITS A REAL SAVE, NOT A NEW IMPORTED ONE
	if ( array_key_exists( 'fca_slc_post_id', $_POST ) ) {
		$meta = fca_slc_sanitize_post_save( $_POST['fca_slc'] );
		update_post_meta( $post_id, 'fca_slc', $meta );
		update_option( 'fca_lockercat_active_post', $post_id );
	}	
}

function fca_slc_sanitize_post_save ( $post ) {
	$data = array(
		'headline' => '',
		'copy' => '',
		'unlock_twitter' => '',
		'unlock_fbshare' => '',
	);
	
	$data['headline'] = 		empty( $post['headline'] )			? '' : esc_textarea( $post['headline'] );
	$data['copy'] = 			empty( $post['copy'] )				? '' : esc_textarea( $post['copy'] );
	$data['unlock_twitter'] = 	empty( $post['unlock_twitter'] )	? '' : 'on';
	$data['unlock_fbshare'] = 	empty( $post['unlock_fbshare'] )	? '' : 'on';
	
	return $data;

}


//ADD NAGS / WARNINGS (UNUSED)
function fca_slc_admin_warning() {
	
	$current_screen = get_current_screen();
	
	if ( $current_screen->id  === 'contentlocker' ) {
		global $post;
		$m = get_post_meta( $post->ID, 'fca_slc', true );

		if ( empty( $m['activecampaign_list'] ) && empty( $m['aweber_list'] ) && empty( $m['getresponse_list'] ) && empty( $m['mailchimp_list'] ) && empty( $m['zapier_url'] ) ) {			
			
			echo '<div id="fca-slc-no-provider-nag" class="notice notice-error is-dismissible" style="padding-bottom: 8px;">';
				echo '<img style="float:left" width="120" height="120" src="' . FCA_SLC_PLUGINS_URL . '/assets/optincat.png' . '">';
				echo '<p><strong>' . __( "Warning: Social Locker by Locker Cat Not Connected", 'locker-cat' ) . '</strong></p>';
				echo '<p>' . __( "In order to start building your email list, Social Locker by Locker Cat needs to connect to your email marketing service provider.", 'locker-cat' ) . '</p>';
				echo '<p>' . __( "Click the 'Fix This' button below to enter your provider details and choose a mailing list.", 'locker-cat' ) . '</p>';
				
				echo "<button type='button' class='button button-primary' id='fca-slc-no-list-cta'>" . __( 'Fix This', 'locker-cat') . "</a> ";
				echo '<br style="clear:both">';
			echo '</div>';
		}
	}
}
//add_action( 'admin_notices', 'fca_slc_admin_warning' );