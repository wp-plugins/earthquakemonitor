<?php
/*
Plugin Name: Earthquakemonitor Widget
Version: 1.71
Plugin URI: http://wordpress.org/extend/plugins/Earthquakemonitor
Description: Earthquake Monitor is a very customizable widget that shows an overview of earthquakes around the world from the U.S. Geological Surveys data. 
Author: Cris van Geel
Author URI: http://www.yellownote.nl
License: GNU General Public License, version 2
*/

/*  Copyright 2011-2015  Cris van Geel  

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
defined( 'ABSPATH' ) or die( 'No direct access allowed.' );
register_activation_hook( __FILE__, 'earthquake_dbinstall' );

global $earthquake_db_version;
$earthquake_db_version = "1.0";

global $earthquake_widget_version;
$earthquake_widget_version = "v1.71";



	function earthquake_css() {
				wp_register_style( 'earthquakewidget_css', plugins_url('css/style.css', __FILE__) );
				wp_enqueue_style( 'earthquakewidget_css' );
			}
             
	function earthquake_showerror($error) {
		
				$out = '<div class="error" id="messages"><p>';
				$out .= $error;
				$out .= '</p></div>';
				echo $out;
	}

/* Database Work , this replaces the usage of the local tmp folder so its more universal */
function earthquake_dbinstall() {

        global $wpdb;
        global $earthquake_db_version;

        $table_name = $wpdb->prefix.'earthquakewidget';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE `{$table_name}` ( `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `json` MEDIUMTEXT NOT NULL ) {$charset_collate};";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        add_option( 'earthquake_db_version', $earthquake_db_version );
}





class EarthQuakeMonitor extends WP_Widget {
	

	function earthquake_getpluginversion() {
		global $earthquake_widget_version;
		return $earthquake_widget_version;
	}


    function EarthQuakeMonitor() {

        $widget_ops = array('classname' => 'widget_earthquakemonitor', 'description' => __( 'Display earthquakes') );
		parent::WP_Widget('earthquakemonitor', __('Earthquakemonitor'), $widget_ops);
				
    }
	
	
	function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array('feed' => 'significant_month', 
															'noearthquakes' => 'No earthquakes', 
															'showupdateformat' => 'D H:i:s (T)', 
															'displayformat' => 'M{mag} {locreg} {hrtime} ago', 
															'lastupdatetxt' => 'Updated on', 
															'customtitle' => 'Latest earthquakes',
															'filter' => '',
															'eqmcachetimer' => 3600,
															'show' => 5, 
															'showtitle' => true , 
															'linkable' => true , 
															'newwindow' => true , 
															'showalert'=> true,
															'showupdate' => true ));
		
		$earthquakemonitorversion = esc_attr($instance['noearthquakes']);
		$noearthquakes = esc_attr($instance['noearthquakes']);
		$showupdateformat = esc_attr($instance['showupdateformat']);
		$lastupdatetxt = esc_attr($instance['lastupdatetxt']);
		$customtitle = esc_attr($instance['customtitle']);
		$filter = esc_attr($instance['filter']);
		$eqmcachetimer = absint($instance['eqmcachetimer']);
		$feed = esc_attr($instance['feed']);
		$showtitle = (bool) $instance['showtitle'];
		$linkable = (bool) $instance['linkable'];
		$newwindow = (bool) $instance['newwindow'];
		$showupdate = (bool) $instance['showupdate'];
		$showalert = (bool) $instance['showalert'];
		$displayformat = esc_attr($instance['displayformat']);

		$show = absint($instance['show']);
		if ( $show < 1 || 30 < $show ) { $show = 5; }
			
		/* Feed */
		echo "<p><label for='". $this->get_field_id('feed') . "'>" . esc_html__('Earthquake Feed')."</label>";
		echo "<select id='" . $this->get_field_id('feed') . "' name='" . $this->get_field_name('feed') . "'>";
		$value = "1.0_hour"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 1+ (Past hour)</option>";
		$value = "2.5_hour"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 2.5+ (Past hour)</option>";
		$value = "4.5_hour"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 4.5+ (Past hour)</option>";
		$value = "significant_hour"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Significant Earthquakes (Past hour)</option>";
		$value = "1.0_day"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 1+ (Past day)</option>";
		$value = "2.5_day"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 2.5+ (Past day)</option>";
		$value = "4.5_day"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 4.5+ (Past day)</option>";
		$value = "significant_day"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Significant Earthquakes (Past day)</option>";
		$value = "1.0_week"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 1+ (Past 7 days)</option>";
		$value = "2.5_week"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 2.5+ (Past 7 days)</option>";
		$value = "4.5_week"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 4.5+ (Past 7 days)</option>";
		$value = "significant_week"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Significant Earthquakes (Past 7 days)</option>";
		$value = "1.0_month"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 1+ (Past 30 days)</option>";
		$value = "2.5_month"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 2.5+ (Past 30 days)</option>";
		$value = "4.5_month"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 4.5+ (Past 30 days)</option>";
		$value = "significant_month"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Significant Earthquakes (Past 30 days)</option>";
		echo "</select></p>";


		/* Text for No Custom Title */
		echo "<p><label for='" . $this->get_field_id('customtitle') ."'>". esc_html__('Title (leave empty for feed title)')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('customtitle') . "' name='" . $this->get_field_name('customtitle') . "' type='text' value='" . $customtitle . "' /></p>";
				
		/* Text for No Earthquakes */
		echo "<p><label for='" . $this->get_field_id('noearthquakes') ."'>". esc_html__('Text when no earthquakes')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('noearthquakes') . "' name='" . $this->get_field_name('noearthquakes') . "' type='text' value='" . $noearthquakes . "' /></p>";
		
		/* Date Format */
		echo "<p><label for='" . $this->get_field_id('showupdateformat') ."'>". esc_html__('Date format')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('showupdateformat') . "' name='" . $this->get_field_name('showupdateformat') . "' type='text' value='" . $showupdateformat . "' /></p>";
		
		/* Filter */
		echo "<p><label for='" . $this->get_field_id('filter') ."'>". esc_html__('Filter')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('filter') . "' name='" . $this->get_field_name('filter') . "' type='text' value='" . $filter . "' /></p>";
		
		
		/* Last update txt */
		echo "<p><label for='" . $this->get_field_id('lastupdatetxt') ."'>". esc_html__('Last update text')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('lastupdatetxt') . "' name='" . $this->get_field_name('lastupdatetxt') . "' type='text' value='" . $lastupdatetxt . "' /></p>";
		
		/* Cache counter */
		echo "<p><label for='" . $this->get_field_id('eqmcachetimer') ."'>". esc_html__('Cache feed (in seconds)')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('eqmcachetimer') . "' name='" . $this->get_field_name('eqmcachetimer') . "' type='text' value='" . $eqmcachetimer . "' /></p>";
		
		/* Earthquake count */
		echo "<p><label for='". $this->get_field_id('show') . "'>" . esc_html__('No. of earthquakes:')."</label>";
		echo "<select id='" . $this->get_field_id('show') . "' name='" . $this->get_field_name('show') . "'>";
		
		for ( $i = 1; $i <= 30; ++$i ) {
			echo "<option value='$i'" . ( $show == $i ? "selected='selected'" : '' ) . ">$i</option>";
		}
		echo "</select></p>";
	
		/* Show Title */
		echo "<p><label for='" . $this->get_field_id('showtitle') . "'><input id='" . $this->get_field_id('showtitle') . "' class='checkbox' type='checkbox' name='" . $this->get_field_name('showtitle') . "'";
		if ( $showtitle ) {
			echo ' checked="checked"';
		}
		echo " /> " . esc_html__('Show title') . "</label></p>";
	
		/* Linkable? */
		echo "<p><label for='" . $this->get_field_id('linkable') . "'><input id='" . $this->get_field_id('linkable') . "' class='checkbox' type='checkbox' name='" . $this->get_field_name('linkable') . "'";
		if ( $linkable ) {
			echo ' checked="checked"';
		}
		echo " /> " . esc_html__('Make location linkable') . "</label></p>";
	
		/* New Window? */
		echo "<p><label for='" . $this->get_field_id('newwindow') . "'><input id='" . $this->get_field_id('newwindow') . "' class='checkbox' type='checkbox' name='" . $this->get_field_name('newwindow') . "'";
		if ( $newwindow ) {
			echo ' checked="checked"';
		}
		echo " /> " . esc_html__('Open links in new window') . "</label></p>";
	
		/* Show Last Updated */
		echo "<p><label for='" . $this->get_field_id('showupdate') . "'><input id='" . $this->get_field_id('showupdate') . "' class='checkbox' type='checkbox' name='" . $this->get_field_name('showupdate') . "'";
		if ( $showupdate ) {
			echo ' checked="checked"';
		}
		echo " /> " . esc_html__('Show last update') . "</label></p>";

                /* Show PAGER Alert Color */
                echo "<p><label for='" . $this->get_field_id('showalert') . "'><input id='" . $this->get_field_id('showalert') . "' class='checkbox' type='checkbox' name='" . $this->get_field_name('showalert') . "'";
                if ( $showalert ) {
                        echo ' checked="checked"';
                }
                echo " /> " . esc_html__('Show PAGER Alert Color') . "</label></p>";


		
		
		echo "<hr />";
		echo "<h3>Usable variable</h3>";
		echo "<ul>";
		echo "<li>{locdet} Location detailed</li>";
		echo "<li>{locreg} Location region</li>";
		echo "<li>{hrtime} Time past since quake</li>";
		echo "<li>{time} Time of last quake</li>";
		echo "<li>{mag} Magnitude</li>";
		echo "<li>{lat} Latitude</li>";
		echo "<li>{long} Longitude</li>";
		echo "<li>{depth_m} Depth in Metric units</li>";
		echo "<li>{depth_i} Depth in Imperial units</li>";
		echo "</ul>";
		echo "<hr />";
		
		/* Display Format */
		echo "<p><label for='" . $this->get_field_id('displayformat') ."'>". esc_html__('Display format')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('displayformat') . "' name='" . $this->get_field_name('displayformat') . "' type='text' value='" . $displayformat . "' size=2 /></p>";
		
		
	}
	
	
	
	function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['noearthquakes'] = trim( strip_tags( stripslashes( $new_instance['noearthquakes'] ) ) );
			$instance['showupdateformat'] = trim ($new_instance['showupdateformat']);
			$instance['lastupdatetxt'] = trim( strip_tags( stripslashes( $new_instance['lastupdatetxt'] ) ) );
			$instance['customtitle'] = trim( strip_tags( stripslashes( $new_instance['customtitle'] ) ) );
			$instance['filter'] = trim( strip_tags( stripslashes( $new_instance['filter'] ) ) );
			$instance['show'] = absint($new_instance['show']);
			$instance['trim'] = absint($new_instance['trim']);
			$instance['eqmcachetimer'] = absint($new_instance['eqmcachetimer']);
			$instance['showtitle'] = isset($new_instance['showtitle']);
			$instance['linkable'] = isset($new_instance['linkable']);
			$instance['newwindow'] = isset($new_instance['newwindow']);
			$instance['showupdate'] = $new_instance['showupdate'];
			$instance['showalert'] = $new_instance['showalert'];
			$instance['displayformat'] = trim( stripslashes( $new_instance['displayformat'] ) );
			$instance['feed'] = $new_instance['feed'];
			return $instance;
		
	}
		

		
	function earthquake_checkphpversion() {
			if(!version_compare(PHP_VERSION, '5.3', '>=')) {
			 earthquake_showerror('<p>Earthquakemonitor widget requires PHP5.3 or higher. Your server is running '.phpversion().'.</p>');
				}	
			return;
	}
	

	function earthquake_checkjson_decode() {	
		if(!function_exists('json_decode')) {
				earthquake_showerror('Earthquakemonitor plugin requires the PHP function <code>json_decode()</code>.');
				
			}
			return;
	}
	
	public static function earthquake_trunkatecache() {
		global $wpdb;
		$table_name = $wpdb->prefix.'earthquakewidget';
		$wpdb->query('TRUNCATE TABLE '.$table_name);	
		
	}

	
	function earthquake_json($feed,$cachetimer) {
		
		/* See if feed in cache is still valid */
		global $wpdb;
		$table_name = $wpdb->prefix.'earthquakewidget';
		$sql = 'SELECT * FROM  '.$table_name.' WHERE timestamp > DATE_SUB(NOW(),INTERVAL '.$cachetimer.' SECOND)';
		$resultset = $wpdb->get_results($sql);
		
			if (!$resultset[0]->json) {
				
				//Refresh Cache
				$stringJSON = @file_get_contents('http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/'.$feed.'.geojson'); 
				 
				 				 
				 //If the new feed is grabbed succesfully and valid truncate the old cache and renew cache.
				 $decodedJSON = json_decode($stringJSON);
				 
		
				if (!$decodedJSON == false) { 
								 			
					$this->earthquake_trunkatecache();				
					$wpdb->query("INSERT INTO ".$table_name." (`timestamp`, `json`) VALUES (CURRENT_TIMESTAMP, '".addslashes($stringJSON)."');"); 

				 }
				
				
			} else {
				//Read from Cache
				$stringJSON = $resultset[0]->json;
				$decodedJSON = json_decode($stringJSON);
			}
			
			
		
			$this->lastupdate = ($decodedJSON->metadata->generated)/1000;
			$this->maintitle = $decodedJSON->metadata->title;
			return $decodedJSON;
				
			
		
			
			
	}

		
	function widget($args, $instance) {
	
		extract( $args );	
		
		$arrayJSON = $this->earthquake_json($instance['feed'],absint($instance['eqmcachetimer']));
	   	   
		if (!$arrayJSON == FALSE) {
			
			echo "\n<!-- Start EarthQuakeMonitor ".$this->earthquake_getpluginversion()." -->\n";
			echo $before_widget."\n";
					
			if ($instance['showtitle']) 
				{
					if ($instance['customtitle'] <> '') {
					
						echo "{$before_title}".$instance["customtitle"]."{$after_title}\n";
					}
					else {
						echo "{$before_title}".$this->maintitle."{$after_title}\n";
					}	
					
				}
			
			echo "<ul>\n";	
		
			$intCount = $arrayJSON->metadata->count;
			
			

			/* Filter results, if filter is enabled. */
			if ($instance["filter"] <> '') {
				$i = 0;
				while ($i < $intCount) {
						if (!preg_match('/'.$instance["filter"].'/i' ,$arrayJSON->features[$i]->properties->place)) { unset ($arrayJSON->features[$i]); }
						$i++;
				}
				Sort($arrayJSON->features);
				$intCount = count($arrayJSON->features);
			}
				
			if ($intCount == 0) {
				echo "<li>".$instance['noearthquakes']."</li>";
			}
			
			if ($intCount > 0 and $intCount > absint($instance['show']) && absint($instance['show']) <> 0) 
				{ $max = absint($instance['show']); } 
			else 
				{
				  $max = $intCount;
				}
			
			for ($i = 0; $i < $max; $i++) {
					
			/* Format display according display format */
						
			$loc_tmp = explode(",",$arrayJSON->features[$i]->properties->place);
			$locdet = $loc_tmp[0]; 
			$locreg = $loc_tmp[1];

			if ($locreg == '') { $locreg = $locdet; }

			
			
			$mag = $arrayJSON->features[$i]->properties->mag;
			$hrtime = human_time_diff(($arrayJSON->features[$i]->properties->time)/1000,time());
			$time = ($arrayJSON->features[$i]->properties->time)/1000;
			$time = date($instance['showupdateformat'],$time);
			$lat = $arrayJSON->features[$i]->geometry->coordinates[1];
			$long = $arrayJSON->features[$i]->geometry->coordinates[0];
			$depth_m = ($arrayJSON->features[$i]->geometry->coordinates[2]);
			$depth_i = round(substr($depth_m,0,-2) * 0.621371192,1);
			
		
			
			if ($instance['newwindow']) 
				{ $target = "_blank"; } 
			else 
				{ $target = "_top"; }
			

			
			/* Parse user string */
			$display = $instance['displayformat'];
			$variable = array("{locdet}","{locreg}","{mag}","{time}","{lat}","{long}","{lat}","{depth_m}","{depth_i}","{hrtime}");
			$replace = array("{$locdet}","{$locreg}","{$mag}","{$time}","{$lat}","{$long}","{$lat}","{$depth_m}","{$depth_i}","{$hrtime}");
			$parseddisplay = str_replace($variable, $replace, $display);
			
			/* Prepare Pager Alert code if requested */
			if ($instance['showalert']) {
			$alertcolor = $arrayJSON->features[$i]->properties->alert;
			if ($alertcolor == '') { $alertcolor='unknown'; }
			$alert = " class='earthquake_".$alertcolor."'";
			$alert_title = " (PAGER ".$alertcolor.")";
			} else { 
				$alert = "";
				$alert_title = "";
				}
			
				
			if ($instance['linkable'])
				{ echo "<li{$alert}><a target='{$target}' title='".htmlspecialchars($arrayJSON->features[$i]->properties->title,ENT_QUOTES)."{$alert_title}' href='{$arrayJSON->features[$i]->properties->url}'>{$parseddisplay}</a></li>\n"; }
			else {	echo "<li{$alert}>".$parseddisplay."</li>\n"; }
					
			
			}
			
			
			echo "</ul>\n";
			
			if ($instance['showupdate']) {
						
				$date = date($instance['showupdateformat'],$this->lastupdate);
				echo "<strong>".$instance['lastupdatetxt']." {$date}</strong>\n";
			}
			
			echo $after_widget;
			echo "\n<!-- End EarthQuakeMonitor ".$this->earthquake_getpluginversion()." -->\n";		
		}
		else 
		{ 
		  echo "\n<!-- EarthquakeMonitor : Error loading JSON Feed. -->\n";

		}
		
		
	}
}

add_action('updated_option', array('earthquakemonitor','earthquake_trunkatecache'));
add_action('admin_notices', array('earthquakemonitor','earthquake_checkphpversion'));
add_action('admin_notices', array('earthquakemonitor','earthquake_checkjson_decode'));
add_action('widgets_init', 'wickett_earthquakemonitor_widget_init' );
add_action('widgets_init', 'earthquake_css');

	function wickett_earthquakemonitor_widget_init() {
		register_widget('EarthQuakeMonitor');
	}

?>
