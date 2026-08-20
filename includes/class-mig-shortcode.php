<?php
/**
 * [masonry_gallery] shortcode: renders the gallery_images repeater from the
 * ACF Options Page as a responsive CSS-masonry grid.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MIG_Shortcode {

	protected static $instance_count = 0;

	public static function init() {
		add_shortcode( 'masonry_gallery', array( __CLASS__, 'render' ) );
	}

	public static function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'columns_desktop' => 3,
				'columns_tablet'  => 2,
				'columns_mobile'  => 1,
				'gap'             => 16,
			),
			$atts,
			'masonry_gallery'
		);

		if ( ! function_exists( 'get_field' ) ) {
			return '';
		}

		$rows = get_field( 'gallery_images', 'option' );
		if ( empty( $rows ) ) {
			return '';
		}

		if ( function_exists( 'mig_load_assets' ) ) {
			mig_load_assets();
		}

		self::$instance_count++;
		$instance_id = 'mig-' . self::$instance_count . '-' . substr( md5( wp_rand() ), 0, 6 );

		$style = sprintf(
			'--mig-cols-desktop: %d; --mig-cols-tablet: %d; --mig-cols-mobile: %d; --mig-gap: %dpx;',
			max( 1, (int) $atts['columns_desktop'] ),
			max( 1, (int) $atts['columns_tablet'] ),
			max( 1, (int) $atts['columns_mobile'] ),
			max( 0, (int) $atts['gap'] )
		);

		ob_start();
		?>
		<div class="mig-gallery" id="<?php echo esc_attr( $instance_id ); ?>" style="<?php echo esc_attr( $style ); ?>">
			<?php echo MIG_Render::gallery( $rows, $instance_id ); // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped partials. ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
