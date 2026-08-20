<?php
/**
 * Renders the gallery markup from the gallery_images repeater rows.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MIG_Render {

	/**
	 * @param array $rows One row per get_field( 'gallery_images', 'option' ) item.
	 * @param string $instance_id Unique id for this shortcode instance.
	 * @return string
	 */
	public static function gallery( $rows, $instance_id ) {
		ob_start();
		foreach ( $rows as $row ) {
			$image        = isset( $row['image'] ) ? $row['image'] : null;
			$color_parts   = isset( $row['overlay_color'] ) ? self::parse_color( $row['overlay_color'] ) : null;
			$text          = isset( $row['text'] ) ? $row['text'] : array();
			$heading       = isset( $text['heading'] ) ? $text['heading'] : '';
			$sub_heading   = isset( $text['sub_heading'] ) ? $text['sub_heading'] : '';
			$link          = isset( $row['link'] ) ? $row['link'] : null;

			if ( empty( $image['ID'] ) ) {
				continue;
			}

			$link_url    = ! empty( $link['url'] ) ? $link['url'] : '';
			$link_target = ! empty( $link['target'] ) ? $link['target'] : '';
			$tag         = $link_url ? 'a' : 'div';

			$overlay_style = '';
			if ( $color_parts ) {
				list( $r, $g, $b, $a ) = $color_parts;
				$solid         = sprintf( 'rgba(%d, %d, %d, %s)', $r, $g, $b, $a );
				$transparent   = sprintf( 'rgba(%d, %d, %d, 0)', $r, $g, $b );
				$overlay_style = sprintf( ' style="--mig-overlay-color: %s; --mig-overlay-color-transparent: %s;"', esc_attr( $solid ), esc_attr( $transparent ) );
			}
			?>
			<<?php echo $tag; ?>
				class="mig-item"
				<?php if ( $link_url ) : ?>
					href="<?php echo esc_url( $link_url ); ?>"
					<?php if ( $link_target ) : ?>target="<?php echo esc_attr( $link_target ); ?>" rel="noopener noreferrer"<?php endif; ?>
				<?php endif; ?>
			>
				<?php echo wp_get_attachment_image( $image['ID'], 'large', false, array( 'loading' => 'lazy' ) ); ?>
				<span class="mig-overlay"<?php echo $overlay_style; // phpcs:ignore WordPress.Security.EscapeOutput -- built from esc_attr above. ?>>
					<?php if ( $heading ) : ?><span class="mig-heading"><?php echo esc_html( $heading ); ?></span><?php endif; ?>
					<?php if ( $sub_heading ) : ?><span class="mig-subheading"><?php echo esc_html( $sub_heading ); ?></span><?php endif; ?>
				</span>
			</<?php echo $tag; ?>>
			<?php
		}
		return ob_get_clean();
	}

	/**
	 * Breaks an ACF Color Picker value down into [r, g, b, a] components so
	 * the caller can build both a solid stop and a same-hue, fully
	 * transparent stop for the overlay gradient. Handles ACF's RGBA array
	 * return format as well as hex ("#rgb", "#rrggbb", "#rrggbbaa") and
	 * rgb()/rgba() string formats.
	 *
	 * @return array{0:int,1:int,2:int,3:float}|null
	 */
	protected static function parse_color( $value ) {
		if ( is_array( $value ) ) {
			$r = isset( $value['red'] ) ? $value['red'] : ( isset( $value['r'] ) ? $value['r'] : null );
			$g = isset( $value['green'] ) ? $value['green'] : ( isset( $value['g'] ) ? $value['g'] : null );
			$b = isset( $value['blue'] ) ? $value['blue'] : ( isset( $value['b'] ) ? $value['b'] : null );
			$a = isset( $value['alpha'] ) ? $value['alpha'] : ( isset( $value['a'] ) ? $value['a'] : 1 );

			if ( null === $r || null === $g || null === $b ) {
				return null;
			}

			return array( (int) $r, (int) $g, (int) $b, (float) $a );
		}

		if ( ! is_string( $value ) || '' === trim( $value ) ) {
			return null;
		}

		$value = trim( $value );

		if ( preg_match( '/^rgba?\(\s*([\d.]+)\s*,\s*([\d.]+)\s*,\s*([\d.]+)\s*(?:,\s*([\d.]+)\s*)?\)$/i', $value, $m ) ) {
			return array( (int) $m[1], (int) $m[2], (int) $m[3], isset( $m[4] ) ? (float) $m[4] : 1.0 );
		}

		if ( preg_match( '/^#?([a-f0-9]{3}|[a-f0-9]{6}|[a-f0-9]{8})$/i', $value, $m ) ) {
			$hex = $m[1];
			if ( 3 === strlen( $hex ) ) {
				$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
			}
			$a = 1.0;
			if ( 8 === strlen( $hex ) ) {
				$a   = round( hexdec( substr( $hex, 6, 2 ) ) / 255, 2 );
				$hex = substr( $hex, 0, 6 );
			}
			return array( hexdec( substr( $hex, 0, 2 ) ), hexdec( substr( $hex, 2, 2 ) ), hexdec( substr( $hex, 4, 2 ) ), $a );
		}

		return null;
	}
}
