<?php
/*
Plugin Name: BJM Utility Plugins List
Plugin URI: https://github.com/bjmdigital/bjm-utility-plugins-list
Description: This is a helper plugin to get a list of all plugin
Author: BJM Digital
Version: 0.0.1
Author URI: https://bjmdigital.com.au/
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_action( 'wp_footer', function(){

	if( ! current_user_can( 'administrator' ) ){
		return;
	}

	if( ! isset( $_GET['bjm-plugins-list'] ) ){
		return;
	}

	include_once ABSPATH . DIRECTORY_SEPARATOR . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'update.php';

	if( ! function_exists( 'get_plugin_updates' ) ){
		echo 'get_plugin_updates function could not be found';
		return;
	}

	$plugins = get_plugin_updates();

	if( empty( $plugins ) ){
		echo 'Updates could not be found.';
		echo PHP_EOL;
		echo 'Please goto Wp-Admin > Updates > Check for updates, and then check back here';
		return;
	}

	printf( '<table><thead><tr><th>%s</th><th>%s</th><th>%s</th></tr></thead><tbody>',
		'Plugin',
		'Old Version',
		'New Version'
	);
	foreach ($plugins as $plugin):
		$plugin_name = $plugin->Name;
		$current_version = $plugin->Version;
		$plugin_update = isset($plugin->update) ? $plugin->update : null;
		if( is_object( $plugin_update ) ){
			$new_version = $plugin_update->new_version;
		} else {
			$new_version = '-';
		}

		printf( '<tr><td>%s</td><td>%s</td><td>%s</td></tr>',
			$plugin_name,
			$current_version,
			$new_version
		);

	endforeach;

	printf( '</tbody></table>');

} );
