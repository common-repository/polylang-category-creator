=== Polylang Category Creator ===
Contributors: Merksk8
Tags: polylang,multiple categories,bulk,woocommerce,taxonomy,custom
Requires at least: 4.6.1
Tested up to: 4.8
Stable tag: 1.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Polylang extension to create categories for all languages in one page. It detects your languages and taxonomies to get things done easier.

== Description ==

This plugin allows to create categories for Posts, Woocommerce Products, or other taxonomies.
In the admin page, it detects your Polylang languages and builds a form to create the category for each language in the same page, creating all at same time.

If having trouble post the issue on support section.

= Available Fields =

* Taxonomy (dropdown select)
* Parent Category (dropdown select)
* Category Name
* Category Slug
* Category description


= Languages =
* English
* Spanish
* Catalan(New)

== Installation ==

1. Upload this plugin to the /wp-content/plugins/ directory.
2. Activate the plugin through the Plugins menu in WordPress.
3. Go to Multilang-Cat section to create categories for all languages.

== Frequently Asked Questions ==

= What requires? =

Only Polylang Plugin.

= How it Works? =

Select the taxonomy, optionally select category for parent, and fill each language for create the categories.

= Does it work with custom taxonomies? =

Tested with Woocommerce, Category and Tags for posts. It should work with custom taxonomies, if not, you can ask for it in support page.

== Screenshots ==
1. How it looks.

== Changelog ==
= 1.5 =
* Added Catalan language
* Edited some interface
* Corrected some language translations
= 1.4 =
* Removed update system 
* Added Nonce field for form
* Added validating for form:
** set CATEGORY NAME (2 to 26 length) 
** set CATEGORY SLUG (0 to 26 length)
** set CATEGORY DESCRIPTION (0 to 40 length)
* Added sanitize for NAME, SLUG, DESCRIPTION
* Added check taxonomy_exists() for Taxonomy selected
* Changed the translations to esc_html_e()
= 1.3 =
* Added a temporary update system through GitHub, hope get in wordpress directory and use default wordpress system :)
= 1.2 =
* Changed the Taxonomy picker and Parent Category
* Added some protection when creating category to filter the fields values
* Parent Category is now applying to translations
= 1.1 =
* Filtered only taxonomies that are translated
* Removed the requirement of filling slug and description
* Changed text to textarea the description field
= 1.0 =
* Initial release
