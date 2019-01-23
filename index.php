<!DOCTYPE html>
<html>
<head>
	
	<title>CTA</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<script
	  src="https://code.jquery.com/jquery-3.2.1.min.js"
	  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	  crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css" integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw==" crossorigin=""/>
    
    <script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet.js" integrity="sha512-mNqn2Wg7tSToJhvHcqfzLMU6J4mkOImSPTxVZAdo+lcPlk+GhZmYgACEe0x35K7YzW1zJ7XyJV/TT1MrdXvMcA==" crossorigin=""></script>

    <link rel="stylesheet" href="styles.css" />

	
</head>
<body>



<div id="inleiding">
	<h1>Centraal Tekeningen Archief</h1>

	<input type="range" min="1" max="100" value="50" class="slider" id="myRange">
</div>


<div id="map"></div>


<div id="resultaten">

	<h1>klik op een straat om dossiers te zien</h1>

	<div id="dossiers">

	</div>

</div>



<script>

	var center = [52.359716,4.900029];
	var zoomlevel = 15;
	var map = L.map('map', {
        center: center,
        zoom: zoomlevel,
        minZoom: 6,
        maxZoom: 20,
        scrollWheelZoom: false
    });

	L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}{r}.{ext}', {
	    attribution: 'Tiles <a href="http://stamen.com">Stamen Design</a> - Data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	    subdomains: 'abcd',
		minZoom: 0,
		maxZoom: 20,
		ext: 'png'
	}).addTo(map);

	map.on('moveend', function(e) {
	    $("#inbbox").prop('checked', true);
	    refreshMap(); 
	});

	function refreshMap(){

		streets = L.geoJson(null, {
		    style: function(feature) {
		        return {
		            color: getColor(feature.properties.count),
		            weight: 5,
		            opacity: 1,
		            clickable: true
		        };
		    },
		    onEachFeature: function(feature, layer) {
				layer.on({
			        click: whenStreetClicked
			    });
		    }
		}).addTo(map);

		var parameters = {};
		var bounds = map.getBounds();
		parameters['bbox'] = bounds['_southWest']['lng'] + ' ' + bounds['_northEast']['lat'];
		parameters['bbox'] += ',' + bounds['_northEast']['lng'] + ' ' + bounds['_northEast']['lat'];
		parameters['bbox'] += ',' + bounds['_northEast']['lng'] + ' ' + bounds['_southWest']['lat'];
		parameters['bbox'] += ',' + bounds['_southWest']['lng'] + ' ' + bounds['_southWest']['lat'];
		parameters['bbox'] += ',' + bounds['_southWest']['lng'] + ' ' + bounds['_northEast']['lat'];
		
		var params = $.param(parameters,true);
		geojsonfile = 'geojson.php?' + params;

	    $.getJSON(geojsonfile, function(data) {
	        streets.addData(data).bringToFront();
	    });

	}

	function getColor(d) {
	    return d > 320 ? '#800026' :
	           d > 160 ? '#BD0026' :
	           d > 80  ? '#E31A1C' :
	           d > 40  ? '#FC4E2A' :
	           d > 20  ? '#FD8D3C' :
	           d > 10  ? '#FEB24C' :
	           d > 5   ? '#FED976' :
	                     '#FFEDA0';
	}

	function whenStreetClicked(e) {
    	var props = e['target']['feature']['properties'];
		console.log(props);

		$('#resultaten h1').html(props['name'] + ', ' + props['count'] + ' dossiers');
	  	
	  	$('#dossiers').load('dossiers.php?street=' + props['street']);

	}

	$(document).ready(function(){
		refreshMap();
	});

</script>



</body>
</html>
