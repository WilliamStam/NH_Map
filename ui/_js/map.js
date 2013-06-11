/*
 * Date: 2013/06/11 - 3:00 PM
 */
var geocoder;
var geodesic;
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

var new_users_list = [];


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
		//	map.setCenter(results[0].geometry.location);

			var icon = customIcons[user.type] || customIcons['user'];
			var position = results[0].geometry.location;
			var marker = new google.maps.Marker({
				map     : map,
				position: position,
				icon    : icon.icon,
				shadow  : icon.shadow
			});
			var html = '<b>' + user.name + '</b><hr>' + user.address+"<hr>";

			distAway = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(-23.046241, 29.904656), position)/1000;
			distAway = distAway.toFixed();
			html = html + distAway + "km away";


			$("#list-area table tbody").append('<tr data-distance="'+ distAway+'"><td>'+user.name+'</td><td class="distance">'+distAway+'</td></tr>');
			sort()
			bindInfoWindow(marker, map, infoWindow, html);
		} else {
			console.error('Geocode was not successful for the following reason: ' + status)
		}
	});
}
function calcDistance(p1, p2) {
	return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
}
function bindInfoWindow(marker, map, infoWindow, html) {
	google.maps.event.addListener(marker, 'click', function () {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	});
}
function sort(){
	var $sort = this;
	var $table = $('#list');
	var $rows = $('tbody > tr', $table);
	$rows.sort(function (a, b) {
		var keyA = $(a).attr("data-distance");
		var keyB = $(b).attr("data-distance");
		if ($($sort).hasClass('asc')) {
			return (keyA > keyB) ? 1 : 0;
		} else {
			return (keyA > keyB) ? 1 : 0;
		}
	});
	$.each($rows, function (index, row) {
		$table.append(row);
	});
}

google.maps.event.addDomListener(window, 'load', initialize);