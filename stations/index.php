<!DOCTYPE html>
<html>
<head>
	
	<title>HUA stations</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="https://fonts.googleapis.com/css?family=Nunito:300,700" rel="stylesheet">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css" integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet.js" integrity="sha512-mNqn2Wg7tSToJhvHcqfzLMU6J4mkOImSPTxVZAdo+lcPlk+GhZmYgACEe0x35K7YzW1zJ7XyJV/TT1MrdXvMcA==" crossorigin=""></script>

     <link rel="stylesheet" href="../styles.css" />

	
	
</head>
<body>

<div id="bigmap"></div>

<div id="sidebar">
	<h2 id="plaats">Straten Utrechts Archief</h2>
	<div id="plaatsinfo">
	</div>

	<div id="wd">
		<p>Dit kaartje toont de aantallen afbeeldingen in de beeldbank die met een straat zijn verbonden.</p>
	</div>

	<div id="bag">
	</div>
</div>

	

<script>

	

	

	$(document).ready(function(){

		createMap();

		refreshMap();


	});

	function createMap(){
		center = [52.090736, 5.121420];
		zoomlevel = 8;
		
		map = L.map('bigmap', {
	        center: center,
	        zoom: zoomlevel,
	        minZoom: 1,
	        maxZoom: 20,
	        scrollWheelZoom: false
	    });

		L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
			subdomains: 'abcd',
			maxZoom: 19
		}).addTo(map);

		$.ajax({
	        type: 'GET',
	        url: 'sporen.geojson',
	        dataType: 'json',
	        success: function(jsonData) {

	            sporen = L.geoJson(null, {
	            	style: {
							color: '#ffffff',
							fillColor: '#f03',
							weight: 0.5,
					}
				}).addTo(map);

	            sporen.addData(jsonData).bringToBack();
	        },
	        error: function() {
	            console.log('Error loading data');
	        }
	    });

	
	}

	function refreshMap(){

		
		$.ajax({
	        type: 'GET',
	        url: 'stations.geojson',
	        dataType: 'json',
	        success: function(jsonData) {

	            if (typeof herkomsten !== 'undefined') {
				    map.removeLayer(herkomsten);
				}

	            herkomsten = L.geoJson(null, {
	            	pointToLayer: function (feature, latlng) {                    
		                return new L.CircleMarker(latlng, {
		                    color: "#FC3272",
		                    radius:8,
		                    weight: 1,
		                    opacity: 0.8,
		                    fillOpacity: 1
		                });
		            },
				    style: function(feature) {
				        return {
				            color: getColor(feature.properties.cnt),
				            radius: getSize(feature.properties.cnt),
				            clickable: true
				        };
				    },
				    onEachFeature: function(feature, layer) {
						layer.on({
					        click: whenClicked
					    });
				    }
				}).addTo(map);

	            herkomsten.addData(jsonData).bringToFront();
			    

	            //map.fitBounds(herkomsten.getBounds());
	            
	            //$('#straatinfo').html('');
	        },
	        error: function() {
	            console.log('Error loading data');
	        }
	    });
	}



	function getSize(d) {
		return d > 300 ? 10 :
	           d > 160 ? 9 :
	           d > 80  ? 8 :
	           d > 40  ? 7 :
	           d > 20 ? 6 :
	           d > 10  ? 5 :
	           d > 5  ? 4 :
	           d > 0  ? 3 :
	                     3;
	}

	function getColor(d) {
		return d > 160 ? '#a50026' :
	           d > 80 ? '#f46d43' :
	           d > 40  ? '#fdae61' :
	           d > 20  ? '#fee090' :
	           d > 0  ? '#ffffbf' :
	                     '#4575b4';
	}

	function whenClicked(){
		var props = $(this)[0].feature.properties;
		//console.log(props);
		var naam = decodeURIComponent(props['nm']);
		var kopje = naam;

		if(props.cnt==1){
			kopje += ', ' + props['cnt'] + ' rec';
		}else{
			kopje += ', ' + props['cnt'] + ' recs';
		}
		$('#plaats').html(kopje);

		if(props['wd'].length){
			$('#wd').html('<a target="_blank" href="http://www.wikidata.org/entity/' + props['wd'] + '">wikidata: ' + props['wd'] + '</a>');
		}else{
			$('#wd').html('huh');
		}

		if(props['bag'].length){
			$('#bag').html('<a target="_blank" href="https://bag.basisregistraties.overheid.nl/bag/id/openbare-ruimte/' + props['bag'] + '">bagid: ' + props['bag'] + '</a>');
		}else{
			$('#bag').html('');
		}

		if(props['gem'].length){
			$('#bag').html(props['gem']);
		}else{
			$('#bag').html('');
		}
	}

</script>



</body>
</html>
