<?php
/**
 * Actions
 *
 * @package     EDD\EDD_Send_Cart\Functions
 * @since       1.0.0
 */
// Exit if accessed directly
//
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Display the "Send Cart" button on the checkout
 *
 * @since 1.0
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_send_cart_button() {
	global $edd_options;

	$color = isset( $edd_options[ 'checkout_color' ] ) ? $edd_options[ 'checkout_color' ] : 'blue';
	$color = ( $color == 'inherit' ) ? '' : $color;
	$text  = apply_filters( 'edd_send_cart_button_text', __( 'Send Cart', 'edd-send-cart' ) );
	if ( apply_filters( 'edd_send_cart_button_show_icon', true ) ) {
		$text = apply_filters( 'edd_send_cart_button_icon_html', '<span class="dashicons dashicons-email-alt"></span>&nbsp;' ) . $text;
	}
	?>
	<a class="edd-cart-sending-button edd-submit button<?php echo ' ' . $color; ?>" id="edd-send-cart-button" href="#"><?php echo $text; ?></a>
	<?php
}
add_action( 'edd_cart_footer_buttons', 'edd_send_cart_button' );

/**
 * Display the "Send Cart Dialog" on the checkout
 *
 * @since 1.0
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function edd_send_cart_dialog() {
	global $edd_options;

		$color = isset( $edd_options[ 'checkout_color' ] ) ? $edd_options[ 'checkout_color' ] : 'blue';
		$color = ( $color == 'inherit' ) ? '' : $color;

		$edit_subject = !edd_get_option( 'EDD_Send_Cart_allow_subject_edit' ) ? 'readonly="readonly" ' : '';

		?>
		<p id="edd-send-cart-form-wrapper">
			<form>
				<strong><?php _e( 'Recipient', 'edd-send-cart' ); ?></strong><br />
				<input class="text-half" type="text" name="eddsc-recipient-first-name" value="" placeholder="<?php _e( 'First Name', 'edd-send-cart' ); ?>" /> &nbsp;<input class="text-half" type="text" name="eddsc-recipient-last-name" value="" placeholder="<?php _e( 'Last Name', 'edd-send-cart' ); ?>" /><br />
				<input class="text-half" type="text" name="eddsc-recipient-email" value="" placeholder="<?php _e( 'Ricipient Email', 'edd-send-cart' ); ?>"<br />

				<strong><?php _e( 'From', 'edd-send-cart' ); ?></strong>
				<input class="text-half" type="text" name="eddsc-sender-first-name" value="" placeholder="<?php _e( 'First Name', 'edd-send-cart' ); ?>" /> &nbsp;<input class="text-half" type="text" name="eddsc-sender-last-name" value="" placeholder="<?php _e( 'Last Name', 'edd-send-cart' ); ?>" /><br />
				<input class="text-half"  type="text" name="eddsc-sender-email" value="" placeholder="<?php _e( 'Sender Email', 'edd-send-cart' ); ?>" /><br />

				<strong><?php _e( 'Subject', 'edd-send-cart' ); ?><?php echo !edd_get_option( 'EDD_Send_Cart_allow_subject_edit' ) ? '&nbsp;<span class="dashicons dashicons-lock"></span>' : ''; ?></strong>
				<input class="text-half" <?php echo $edit_subject; ?> type="text" name="eddsc-email-subject" value="" placeholder="<?php echo edd_get_option( 'EDD_Send_Cart_subject' ); ?>" /><br />

				<textarea>
<?php echo esc_attr( edd_get_option( 'EDD_Send_Cart_text' ) ); // Alignment is critial for proper output ?>
				</textarea>

				<span class="small-text">
					<?php _e( 'Feel free to personalize this message. All {placeholder} items will add the appropriate content when you send the email', 'edd-send-cart' ); ?>
				</span>
				<span class="send-button">
					<a class="edd-cart-sending-button edd-submit button<?php echo ' ' . $color; ?>" id="edd-send-cart-now" href="<?php echo add_query_arg( 'edd_action', 'send_cart' ) ?>"><?php _e( 'Send', 'edd-send-cart' ); ?></a>
				</span>
			</form>
		</p>
		<?php

}
add_action( 'edd_cart_footer_buttons', 'edd_send_cart_dialog', 99 );
