=== UI Leaflet Integration ===
Contributors: usability.idealist
Tags: shortcode, content, helpers, development
Requires at least: 4.7
Tested up to: 5.0-nightly
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Shortcodes plus a few helpers for safe editing content. Esp. helpful when redesign or restructuring a pre-existing website.

== Description ==

Shortcodes plus a few helpers for safe editing content. Esp. helpful when redesign or restructuring a pre-existing website. Aimed at experienced users and developers.

Features

* a selection of shortcodes to help out with editing content
* auto-add post slugs to menu items

= Work in progress =

* "Config" constants to eg. disable the auto-post "slugging"
* Filter hook documentation

= Future plans = 

* Shortcode insertion via a nice user interface in the editor
* Properly implemented shortcode asset loading via a separate class / plugin

= Website =

http://f2w.de/ui-cs


= Please Vote and Enjoy =

Your votes really make a difference! Thanks.


== Installation ==

1. Upload 'ui-cs' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Done!

== Frequently Asked Questions ==

= Shortcode documentation =

All available shortcodes:

= remove_content =
`[remove_content]My removed content .. [/remove_content]`

Removes contained content in frontend view.

**Parameters**

* label - Optional label, which will be placed AFTER the concerned (removed) content.

= hide_content =
`[hide_content]My hidden content ..[/hide_content]`

Hides content by wrapping it in HTML comment tags.

**Parameters**

* label - Optional label, which will be placed BEFORE the concerned (hidden) content.

= permalink =

Insert full URL for the given post ID or post slug. Requires either post_id OR the post slug PLUS post type (defaults to `page`).

**Parameters**

* post_id / id - Post ID
* slug - post / page slug.
* post_type - Defaults to "page". Required when using the post `slug` attribute.

= link =

`[link post_id="20"]My link text[/link]`

Inserts full link including link text for the given post ID or post slug. Essentially an extended version of the `permalink` shortcode.

**Parameters**

* post_id / id - Post ID
* slug - post / page slug.
* post_type - Defaults to "page". Required when using the post `slug` attribute.
* class - HTML class of the link tag. Defaults to 'permalink'. 
* query - Optional query string to append to the url. Also see https://en.wikipedia.org/wiki/Query_string for more info.

= site_url =

Inserts the full URL of the current site. 

= css_cols =

`[css_cols cols="3"]My columnized content ... [/css_cols]`

Adds CSS columns to the contained content. Also see https://css-tricks.com/guide-responsive-friendly-css-columns/

**Parameters**

* cols - Column number
* number - Alias for `cols`
* width - Sets the `css-column-width` property
* wrap_tag - Tag to wrap the content in. Defaults to `div`

= columns =

Does the same as the shortcode `css_cols`, but uses a CSS class, by wrapping the given content inside a DIV container.

**Parameters**

* cols - Column number
* number - Alias for `cols`

= quote =

Wraps the contained content into a `blockquote` tag.

**Parameters**

* author - Author of this quote
* cite - Alias for `author`
* content_class - HTML class of the quote itself. Defaults to `quote-content`
* author_class - HTML class for the author / cite container. Defaults to `quote-author`
* dash_class - HTML class for the delimiter / "dash" between the quote and the author name. Defaults to `quote-dash`

= button =

Adds a Bootstrap v3-focused link button.

= wp_gallery =

Improved version of the default WP core shortcode `[gallery]`.

**Additional Parameters**

Aside the regular parameters, the following additional ones apply:

* width - Set a specific width for the images. If not is set, will use the original WP shortcode as a fallback
* slugs - Works analogue to `ids`, but uses file names / post slugs, and overwrites the post IDs supplied in `ids`

= Q. I have a question =
A. Chances are, someone else has asked it. Check out the support forum at WordPress.org first :)

== Changelog ==

= 1.2 =

* Initial public release

== Upgrade Notice ==

None yet.
