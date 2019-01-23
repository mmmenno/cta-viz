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



<script src="cta.js"></script>



</body>
</html>
