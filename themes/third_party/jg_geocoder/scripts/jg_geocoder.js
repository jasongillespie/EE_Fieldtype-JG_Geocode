/**
*  Geocode by either searching an address or drag/dropping a marker using google's Maps V3 API
*  @author Jason Gillespie
*/


ft_jg_geocoder = {
	
	/**
	* Listen to the address search textarea, and autocomplete the field with google's matching addresses.
	* Listen to the map for a marker drag, and geocode the location at the marker's position
	*
	*/
	listener: function () {
		
		$(".ft_jg_geocoder_address").autocomplete({
			
			// Get address data from google, and display the results in autocomplete
			source: function(request, response) {
				ft_jg_geocoder.geocoder.geocode( {'address': request.term }, function(results, status) {
					response($.map(results, function(item) {
						return {
							label: item.formatted_address,
							address: item.formatted_address,
							latitude: item.geometry.location.lat(),
							longitude: item.geometry.location.lng()
						}
					}));
				})
			},
			
			// When an address is selected, set the hidden field, and address textarea to represent the selected address
			select: function(event, ui) {
				var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
				ft_jg_geocoder.marker.setPosition(location);
				ft_jg_geocoder.map.setCenter(location);
				$(".ft_jg_geocoder_data_points").attr('value', ui.item.latitude+'|'+ui.item.longitude+'|'+ui.item.address);
			}
		});
		
		// Listen to a marker drag, rewrite the location if a result is found
		google.maps.event.addListener(ft_jg_geocoder.marker, "drag", function() {
			ft_jg_geocoder.geocoder.geocode({"latLng": ft_jg_geocoder.marker.getPosition()}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {	
						$(".ft_jg_geocoder_data_points").attr('value', ft_jg_geocoder.marker.getPosition().lat()+'|'+ft_jg_geocoder.marker.getPosition().lng()+'|'+results[0].formatted_address);
						$(".ft_jg_geocoder_address").val(results[0].formatted_address);
					}
				}
			});
		});
	},
	
	/** 
	* Initialize the google map, marker, and geocoder,
	* then listen for events.
	*/
	init: function () {
		
		// Get current data
		var data_points = $(".ft_jg_geocoder_data_points").val().split('|');
		
		// Set the default map center
		var map_center = new google.maps.LatLng(data_points[0],data_points[1]);
		
		// Configure map options
		var map_options = {
			'zoom': 13,
			'center': map_center,
			'mapTypeId': google.maps.MapTypeId.SATELLITE
		};
		
		// Initialize map
		ft_jg_geocoder.map = new google.maps.Map(document.getElementById("ft_jg_geocoder_map_canvas"), map_options);
		
		// Initialize geocoder
		ft_jg_geocoder.geocoder = new google.maps.Geocoder();

		// Initialize marker
		ft_jg_geocoder.marker = new google.maps.Marker({
			'map': ft_jg_geocoder.map,
			'draggable': true
		});
		
		// Set the default marker location at the center of the map
		ft_jg_geocoder.marker.setPosition(map_center);
		
		// Listen for map and address search events
		ft_jg_geocoder.listener();
	}
}

$(document).ready (function () {
	
	ft_jg_geocoder.init();
	
});