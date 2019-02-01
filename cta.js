	
	var streeturi = "";

	var center = [52.368716,4.900029];
	var zoomlevel = 13;
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

		//$('#resultaten h1').html('klik op een straat om dossiers te zien');
		//$('#dossiers').html('');

		if (typeof streets !== 'undefined') {
		    map.removeLayer(streets);
		}

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

		parameters['start'] = $('#fromyear').val();
		parameters['end'] = $('#untilyear').val();
		parameters['term'] = $('#aat-term').val();
		parameters['searchterms'] = $('#search-terms').val();
		
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
		//console.log(props);
		$('#resultaten h1').html(props['name'] + '');
		streeturi = props['street'];
		loadDossiers();
	}

	function loadDossiers(){
		var parameters = {};
		parameters['street'] = streeturi;
		parameters['start'] = $('#fromyear').val();
		parameters['end'] = $('#untilyear').val();
		parameters['term'] = $('#aat-term').val();
		parameters['searchterms'] = $('#search-terms').val();
		
		var params = $.param(parameters,true);
		$('#dossiers').load('dossiers.php?' + params);
	}

	$('form').submit(function( event ) {
		refreshMap();
		if(streeturi!=""){
			loadDossiers();
		}
		event.preventDefault();
	});

	$(document).ready(function(){

		showYears();
		refreshMap();

        $('#fromyear').on('input',function(){
        	showYears();
        });

        $('#untilyear').on('input',function(){
        	showYears();
        });

        $('#fromyear').on('change',function(){
        	$('form').submit();
        });
		$('#untilyear').on('change',function(){
        	$('form').submit();
        });
        $('#aat-term').on('change',function(){
        	$('form').submit();
        });
        $('#search-terms').on('change',function(){
        	$('form').submit();
        });



	});

	function showYears(){
		var fromyear = $('#fromyear').val();
		var untilyear = $('#untilyear').val();
		
		$('#from').text(fromyear);
		$('span#until').text(untilyear);
	}