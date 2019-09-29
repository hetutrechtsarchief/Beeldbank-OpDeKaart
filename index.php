<!DOCTYPE html>
<html>
<head>
	
	<title>HUA straten</title>

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

     <link rel="stylesheet" href="styles.css" />

	
	
</head>
<body>


<div id="hellothere" class="container">

	<h1 id="plaats">Pilot geocoderen beeldbank Utrechts Archief: straten en stations</h1>
	<div id="plaatsinfo">
	</div>



	<div class="row">
		<div class="col-md-6">
			<p>De beschrijvingen van beeldbankrecords doorzochten we op alle straatnamen binnen de gemeente Utrecht.</p>

			<p>Buiten Utrecht hebben we het anders gedaan: eerst zochten we met een reguliere expressie naar -weg, -straat, -laan, etc. en plaatsnaam. Als beiden gevonden werden hebben we daar op Wikidata een straat bij gezocht.</p>

			
		</div>
		<div class="col-md-6">

			<p>In de beeldbank is ook de fotocollectie van de NS opgenomen. We hebben de beeldbank doorzocht op de namen van <a href="https://w.wiki/93C">stations die in Wikidata opgenomen zijn</a>.</p>

			<p>Om de resultaten te bekijken hebben we drie kaartjes gemaakt.</p>

		</div>
	</div>

	<div class="row">
		<div class="col-md-4">

			<h2>Straten in de gemeente Utrecht</h2>

			<a href="utrecht.php"><img src="utrecht.jpg" /></a>
			<p>In de beeldbank zijn 145.823 vermeldingen gevonden van 2.155 straten binnen de gemeente Utrecht.</p>

		</div>
		<div class="col-md-4">

			<h2>Straten buiten Utrecht</h2>

			<a href="elders.php"><img src="buiten.jpg" /></a>
			<p>Buiten de gemeente Utrecht zijn 9.782 vermeldingen gevonden van 1.303 verschillende straten.</p>

		</div>
		<div class="col-md-4">

			<h2>Stations</h2>

			<a href="stations/"><img src="stations.jpg" /></a>
			<p>Er zijn 1179 stations in Nederland (geweest). Daarvan hebben we er 511 in de beeldbank gevonden op 12.256 afbeeldingen.</p>
		</div>
	</div>
</div>

	



</body>
</html>
