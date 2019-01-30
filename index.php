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
	<select>
  		<option value="Categorie">Categorie</option>
  		<option value="Bruggen">Bruggen</option>
  		<option value="Speelplaatsen">Speelplaatsen</option>
  		<option value="Riolen">Riolen</option>
  		<option value="Scholen">Scholen</option>
  		<option value="Vegetatie">Vegetatie</option>
  		<option value="Façades">Façades</option>
  		<option value="Dokken">Dokken</option>
  		<option value="Luchthaven">Luchthaven</option>
  		<option value="Pompinstallaties">Pompinstallaties</option>
  		<option value="Sportvelden">Sportvelden</option>
  		<option value="Dierentuinen">Dierentuinen</option>
  		<option value="Elektrische onderstations">Elektrische onderstations</option>
  		<option value="Metrostations">Metrostations</option>
  		<option value="Politiebureaus">Politiebureaus</option>
  		<option value="Ziekenhuizen">Ziekenhuizen</option>
  		<option value="Krachtcentrales">Krachtcentrales</option>
	</select>
    <input type="text" placeholder="Search.." name="search">
    <button type="submit"><i class="fa fa-search"></i></button>
</div>


<div id="map"></div>

<div class='my-legend'>
<div class='legend-title'>Aantal dossiers per straat</div>
<div class='legend-scale'>
  <ul class='legend-labels'>
    <li><span style='background:#FFEDA0;'></span> >0</li>
    <li><span style='background:#FED976;'></span> >5</li>
    <li><span style='background:#FEB24C;'></span> >10</li>
    <li><span style='background:#FD8D3C;'></span> >20</li>
    <li><span style='background:#FC4E2A;'></span> >40</li>
    <li><span style='background:#E31A1C;'></span> >80</li>
    <li><span style='background:#BD0026;'></span> >160</li>
    <li><span style='background:#800026;'></span> >320</li>
  </ul>
</div>

</div>



<div id="resultaten">

	<h1>klik op een straat om dossiers te zien</h1>

	<div id="dossiers">

	</div>

</div>



<script src="cta.js"></script>



</body>
</html>
