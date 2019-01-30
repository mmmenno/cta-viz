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
	<select name="term" id="aat-term">
        <option value="reset">Categorie</option>
        <option value="http://vocab.getty.edu/aat/300007836">Bruggen</option>
        <option value="http://vocab.getty.edu/aat/300008203">Speelplaatsen</option>
        <option value="http://vocab.getty.edu/aat/300006122">Riolen</option>
        <option value="http://vocab.getty.edu/aat/300006495">Scholen</option>
        <option value="http://vocab.getty.edu/aat/300266061">Vegetatie</option>
        <option value="http://vocab.getty.edu/aat/300002526">Façades</option>
        <option value="http://vocab.getty.edu/aat/300404856">Wal- en Kademuren</option>
        <option value="http://vocab.getty.edu/aat/300000681">Luchthaven</option>
        <option value="http://vocab.getty.edu/aat/300006187">Pompinstallaties</option>
        <option value="http://vocab.getty.edu/aat/300170875">Sportvelden</option>
        <option value="http://vocab.getty.edu/aat/300000348">Dierentuinen</option>
        <option value="http://vocab.getty.edu/aat/300006443">Elektrische onderstations</option>
        <option value="http://vocab.getty.edu/aat/300007780">Metrostations</option>
        <option value="http://vocab.getty.edu/aat/300006049">Politiebureaus</option>
        <option value="http://vocab.getty.edu/aat/300006676">Ziekenhuizen</option>
        <option value="http://vocab.getty.edu/aat/300000414">Krachtcentrales</option>
	</select>
    <input type="text" placeholder="Search.." name="search">
    <button type="submit"><i class="fa fa-search"></i></button>
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
