<?php
	
function fca_slc_add_marketing_metaboxes( $post ) {

	add_meta_box( 
		'fca_slc_marketing_metabox',
		__( 'Level Up Your Social Locker', 'locker-cat' ),
		'fca_slc_render_marketing_metabox',
		null,
		'side',
		'default'
	);
	
	add_meta_box( 
		'fca_slc_quick_links_metabox',
		__( 'Quick Links', 'locker-cat' ),
		'fca_slc_render_quick_links_metabox',
		null,
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes_sociallocker', 'fca_slc_add_marketing_metaboxes', 11, 1 );

function fca_slc_render_marketing_metabox( $post ) {
	
	ob_start(); ?>

	<h3><?php _e( "Build Awesome Social Lockers With Social Locker by Locker Cat Premium", 'locker-cat' ); ?></h3>

	<ul>
		<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Boost Social Shares', 'locker-cat' ); ?></li>
		<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Get Leads & Email Subscribers', 'locker-cat' ); ?></li>
		<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Priority Email Support', 'locker-cat' ); ?></li>
	</ul>
    
	<p style="text-align: center;">
		<a href="https://fatcatapps.com/locker-cat/upgrade?utm_medium=plugin&utm_source=Landing%20Page%20Cat%20Free&utm_campaign=free-plugin" target="_blank" class="button button-primary button-large"><?php _e('Upgrade Now', 'locker-cat'); ?></a>
	</p> 

	<?php 
		
	echo ob_get_clean();
}

function fca_slc_render_quick_links_metabox( $post ) {
	
	ob_start(); ?>

	<ul class='fca_slc_marketing_checklist'>
		<li><div class="dashicons dashicons-arrow-right"></div><a href="http://wordpress.org/support/plugin/locker-cat" target="_blank"><?php _e( 'Problems or Suggestions? Get help here.', 'locker-cat' ); ?></a> </li>
		<li><div class="dashicons dashicons-arrow-right"></div><strong><a href="https://wordpress.org/support/plugin/locker-cat/reviews/" target="_blank"><?php _e( 'Like this plugin?  Please leave a review.', 'locker-cat' ); ?></strong></a> </li>
	</ul>

	<?php 
		
	echo ob_get_clean();
}
