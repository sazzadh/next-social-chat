<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Next Social Chat
 * Plugin URI:        https://sazzadh.orcomg/next-social-chat
 * Description:       Integrate your WhatsApp experience directly into your website. This is one of the best way to connect and interact with your customer.
 * Version:           0.1
 * Author:            Sazzad
 * Author URI:        https://sazzadh.com
 * Text Domain:       next-social-chat
 * Domain Path:       /languages
 * Prefix:			  nextsocialchat
 */
namespace NextSocialChat;

defined('ABSPATH') || exit;

if ( ! defined( 'NEXTSOCIALCHAT_VERSION' ) ) {
	define( 'NEXTSOCIALCHAT_VERSION', '0.1' );
}

if ( ! defined( 'NEXTSOCIALCHAT_PLUGIN_URL' ) ) {
	define( 'NEXTSOCIALCHAT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'NEXTSOCIALCHAT_PLUGIN_DIR' ) ) {
	define( 'NEXTSOCIALCHAT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'NEXTSOCIALCHAT_BASE_NAME' ) ) {
	define( 'NEXTSOCIALCHAT_BASE_NAME', plugin_basename( __FILE__ ) );
}


include_once "vendor/autoload.php";

if ( ! function_exists( 'NextSocialChat\\init' ) ) {
	function init() {
		Admin::getInstance();
	}
}

add_action('plugins_loaded', 'NextSocialChat\\init');