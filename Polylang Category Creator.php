<?php
/*
 Plugin Name: Polylang Category Creator
 Plugin URI: https://github.com/merksk8/Polylang-Category-Creator
 Description: Allow to create categories faster, creating all languages in one page. For Polylang, compatible with woocommerce and custom taxonomy.
 Version: 1.5
 Author: Merksk8
 Author URI: https://profiles.wordpress.org/merksk8
 License: GPL2
 Copyright: Merk
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define ('WPLANG', 'en_US');
function mk_mbc_mainloader(){
	require( dirname( __FILE__ ) . '/admin/admin-page.php' );
	
}

add_action( 'init', 'mk_mbc_mainloader' );

load_plugin_textdomain('mk-mbc', false, basename( dirname( __FILE__ ) ) . '/languages' );

?>