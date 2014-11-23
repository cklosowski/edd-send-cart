<?php
/**
 * Plugin Name:       Easy Digital Downloads - Send Cart
 * Plugin URI:      https://easydigitaldownloads.com/extensions/edd-send-cart
 * Description:     Allows a visitor to build a cart, and send to someone via email
 * Version:         1.0.0
 * Author:          Chris Klosowski
 * Author URI:      https://filament-studios.com
 * Text Domain:     edd-send-cart
 *
 * @package         EDD\EDD_Send_Cart
 * @author          Chris Klosowski
 * @copyright       Copyright (c) 2014 Filament Studios
 *
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Send_Cart' ) ) {

	/**
	 * Main EDD_Send_Cart class
	 *
	 * @since       1.0.0
	 */
	class EDD_Send_Cart {

		/**
		 * @var         EDD_Send_Cart $instance The one true EDD_Send_Cart
		 * @since       1.0.0
		 */
		private static $instance;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      object self::$instance The one true EDD_Send_Cart
		 */
		public static function instance() {
			if( !self::$instance ) {
				self::$instance = new EDD_Send_Cart();
				if ( self::$instance->verify_config() ) {
					self::$instance->setup_constants();
					self::$instance->includes();
					self::$instance->load_textdomain();
					self::$instance->hooks();
				}
			}

			return self::$instance;
		}

		/**
		 * Verify conditions are set for Send Cart to work correctly
		 *
		 * @access private
		 * @since  1.0.0
		 * @return bool If Send Cart can work with the current site configuration
		 */
		private function verify_config() {
			$config_valid = true;

			if ( ! edd_get_option( 'enable_cart_saving', $default = false ) ) {
				add_action( 'admin_notices', array( $this, 'cart_saving_disabled' ) );
				$config_valid = false;
			}

			return $config_valid;
		}

		public function cart_saving_disabled() {
			echo sprintf( '<div class="error"><p>Send Cart' . __( ' requires the "Save Cart" feature of Easy Digital Downloads to be enabled. Please <a href="%s">enable it</a>.', 'edd-send-cart' ) . '</p></div>', admin_url( 'edit.php?post_type=download&page=edd-settings&tab=misc' ) );
		}


		/**
		 * Setup plugin constants
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function setup_constants() {
			// Plugin version
			define( 'EDD_SEND_CART_VER', '1.0.0' );

			// Plugin path
			define( 'EDD_SEND_CART_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin URL
			define( 'EDD_SEND_CART_URL', plugin_dir_url( __FILE__ ) );
		}


		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {
			// Include scripts
			require_once EDD_SEND_CART_DIR . 'includes/scripts.php';
			require_once EDD_SEND_CART_DIR . 'includes/functions.php';
			require_ONCE EDD_SEND_CART_DIR . 'includes/actions.php';
		}


		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		private function hooks() {
			// Register settings
			add_filter( 'edd_settings_extensions', array( $this, 'settings' ), 1 );

			// Handle licensing
			if( class_exists( 'EDD_License' ) ) {
				$license = new EDD_License( __FILE__, 'Send Cart', EDD_SEND_CART_VER, 'Chris Klosowski' );
			}
		}


		/**
		 * Internationalization
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public function load_textdomain() {
			// Set filter for language directory
			$lang_dir = EDD_SEND_CART_DIR . '/languages/';
			$lang_dir = apply_filters( 'edd_send_cart_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'edd_send_cart_locale', get_locale(), 'edd-send-cart' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'edd-send-cart', $locale );

			// Setup paths to current locale file
			$mofile_local   = $lang_dir . $mofile;
			$mofile_global  = WP_LANG_DIR . '/edd-send-cart/' . $mofile;

			if( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/edd-send-cart/ folder
				load_textdomain( 'edd-send-cart', $mofile_global );
			} elseif( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/edd-send-cart/languages/ folder
				load_textdomain( 'edd-send-cart', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'edd-send-cart', false, $lang_dir );
			}
		}


		/**
		 * Add settings
		 *
		 * @access      public
		 * @since       1.0.0
		 * @param       array $settings The existing EDD settings array
		 * @return      array The modified EDD settings array
		 */
		public function settings( $settings ) {
			$new_settings = array(
				array(
					'id'    => 'EDD_Send_Cart_settings',
					'name'  => '<strong>' . __( 'Send Cart Settings', 'edd-send-cart' ) . '</strong>',
					'desc'  => __( 'Configure Send Cart Settings', 'edd-send-cart' ),
					'type'  => 'header'
				),
				array(
					'id'    => 'EDD_Send_Cart_subject',
					'name'  => __( 'Default Email Subject', 'edd-send-cart' ),
					'desc'  => __( 'The defualt email subject to be sent with a cart.', 'edd-send-cart' ),
					'type'  => 'text',
					'std'   => __( 'Check out this cart on ' . get_bloginfo( 'name' ), 'edd-send-cart' )
				),
				array(
					'id'    => 'EDD_Send_Cart_allow_subject_edit',
					'name'  => __( 'Allow users to edit the subject.', 'edd-send-cart' ),
					'desc'  => __( 'Allow users to alter the subject line.', 'edd-send-cart' ),
					'type'  => 'checkbox'
				),
				array(
					'id'    => 'EDD_Send_Cart_text',
					'name'  => __( 'Defauilt Send Cart Message', 'edd-send-cart' ),
					'desc'  => __( 'The default text provided to the user when sending a cart.', 'edd-send-cart' ),
					'type'  => 'rich_editor',
					'std'   => __( "Dear", "edd-send-cart" ) . " {name},\n\n" . __( "{from} thought you might like to see what they are shopping for on {sitename}.", "edd-send-cart" ) . "\n\n{link}\n\n"
				)
			);

			return array_merge( $settings, $new_settings );
		}


	/*
	 * Activation function fires when the plugin is activated.
	 *
	 * This function is fired when the activation hook is called by WordPress,
	 *
	 */
	public static function activation() {
		/*Activation functions here*/

		}


	}


/**
 * The main function responsible for returning the one true EDD_Send_Cart
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \EDD_Send_Cart The one true EDD_Send_Cart
 *
 */
function edd_send_cart_load() {
	if( ! class_exists( 'Easy_Digital_Downloads' ) ) {
		if( ! class_exists( 'EDD_Extension_Activation' ) ) {
			require_once 'includes/class.extension-activation.php';
		}

		$activation = new EDD_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();
		return EDD_Send_Cart::instance();
	} else {
		return EDD_Send_Cart::instance();
	}
}

/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class hence, needs to be called outside and the
 * function also needs to be static.
 */
register_activation_hook( __FILE__, array( 'EDD_Send_Cart', 'activation' ) );


add_action( 'plugins_loaded', 'edd_send_cart_load' );

} // End if class_exists check
