<?php
/*
Plugin Name: Pet Info
Version: 2.0
Description: Inserts a bunch of information about various pets into the database
Author: Jorie Sieck
Author URI: https://my.thinkeracademy.com
*/
global $pi_db_version;
$pi_db_version = '1.0';

// Activation hook: calls function when a particular page loads
add_action('genesis_before_content','pi_save_data');
/*
 * Puts the given data in the previously created table.
 */
function pi_save_data() {
    $page_id = 30;
    if(is_page($page_id)) {
        global $wpdb;
        $names = array('Unicorn', 'Pegasus', 'Pony','Asian dragon','Medieval dragon','Lion','Gryphon');
        $types = array('Horse','Horse','Horse','Dragon','Dragon','Cat','Cat');
        $descriptions = array('Spiral horn centered in forehead','Flying; wings sprouting from back',
            'Very small; half the size of standard horse','Serpentine body','Lizard-like body','Large; maned',
            'Lion body; eagle head; wings');
        $prices = array(10000,15000,500,30000,30000,2000,25000);

        $table_name = $wpdb->prefix . 'pet';
        $used_js = array();
        for($i=0;$i<sizeof($names);$i++) {
            $j = rand(0,sizeof($names)-1);
            while(in_array($j,$used_js)) {
                $j = rand(0,sizeof($names));
            }
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $names[$j],
                    'type' => $types[$j],
                    'description' => $descriptions[$j],
                    'price' => $prices[$j],
                )
            );
            array_push($used_js,$j);
        }
    }
}

// Activation hook: calls function on plugin activation
register_activation_hook(__FILE__,'pi_install');
/*
 * Creates an empty table in the database called wp_pet with the given columns.
 */
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