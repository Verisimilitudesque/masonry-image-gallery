=== Masonry Image Gallery ===
Contributors: yourname
Tags: gallery, masonry, acf, images
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later

Responsive masonry image gallery with a color overlay and overlaid text, managed from a single ACF Options Page.

== Description ==

Displays a responsive, CSS-masonry image grid. Each image has a color overlay and an always-visible heading/sub-heading, with the overlay darkening on hover/focus. Content is managed entirely from an ACF Options Page repeater — no per-post setup required.

Place the gallery anywhere with:

`[masonry_gallery]`

Optional shortcode attributes:

* `columns_desktop` (default 4)
* `columns_tablet` (default 2, applies at 900px and below)
* `columns_mobile` (default 1, applies at 600px and below)
* `gap` (default 16, in pixels)
* `overlay_opacity` (default 0.5)
* `overlay_opacity_hover` (default 0.75)

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

= 1.0.0 =
* Initial release.
