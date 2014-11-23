<?php
/**
 * Scripts
 *
 * @package     EDD\EDD_Send_Cart\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @global      array $edd_settings_page The slug for the EDD settings page
 * @global      string $post_type The type of post that we are editing
 * @return      void
 */
function edd_send_cart_admin_scripts( $hook ) {
	global $edd_settings_page, $post_type;

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	if( $hook == $edd_settings_page ) {
		wp_enqueue_script( 'edd_send_cart_admin_js', EDD_SEND_CART_URL . 'assets/js/admin' . $suffix . '.js', array( 'jquery' ) );
		wp_enqueue_style( 'edd_send_cart_admin_css', EDD_SEND_CART_URL . 'assets/css/admin' . $suffix . '.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'edd_send_cart_admin_scripts', 100 );


/**
 * Load frontend scripts
 *
 * @since       1.0.0
 * @return      void
 */
function edd_send_cart_scripts( $hook ) {
	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script( 'edd_send_cart_js', EDD_SEND_CART_URL . 'assets/js/scripts' . $suffix . '.js', array( 'jquery' ) );
	wp_enqueue_style( 'edd_send_cart_css', EDD_SEND_CART_URL . 'assets/css/styles' . $suffix . '.css' );
}
add_action( 'wp_enqueue_scripts', 'edd_send_cart_scripts' );
