<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JG_geocoder_ft extends EE_Fieldtype {
	
	var $info = array(
		'name'		=> 'JG Geocoder',
		'version'	=> '2.0'
	);
	
	// --------------------------------------------------------------------
	
	/**
	 * Display Field on Publish
	 *
	 * @access	public
	 * @param	existing data
	 * @return	field html
	 *
	 */
	function display_field ($data) {
		
		$data_points = array('latitude', 'longitude', 'address');

		if ($data) {
			list($latitude, $longitude, $address) = explode('|', $data);
		}
		else {
			foreach($data_points as $key) {
				$$key = $this->settings[$key];
			}
		}
		
		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="/themes/third_party/jg_geocoder/styles/jg_geocoder.css">');
		$this->EE->cp->add_to_head('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>');
		$this->EE->cp->add_to_head('<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>');
		$this->EE->cp->add_to_head('<script type="text/javascript" src="/themes/third_party/jg_geocoder/scripts/jg_geocoder.js"></script>');
		
		$options = compact($data_points);
		$value = implode('|', array_values($options));
		$hidden_input = form_input($this->field_name, $value, 'id="'.$this->field_name.'" style="display: none;" class="ft_jg_geocoder_data_points"');
		
		$ft_geocoder_address_input = '<p>'.form_label('Search address:', 'ft_jg_geocoder_address').' '.form_input('ft_jg_geocoder_address', $address, 'class="ft_jg_geocoder_address"').'</p>';
		
		return $hidden_input.'<div style="height: 300px;"><div id="ft_jg_geocoder_map_canvas" style="width: 100%; height: 100%"></div></div>'.$ft_geocoder_address_input;
	}
	
	// --------------------------------------------------------------------
		
	/**
	 * Replace Latitude
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_latitude ($data, $params = array(), $tagdata = FALSE) {
		list($latitude, $longitude, $address) = explode('|', $data);
		return $latitude;
	}
	
	/**
	 * Replace Longitude
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_longitude ($data, $params = array(), $tagdata = FALSE) {
		list($latitude, $longitude, $address) = explode('|', $data);
		return $longitude;
	}
	
	/**
	 * Replace Address
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_address ($data, $params = array(), $tagdata = FALSE) {
		list($latitude, $longitude, $address) = explode('|', $data);
		return $address;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Install JG Geocoder Fieldtype
	 *
	 * @access	public
	 * @return	default global settings
	 *
	 */
	function install()
	{
		/**
		* TODO: Make these default variables configurable
		*/
		return array(
			'latitude'	=> '37.23',
			'longitude'	=> '-80.41',
			'address'	=> 'Blacksburg, VA 24060'
		);
	}
	
}

/* End of file ft.jg_geocoder.php */
/* Location: ./system/expressionengine/third_party/jg_geocoder/ft.jg_geocoder.php */