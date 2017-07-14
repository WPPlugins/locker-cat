<?php
/*
	Plugin Name: Social Locker by Locker Cat - Free
	Plugin URI: https://fatcatapps.com/locker-cat
	Description: Provides an easy way to create Social Lockers
	Text Domain: locker-cat
	Domain Path: /languages
	Author: Fatcat Apps
	Author URI: https://fatcatapps.com/
	License: GPLv2
	Version: 1.0.0
*/

// BASIC SECURITY
defined( 'ABSPATH' ) or die( 'Unauthorized Access!' );



if ( !defined('FCA_SLC_PLUGIN_DIR') ) {
	
	//DEFINE SOME USEFUL CONSTANTS
	define( 'FCA_SLC_PLUGIN_VER', '1.0.0' );
	define( 'FCA_SLC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'FCA_SLC_PLUGINS_URL', plugins_url( '', __FILE__ ) );
	define( 'FCA_SLC_PLUGIN_FILE', __FILE__ );
	define( 'FCA_SLC_PLUGIN_PACKAGE', 'Free' ); //DONT CHANGE THIS, IT WONT ADD FEATURES, ONLY BREAKS UPDATER AND LICENSE
	
	include_once( FCA_SLC_PLUGIN_DIR . '/includes/api.php' );
	if ( file_exists ( FCA_SLC_PLUGIN_DIR . '/includes/upgrade.php' ) ) {
		//include_once( FCA_SLC_PLUGIN_DIR . '/includes/upgrade.php' );
	}
	
	//LOAD MODULES
	include_once( FCA_SLC_PLUGIN_DIR . '/includes/editor/editor.php' );
	if ( file_exists ( FCA_SLC_PLUGIN_DIR . '/includes/editor/sidebar.php' ) ) {
		//include_once( FCA_SLC_PLUGIN_DIR . '/includes/editor/sidebar.php' );
	}
	include_once( FCA_SLC_PLUGIN_DIR . '/includes/locker/locker.php' );
	
	if ( file_exists ( FCA_SLC_PLUGIN_DIR . '/includes/splash/splash.php' ) ) {
		include_once( FCA_SLC_PLUGIN_DIR . '/includes/splash/splash.php' );
	}
	include_once( FCA_SLC_PLUGIN_DIR . '/includes/functions.php' );
	
	////////////////////////////
	// SET UP POST TYPE
	////////////////////////////

	//REGISTER CPT
	function fca_slc_register_post_type() {
		
		$labels = array(
			'name' => _x('Social Lockers','locker-cat'),
			'singular_name' => _x('Social Locker','locker-cat'),
			'add_new' => _x('Add New','locker-cat'),
			'all_items' => _x('All Social Lockers','locker-cat'),
			'add_new_item' => _x('Add New Social Locker','locker-cat'),
			'edit_item' => _x('Edit Social Locker','locker-cat'),
			'new_item' => _x('New Social Locker','locker-cat'),
			'view_item' => _x('View Social Locker','locker-cat'),
			'search_items' => _x('Search Social Lockers','locker-cat'),
			'not_found' => _x('Social Locker not found','locker-cat'),
			'not_found_in_trash' => _x('No Social Lockers found in trash','locker-cat'),
			'parent_item_colon' => _x('Parent Social Locker:','locker-cat'),
			'menu_name' => _x('Social Locker','locker-cat')
		);
			
		$args = array(
			'labels' => $labels,
			'description' => "",
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => false,
			'show_in_nav_menus' => false,
			'show_in_menu' => false,
			'show_in_admin_bar' => false,
			'menu_position' => 118,
			'menu_icon' => FCA_SLC_PLUGINS_URL . '/assets/icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title'),
			'has_archive' => false,
			'rewrite' => false,
			'query_var' => true,
			'can_export' => true,
		);
		
		register_post_type( 'contentlocker', $args );
	}
	add_action ( 'init', 'fca_slc_register_post_type' );
	
	//CHANGE CUSTOM 'UPDATED' MESSAGES FOR OUR CPT
	function fca_slc_post_updated_messages( $messages ){
		
		$post = get_post();
		
		$messages['contentlocker'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Social Locker updated.','locker-cat'),
			2  => __( 'Social Locker updated.','locker-cat'),
			3  => __( 'Social Locker deleted.','locker-cat'),
			4  => __( 'Social Locker updated.','locker-cat'),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Social Locker restored to revision from %s','locker-cat'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Social Locker published.' ,'locker-cat'),
			7  => __( 'Social Locker saved.' ,'locker-cat'),
			8  => __( 'Social Locker submitted.' ,'locker-cat'),
			9  => sprintf(
				__( 'Social Locker scheduled for: <strong>%1$s</strong>.','locker-cat'),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Social Locker draft updated.' ,'locker-cat'),
		);

		return $messages;
	}
	add_filter('post_updated_messages', 'fca_slc_post_updated_messages' );
	
	function fca_slc_remove_screen_options_tab ( $show_screen, $screen ) {
		if ( $screen->id == 'contentlocker' ) {
			return false;
		}
		return $show_screen;
	}	
	add_filter('screen_options_show_screen', 'fca_slc_remove_screen_options_tab', 10, 2);
	
	function fca_slc_tooltip( $text = 'Tooltip', $icon = 'dashicons dashicons-editor-help' ) {
		return "<span class='$icon fca_slc_tooltip' title='" . htmlentities( $text ) . "'></span>";
	}	
	
	//DEACTIVATION SURVEY
	function fca_slc_admin_deactivation_survey( $hook ) {
		if ( $hook === 'plugins.php' ) {
			
			ob_start(); ?>
			
			<div id="fca-deactivate" style="position: fixed; left: 232px; top: 191px; border: 1px solid #979797; background-color: white; z-index: 9999; padding: 12px; max-width: 669px;">
				<h3 style="font-size: 14px; border-bottom: 1px solid #979797; padding-bottom: 8px; margin-top: 0;"><?php _e( 'Sorry to see you go', 'locker-cat' ) ?></h3>
				<p><?php _e( 'Hi, this is David, the creator of Social Locker by Locker Cat. Thanks so much for giving my plugin a try. I’m sorry that you didn’t love it.', 'locker-cat' ) ?>
				</p>
				<p><?php _e( 'I have a quick question that I hope you’ll answer to help us make Social Locker by Locker Cat better: what made you deactivate?', 'locker-cat' ) ?>
				</p>
				<p><?php _e( 'You can leave me a message below. I’d really appreciate it.', 'locker-cat' ) ?>
				</p>
				
				<p><textarea style='width: 100%;' id='fca-slc-deactivate-textarea' placeholder='<?php _e( 'What made you deactivate?', 'locker-cat' ) ?>'></textarea></p>
				
				<div style='float: right;' id='fca-deactivate-nav'>
					<button style='margin-right: 5px;' type='button' class='button button-secondary' id='fca-slc-deactivate-skip'><?php _e( 'Skip', 'locker-cat' ) ?></button>
					<button type='button' class='button button-primary' id='fca-slc-deactivate-send'><?php _e( 'Send Feedback', 'locker-cat' ) ?></button>
				</div>
			
			</div>
			
			<?php
				
			$html = ob_get_clean();
			
			$data = array(
				'html' => $html,
				'nonce' => wp_create_nonce( 'fca_slc_uninstall_nonce' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			);
						
			wp_enqueue_script('fca_slc_deactivation_js', FCA_SLC_PLUGINS_URL . '/includes/deactivation.min.js', false, FCA_SLC_PLUGIN_VER, true );
			wp_localize_script( 'fca_slc_deactivation_js', "fca_slc", $data );
		}
		
		
	}	
	add_action( 'admin_enqueue_scripts', 'fca_slc_admin_deactivation_survey' );
}