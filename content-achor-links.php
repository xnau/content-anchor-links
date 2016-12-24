<?php

/*
 * Plugin Name: Content Anchor Links
 * Version: 1.0
 * Description: Easily add content anchors to your pages and posts
 * Author: Roland Barker, xnau webdesign
 * Version: 1.0
 * Author URI: https://xnau.com
 * License: GPL3
 * Text Domain: participants-database
 * Domain Path: /languages
 * 
 */

include 'xnau_WP_Headings_IDs.php';
add_filter( 'content_edit_pre', array('xnau_WP_Headings_IDs', 'add_heading_ids') );
add_filter( 'wp_link_query', array('xnau_WP_Headings_IDs', 'get_content_anchors') );