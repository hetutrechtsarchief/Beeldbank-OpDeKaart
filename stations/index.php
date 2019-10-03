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

	<script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.js"></script>

  <link rel="stylesheet" href="../scroller.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.css" />
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.js"></script>
  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
</head>
<body>

<div id="bigmap"></div>

<div id="sidebar">
	<h2 id="plaats">Stations in de beeldbank van Het Utrechts Archief</h2>
	<div id="plaatsinfo">
	</div>

	<p>Dit kaartje toont de aantallen afbeeldingen in de beeldbank die met een station zijn verbonden.</p>

	<div id="bag">
  </div>

  <div id="wd">
  </div>

  <a id="sparqlTip" href="https://druid.datalegend.net/HetUtrechtsArchief/beeldbank/">Tip: Query zelf deze dataset met SPARQL</a>

  <label id="lblOnlineOnly"><input id="chkOnlineOnly" checked type="checkbox">Toon alleen afbeeldingen die ik online kan bekijken</label>

  <div class="container"></div>
  <p class="einde">Einde...</p>

  <div class="page-load-status">
    <div class="loader-ellips infinite-scroll-request">
      <img src="../loading.gif">
    </div>
    <p class="infinite-scroll-last">End of content</p>
    <p class="infinite-scroll-error">No more pages to load</p>
  </div>

</div>

	<a href="../"><img id="btnBack" src="../back.gif"></a>

<!-- .photo-item template HTML -->
<script type="text/html" id="photo-item-template">
  <div class="photo-item" id="{{guid}}">
    <a data-fancybox="gallery" data-caption="<h2>{{description}}</h2>Datum: {{beginTimeStamp}} - {{endTimeStamp}}<br/>Licentie: {{rights}}<br/><a target='_blank' href='https://hetutrechtsarchief.nl/collectie/beeldmateriaal/catalogusnummer/{{catalogusnummer}}'>https://hetutrechtsarchief.nl/collectie/beeldmateriaal/catalogusnummer/{{catalogusnummer}}</a>" 
        href="https://proxy.archieven.nl/download/39/{{guid}}">
      <img class="photo-item__image" title="{{description}}" src="https://proxy.archieven.nl/thumb/39/{{guid}}"/>
    </a>
  </div>
</script>

<script>

	$(document).ready(function(){
		createMap();
		refreshMap();
		window.apiBase = '../sparql.php?wikidataID=';
	});

	function createMap(){
		center = [52.090736, 5.121420];
		zoomlevel = 8;
		
		map = L.map('bigmap', {
	        center: center,
	        zoom: zoomlevel,
	        minZoom: 1,
	        maxZoom: 20,
	        scrollWheelZoom: true,
          zoomControl: false
      });

    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

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
					        click: whenClicked,
                  mouseover: rollover,
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

	function rollover() {
    var props = $(this)[0].feature.properties;
    this.bindPopup(props["nm"]);
    this.openPopup();
    var self = this;
    setTimeout(function() {
      self.closePopup();
    },1500);
  }

	function whenClicked(){
		$(".container").empty();
    $("#intro").hide();

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

		console.log('props',props);

		if(props['wd'].length){
			window.wikidataID = props['wd'];
      initScroller();
			$('#wd').html('<a target="_blank" href="http://www.wikidata.org/entity/' + props['wd'] + '">wikidata: ' + props['wd'] + '</a>');
		}else{
			$('#wd').html('huh');
		}	


		// if(props['wd'].length){
		// 	$('#wd').html('<a target="_blank" href="http://www.wikidata.org/entity/' + props['wd'] + '">wikidata: ' + props['wd'] + '</a>');
		// }else{
		// 	$('#wd').html('huh');
		// }

		// if(props['bag'].length){
		// 	$('#bag').html('<a target="_blank" href="https://bag.basisregistraties.overheid.nl/bag/id/openbare-ruimte/' + props['bag'] + '">bagid: ' + props['bag'] + '</a>');
		// }else{
		// 	$('#bag').html('');
		// }

		// if(props['gem'].length){
		// 	$('#bag').html(props['gem']);
		// }else{
		// 	$('#bag').html('');
		// }
	}

</script>

<script src="../scroller.js"></script>

</body>
</html>
