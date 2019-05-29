<?php
/*
Plugin Name: Pet Info
Version: 1.0
Description: Inserts a bunch of information about various pets into the database
Author: Jorie Sieck
Author URI: https://my.thinkeracademy.com
*/
global $pi_db_version;
$pi_db_version = '1.0';

function pi_install() {
    global $wpdb;
    global $pi_db_version;

    $table_name = $wpdb->prefix . 'pet';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
        type tinytext NOT NULL,
        description longtext NOT NULL,
        price bigint(20) NOT NULL,
        PRIMARY KEY (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'pi_db_version', $pi_db_version );
}

function pi_install_data() {
    global $wpdb;
    $names = array('Unicorn', 'Pegasus', 'Pony','Asian dragon','Medieval dragon','Lion','Gryphon');
    $types = array('Horse','Horse','Horse','Dragon','Dragon','Cat','Cat');
    $descriptions = array('Spiral horn centered in forehead','Flying; wings sprouting from back',
        'Very small; half the size of standard horse','Serpentine body','Lizard-like body','Large; maned',
        'Lion body; eagle head; wings');
    $prices = array(10000,15000,500,30000,30000,2000,25000);

    $table_name = $wpdb->prefix . 'pet';
    for($i=0;$i<sizeof($names);$i++) {
        $wpdb->insert(
            $table_name,
            array(
                'name' => $names[$i],
                'type' => $types[$i],
                'description' => $descriptions[$i],
                'price' => $prices[$i],
            )
        );
    }
}

register_activation_hook(__FILE__,'pi_install');
register_activation_hook(__FILE__,'pi_install_data');