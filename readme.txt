=== Masonry Image Gallery ===
Contributors: Verisimilitudesque
Tags: gallery, masonry, acf, images
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.3
License: GPLv2 or later

Responsive masonry image gallery with a color-gradient overlay and overlaid text, managed from a single ACF Options Page.

== Description ==

Displays a responsive, CSS-masonry image grid. Each image has a gradient overlay (opaque at the bottom, fading to fully transparent at the top, built from a single overlay_color field) with an always-visible heading/sub-heading on top. Content is managed entirely from an ACF Options Page repeater — no per-post setup required.

Place the gallery anywhere with:

`[masonry_gallery]`

Optional shortcode attributes:

* `columns_desktop` (default 3)
* `columns_tablet` (default 2, applies at 900px and below)
* `columns_mobile` (default 1, applies at 600px and below)
* `gap` (default 16, in pixels)

== Requirements ==

Requires Advanced Custom Fields (or ACF Pro) and an Options Page with the following field structure:

* `gallery_images` — Repeater
	* `image` — Image
	* `overlay_color` — Color Picker
	* `text` — Group
		* `heading` — Text
		* `sub_heading` — Text
	* `link` — Link (optional)

== Changelog ==

= 1.0.3 =
* Reverted 1.0.2's grid layout back to CSS-column masonry (natural image aspect ratios, no cropping). Removed the `aspect_ratio` shortcode attribute along with it.

= 1.0.2 =
* Switched from CSS-column masonry (variable image heights, jagged bottom edges) to a uniform grid with square cells and object-fit: cover, so the gallery forms a clean rectangle. Images are now cropped to fill their cell instead of keeping their natural aspect ratio.
* Added `aspect_ratio` shortcode attribute (default "1/1") to control cell shape.

= 1.0.1 =
* Overlay changed from a flat hover-darkening tint to an always-on gradient (opaque at bottom, transparent at top) built from overlay_color.
* Fixed overlay_color not rendering when the ACF Color Picker's RGBA array return format was used.
* Default columns changed to 3 desktop / 2 tablet / 1 mobile.

= 1.0.0 =
* Initial release.
