<?php
/*
Plugin Name: Earthquakemonitor Widget
Version: 1.3
Plugin URI: http://wordpress.org/extend/plugins/Earthquakemonitor
Description: Earthquake Monitor is a customizable widget that shows an overview of earthquakes around the world from the U.S. Geological Surveys data. 
Author: Cris van Geel
Author URI: http://www.yellownote.nl
License: GNU General Public License, version 2
*/

/*  Copyright 2011  Cris van Geel  (email : cm.v.geel@gmail.com)

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



class EarthQuakeMonitor extends WP_Widget {
	
    
    function EarthQuakeMonitor() {

        $widget_ops = array('classname' => 'widget_earthquakemonitor', 'description' => __( 'Display earthquakes') );
		parent::WP_Widget('earthquakemonitor', __('Earthquakemonitor'), $widget_ops);
				
    }
	
	
	function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array('feed' => 'eqs7day-M2.5', 
															'noearthquakes' => 'No Earthquakes', 
															'showupdateformat' => 'D H:i:s (T)', 
															'lastupdatetxt' => 'Last update :', 
															'customtitle' => '',
															'filter' => '',
															'eqmcachetimer' => 3600,
															'show' => 5, 
															'trim' => 30, 
															'showtitle' => true , 
															'linkable' => true , 
															'newwindow' => true , 
															'showupdate' => true ));
		
		$noearthquakes = esc_attr($instance['noearthquakes']);
		$showupdateformat = esc_attr($instance['showupdateformat']);
		$lastupdatetxt = esc_attr($instance['lastupdatetxt']);
		$customtitle = esc_attr($instance['customtitle']);
		$filter = esc_attr($instance['filter']);
		$trim = absint($instance['trim']);
		$eqmcachetimer = absint($instance['eqmcachetimer']);
		$feed = esc_attr($instance['feed']);
		$showtitle = (bool) $instance['showtitle'];
		$linkable = (bool) $instance['linkable'];
		$newwindow = (bool) $instance['newwindow'];
		$showupdate = (bool) $instance['showupdate'];

		$show = absint($instance['show']);
		if ( $show < 1 || 30 < $show ) { $show = 5; }
			
		/* Feed */
		echo "<p><label for='". $this->get_field_id('feed') . "'>" . esc_html__('Earthquake Feed')."</label>";
		echo "<select id='" . $this->get_field_id('feed') . "' name='" . $this->get_field_name('feed') . "'>";
		$value = "eqs1hour-M0"; echo "<option value='{$value}'" . ( $feed == $value? "selected='selected'" : '' ) . ">Magnitude 0+ (Past hour)</option>";
		$value = "eqs1hour-M1"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 1+ (Past hour)</option>";
		$value = "eqs1day-M0"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 0+ (Past day))</option>";
		$value = "eqs1day-M1"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 1+ (Past day)</option>";
		$value = "eqs1day-M2.5"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 2.5+ (Past day)</option>";
		$value = "eqs7day-M2.5"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 2.5+ (Past 7 days)</option>";
		$value = "eqs7day-M5"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 5.0+ (Past 7 days)</option>";
		$value = "eqs7day-M7"; echo "<option value='{$value}'" . ( $feed == $value ? "selected='selected'" : '' ) . ">Magnitude 7+ (Past 7 days)</option>";
		echo "</select></p>";

		
		/* Text for No Custom Title */
		echo "<p><label for='" . $this->get_field_id('customtitle') ."'>". esc_html__('Title (leave empty for feed title)')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('customtitle') . "' name='" . $this->get_field_name('customtitle') . "' type='text' value='" . $customtitle . "' /></p>";
		
		/* Filter */
		echo "<p><label for='" . $this->get_field_id('filter') ."'>". esc_html__('Filter (i.e. \'Japan\' Leave empty for no filter)')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('filter') . "' name='" . $this->get_field_name('filter') . "' type='text' value='" . $filter . "' /></p>";
		
		
		/* Text for No Earthquakes */
		echo "<p><label for='" . $this->get_field_id('noearthquakes') ."'>". esc_html__('Text when no earthquakes')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('noearthquakes') . "' name='" . $this->get_field_name('noearthquakes') . "' type='text' value='" . $noearthquakes . "' /></p>";
		
		/* Date Format */
		echo "<p><label for='" . $this->get_field_id('showupdateformat') ."'>". esc_html__('Date format')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('showupdateformat') . "' name='" . $this->get_field_name('showupdateformat') . "' type='text' value='" . $showupdateformat . "' /></p>";
		
		/* Last update txt */
		echo "<p><label for='" . $this->get_field_id('lastupdatetxt') ."'>". esc_html__('Last update text')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('lastupdatetxt') . "' name='" . $this->get_field_name('lastupdatetxt') . "' type='text' value='" . $lastupdatetxt . "' /></p>";
		
		/* Trim count */
		echo "<p><label for='" . $this->get_field_id('trim') ."'>". esc_html__('Trim at char count (0=no trim)')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('trim') . "' name='" . $this->get_field_name('trim') . "' type='text' value='" . $trim . "' /></p>";

		/* Cache counter */
		echo "<p><label for='" . $this->get_field_id('eqmcachetimer') ."'>". esc_html__('Cache feed (in seconds)')."</label>";
		echo "<input class='widefat' id='" . $this->get_field_id('eqmcachetimer') . "' name='" . $this->get_field_name('eqmcachetimer') . "' type='text' value='" . $eqmcachetimer . "' /></p>";

		
		/* Earthquake count */
		echo "<p><label for='". $this->get_field_id('show') . "'>" . esc_html__('Show earthquake count:')."</label>";
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
		echo " /> " . esc_html__('Make linkable') . "</label></p>";
	
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
			$instance['feed'] = $new_instance['feed'];
			return $instance;
		
	}
	

	
	
	function checktempdirectory() {
			if (!is_writeable(sys_get_temp_dir())) {
				$out = '<div class="error" id="messages">';
				$out .= '<p>The PHP Temp directory : '.realpath(sys_get_temp_dir()).' is not writeable. Please set correct permissions.</p>';
				$out .= '</div>';
				echo $out;
			}
			

echo $temp_file;
		}
	
	
	
	function checkphpversion() {
			if(!version_compare(PHP_VERSION, '5.2.1', '>=')) {
			$out = '<div class="error" id="messages">';
			$out .= '<p>Earthquakemonitor plugin requires PHP5.2.1 or higher. Your server is running '.phpversion().'.</p>';
			$out .= '</div>';
			echo $out;
				}	
			return;
	}
	
	function checksimplexml() {	
		if(!function_exists('simplexml_load_file')) {
				$out = '<div class="error" id="messages"><p>';
				$out .= 'Earthquakemonitor plugin requires the PHP function <code>simplexml_load_file()</code>. Your server has this disabled. Please ask your hosting company to enable <code>simplexml_load_file</code>.';
				$out .= '</p></div>';
				echo $out;
			}
			return;
	}
	
	function retrievexml($feed,$cachetimer,$myfilter) {
		
		$filename = sys_get_temp_dir().'/eqmdata';
		if (time()- $cachetimer > filemtime(sys_get_temp_dir()."/eqmdata")) {
		
			/* Refresh Cache */
			
			$stringXML = @file_get_contents('http://earthquake.usgs.gov/earthquakes/catalogs/'.$feed.'.xml'); 
				if ($stringXML == FALSE) {
					
					//If there is an error grabbing the latest RSS Feed get the previous cached one..
					$stringXML = @file_get_contents($filename);
				}
			@file_put_contents( $filename, $stringXML );
		} 
		else {
		
			/* Read from Cache */
			$stringXML = @file_get_contents($filename);

			}

		$tempXML = @simplexml_load_string($stringXML);
		
			if (!$tempXML == false) { 
				$result = $tempXML->xpath("(//channel/item)[contains(., '".$myfilter."')]");
				$this->lastupdate = $tempXML->channel->pubDate;
				$this->maintitle = $tempXML->channel->title;
				return $result;
			}
			else { 	return FALSE;}
		}
		
	function widget($args, $instance) {
	
		extract( $args );	
		
		$arrayXML = $this->retrievexml($instance['feed'],absint($instance['eqmcachetimer']),$instance['filter']);

		if (!$arrayXML == FALSE) {
			
			echo $before_widget;
					
			if ($instance['showtitle']) 
				{
					if ($instance['customtitle'] <> '') {
						echo "{$before_title}".$instance["customtitle"]."{$after_title}";
					}
					else {
						echo "{$before_title}".$this->maintitle."{$after_title}";
					}	
					
				}
			
			echo "<ul>\n";		
			$intCount = count($arrayXML);
			
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
			
			$title = $arrayXML[$i]->title;
				
				if ($instance['trim'] > 0 and strlen($title) > $instance['trim'] ) 
					{
					  $title = substr($title,0,$instance['trim'])."..";
					}
				
				if (!$instance['linkable']) {
					echo "<li>{$title}</li>\n";
				}
				else {
				
				if ($instance['newwindow']) 
					{ $target = "_blank"; } 
				else 
					{ $target = "_top"; };
				
				echo "<li><a target='{$target}' title='{$arrayXML[$i]->description} {$arrayXML[$i]->title} ' href='{$arrayXML[$i]->link}'>{$title}</a></li>\n";
				}
					
			}
			
			echo "</ul>";
			
			if ($instance['showupdate']) {
			
				$tmp_date = strtotime($this->lastupdate);
				$date = date($instance['showupdateformat'],$tmp_date);
				echo $instance['lastupdatetxt']." {$date}\n";
			}
			
			echo $after_widget;
		} 
		
		else 
		{ 
			echo "Feed error in Earthquakedata"; 
		}
		
		
	}
}


add_action('admin_notices', array('earthquakemonitor','checkphpversion'));
add_action('admin_notices', array('earthquakemonitor','checksimplexml'));
add_action('admin_notices', array('earthquakemonitor','checktempdirectory'));
add_action( 'widgets_init', 'wickett_earthquakemonitor_widget_init' );

	function wickett_earthquakemonitor_widget_init() {
		register_widget('EarthQuakeMonitor');
	}

?>