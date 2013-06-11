/*
 * Date: 2013/06/11 - 3:00 PM
 */
var geocoder;
var map;

var customIcons = {
	user: {
		icon  : 'http://labs.google.com/ridefinder/images/mm_20_green.png',
		shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
	},
	mod : {
		icon  : 'http://labs.google.com/ridefinder/images/mm_20_orange.png',
		shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
	},
	vip : {
		icon  : 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
		shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
	}
};



var infoWindow = new google.maps.InfoWindow;
function initialize() {
	geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(-23.046241, 29.904656);
	var mapOptions = {
		zoom     : 5,
		center   : latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

	for (var i = 0; i < users.length; i++) {
		codeAddress(users[i])
	}
}

function codeAddress(user) {
	var address = user.address;
	geocoder.geocode({ 'address': address}, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);

			var icon = customIcons[user.type] || customIcons['user'];

			var marker = new google.maps.Marker({
				map     : map,
				position: results[0].geometry.location,
				icon    : icon.icon,
				shadow  : icon.shadow
			});
			var html = '<b>' + user.name + '</b><hr>' + user.address;
			bindInfoWindow(marker, map, infoWindow, html);
		} else {
			console.error('Geocode was not successful for the following reason: ' + status)
		}
	});
}

function bindInfoWindow(marker, map, infoWindow, html) {
	google.maps.event.addListener(marker, 'click', function () {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	});
}

google.maps.event.addDomListener(window, 'load', initialize);