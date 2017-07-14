<?php
	
function fca_slc_splash_page() {
	add_submenu_page(
		null,
		__('Activate', 'locker-cat'),
		__('Activate', 'locker-cat'),
		'manage_options',
		'locker-cat-splash',
		'fca_slc_render_splash_page'
	);
}
add_action( 'admin_menu', 'fca_slc_splash_page' );

function fca_slc_render_splash_page() {
		
	wp_enqueue_style('fca_slc_splash_css', FCA_SLC_PLUGINS_URL . '/includes/splash/splash.min.css', false, FCA_SLC_PLUGIN_VER );
	wp_enqueue_script('fca_slc_splash_js', FCA_SLC_PLUGINS_URL . '/includes/splash/splash.min.js', false, FCA_SLC_PLUGIN_VER, true );
		
	$user = wp_get_current_user();
	$name = empty( $user->user_firstname ) ? '' : $user->user_firstname;
	$email = $user->user_email;
	$site_link = '<a href="' . get_site_url() . '">'. get_site_url() . '</a>';
	$website = get_site_url();
	$nonce = wp_create_nonce( 'fca_slc_activation_nonce' );
	
	echo '<form method="post" action="' . admin_url( '/admin.php?page=locker-cat-splash' ) . '">';
		echo '<div id="fca-logo-wrapper">';
			echo '<div id="fca-logo-wrapper-inner">';
				echo '<img id="fca-logo-text" src="' . FCA_SLC_PLUGINS_URL . '/assets/fatcatapps-logo-text.png' . '">';
			echo '</div>';
		echo '</div>';
		
		echo "<input type='hidden' name='fname' value='$name'>";
		echo "<input type='hidden' name='email' value='$email'>";
		echo "<input type='hidden' name='fca-slc-nonce' value='$nonce'>";
		
		echo '<div id="fca-splash">';
			echo '<h1>' . __( 'Welcome to Social Locker by Locker Cat', 'locker-cat' ) . '</h1>';
			
			echo '<div id="fca-splash-main" class="fca-splash-box">';
				echo '<p id="fca-splash-main-text">' .  sprintf ( __( 'In order to enjoy all our features and functionality, Social Locker by Locker Cat needs to connect your user, %1$s at %2$s, to <strong>api.fatcatapps.com</strong>.', 'locker-cat' ), '<strong>' . $name . '</strong>', '<strong>' . $website . '</strong>'  ) . '</p>';
				echo "<button type='submit' id='fca-slc-submit-btn' class='fca-slc-button button button-primary' name='fca-slc-submit-optin' >" . __( 'Connect', 'locker-cat') . "</button><br>";
				echo "<button type='submit' id='fca-slc-optout-btn' name='fca-slc-submit-optout' >" . __( 'Skip This Step', 'locker-cat') . "</button>";
			echo '</div>';
			
			echo '<div id="fca-splash-permissions" class="fca-splash-box">';
				echo '<a id="fca-splash-permissions-toggle" href="#" >' . __( 'What permission is being granted?', 'locker-cat' ) . '</a>';
				echo '<div id="fca-splash-permissions-dropdown" style="display: none;">';
					echo '<h3>' .  __( 'Your Website Info', 'locker-cat' ) . '</h3>';
					echo '<p>' .  __( 'Your URL, WordPress version, plugins & themes.', 'locker-cat' ) . '</p>';
					
					echo '<h3>' .  __( 'Your Info', 'locker-cat' ) . '</h3>';
					echo '<p>' .  __( 'Your name and email.', 'locker-cat' ) . '</p>';
					
					echo '<h3>' .  __( 'Plugin Usage', 'locker-cat' ) . '</h3>';
					echo '<p>' .  __( 'How you use Social Locker by Locker Cat.', 'locker-cat' ) . '</p>';				
				echo '</div>';
			echo '</div>';
			

		echo '</div>';
	
	echo '</form>';
	
	echo '<div id="fca-splash-footer">';
		echo '<a target="_blank" href="https://fatcatapps.com/legal/terms-service/">' . _x( 'Terms', 'as in terms and conditions', 'locker-cat' ) . '</a> | <a target="_blank" href="https://fatcatapps.com/legal/privacy-policy/">' . _x( 'Privacy', 'as in privacy policy', 'locker-cat' ) . '</a>';
	echo '</div>';
}

function fca_slc_admin_redirects() {
	if ( isset( $_POST['fca-slc-nonce'] ) ) {
		
		$nonce_verified = wp_verify_nonce( $_POST['fca-slc-nonce'], 'fca_slc_activation_nonce' ) == 1;
		
		if ( isset( $_POST['fca-slc-submit-optout'] ) && $nonce_verified ) {
			update_option( 'fca_slc_activation_status', 'disabled' );
			wp_redirect( admin_url( '/admin.php?page=locker-cat' ) );
			exit;
		} else if ( isset( $_POST['fca-slc-submit-optin'] ) && $nonce_verified ) {
			update_option( 'fca_slc_activation_status', 'active' );
			$email = urlencode ( sanitize_email ( $_POST['email'] ) );
			$name = urlencode ( sanitize_text_field ( $_POST['fname'] ) );
			$product = 'lockercat';
			$url =  "https://api.fatcatapps.com/api/activate.php?email=$email&fname=$name&product=$product";
			$args = array(
				'timeout'     => 15,
				'redirection' => 15,
				'blocking'    => true,
				'sslverify'   => false
			); 
			$return = wp_remote_get( $url, $args );

			wp_redirect( admin_url( '/admin.php?page=locker-cat' ) );
			exit;
		}
	}
	
	$status = get_option( 'fca_slc_activation_status' );
	if ( empty( $status ) && isset( $_GET['page'] ) && $_GET['page'] === 'locker-cat' ) {
        wp_redirect( admin_url( '/admin.php?page=locker-cat-splash' ) );
		exit;
    }

}
add_action('admin_init', 'fca_slc_admin_redirects');

