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
			$overlay_color = isset( $row['overlay_color'] ) ? $row['overlay_color'] : '';
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

			$overlay_style = $overlay_color ? ' style="--mig-overlay-color: ' . esc_attr( $overlay_color ) . ';"' : '';
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
}
