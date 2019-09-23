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

	<h2 id="plaats">Straten in de beeldbank van het Utrechts Archief</h2>
	<div id="plaatsinfo">
	</div>

	<div class="row">
		<div class="col-md-4">
			<p>In de beschrijvingen van beeldbankrecords zochten we naar straatnamen. We hebben alle straatnamen binnen de gemeente Utrecht tegen de beschrijvingen gehouden.</p>

			<p>Buiten Utrecht hebben we het iets anders aangepakt: we hebben eerst met een reguliere expressie gezocht naar -weg, -straat, -laan, etc. en plaatsnaam. Als beiden gevonden werden hebben we vervolgens gekeken of we daar op Wikidata een straat bij konden vinden.</p>

			<p>Om de resultaten te bekijken hebben we twee kaartjes gemaakt.</p>
		</div>
		<div class="col-md-4">
			<a href="utrecht.php"><img src="utrecht.jpg" /></a>
			<p>Binnen Utrecht zijn 110.400 straatvermeldingen gevonden.</p>
		</div>
		<div class="col-md-4">
			<a href="elders.php"><img src="buiten.jpg" /></a>
			<p>Buiten de gemeente Utrecht zijn 9.791 straatvermeldingen gevonden.</p>
		</div>
	</div>
</div>

	



</body>
</html>
