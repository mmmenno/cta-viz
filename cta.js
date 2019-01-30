
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

		parameters['start'] = 1880; //$('#startyear').val();
		parameters['end'] = 1890; //$('#endyear').val();
		parameters['term'] = "http://vocab.getty.edu/aat/300006122";
		
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

	// nice globals!
	var searchData = {
	    'street': '',
	    'start': 0,
        'end': 0,
        'term': ''
    };

	function whenStreetClicked(e) {
    	var props = e['target']['feature']['properties'];
		//console.log(props);
		$('#resultaten h1').html(props['name'] + '');

		searchData.street = props['street'];
		loadDossiers();
	}

	function loadDossiers() {
        var q = 'var=1';
        if (searchData.street.length > 1) {
            q += '&street='+searchData.street;
        }
        if (searchData.start > 0) {
            q += '&start='+searchData.start;
        }
        if (searchData.end > 0) {
            q += '&end='+searchData.end;
        }
        if (searchData.term.length > 1) {
            q += '&term='+searchData.term;
        }

        $('#dossiers').load('dossiers.php?' + q);
    }

	$('form').submit(function( event ) {
		refreshMap();
		event.preventDefault();
	});

	$(document).ready(function(){
		refreshMap();

        $('#aat-term').on('change', function() {
            if (this.value == 'reset') {
                searchData.term = '';
            } else {
                searchData.term = this.value;
            }
            loadDossiers();
        });

        $('#reset-straat').on('click', function() {
            searchData.street = '';
            loadDossiers();
        });
	});