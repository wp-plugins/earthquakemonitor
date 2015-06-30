<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

delete_option( "widget_earthquakemonitor" );
delete_option( "earthquake_db_version");

// For site options in multisite
delete_site_option( "widget_earthquakemonitor");  
delete_site_option( "earthquake_db_version");  

//drop a custom db table
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."earthquakewidget" );

?>