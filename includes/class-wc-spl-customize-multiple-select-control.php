<?php
/**
 * WC_SPF_Customize_Control_Multiple_Select class.
 *
 * @since 1.2.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Adds a multi-select customizer control.
 *
 * @since 1.2.0
 */
class WC_SPL_Customize_Multiple_Select_Control extends WP_Customize_Control {

	/**
     * The type of customize control being rendered.
     *
     * @var string
     */
	public $type = 'wc-spl-multiple-select';

    /**
     * Enqueue scripts/styles.
     */
    public function enqueue() {
    	wp_enqueue_style( 'woocommerce_admin_styles' );
        wp_enqueue_script( 'wc-enhanced-select' );
    }

	/**
	 * Displays the multiple select on the customize screen.
	 */
	public function render_content() {

		if ( empty( $this->choices ) ) {
			return;
		}

		?>
			<label>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>

				<select multiple="multiple" class="wc-enhanced-select" <?php $this->link(); ?>>
					<?php
						foreach ( $this->choices as $value => $label ) {
							echo '<option value="' . esc_attr( $value ) . '"' . selected( in_array( $value, $this->value() ), true , false ) . '>' . $label . '</option>';
						}
					?>
				</select>
			</label>
		<?php
	}
}
