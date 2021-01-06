<?php

/**
 * Plugin Name:       WP2Static Add-on: GCS Deployment
 * Plugin URI:        https://wp2static.com
 * Description:       AWS GCS deployment add-on for WP2Static.
 * Version:           1.0.0
 * Requires PHP:      7.3
 * Author:            Leon Stafford
 * Author URI:        https://ljs.dev
 * License:           Unlicense
 * License URI:       http://unlicense.org
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'WP2STATIC_GCS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP2STATIC_GCS_VERSION', '1.0.0' );

if ( file_exists( WP2STATIC_GCS_PATH . 'vendor/autoload.php' ) ) {
    require_once WP2STATIC_GCS_PATH . 'vendor/autoload.php';
}

function run_wp2static_addon_gcs() : void {
    $controller = new WP2StaticGCS\Controller();
    $controller->run();
}

register_activation_hook(
    __FILE__,
    [ 'WP2StaticGCS\Controller', 'activate' ]
);

register_deactivation_hook(
    __FILE__,
    [ 'WP2StaticGCS\Controller', 'deactivate' ]
);

run_wp2static_addon_gcs();

