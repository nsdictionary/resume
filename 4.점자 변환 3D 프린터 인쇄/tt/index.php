<?error_reporting(0);?><head><!-- Optional theme --><!-- Latest compiled and minified JavaScript --><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script><script src="//code.jquery.com/jquery-1.11.0.min.js"></script><script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script><style>body{	width: 600px;	margin-left: auto;	margin-right: auto;	background-color: #34495E;	font-family: sans-serif;}.description{	display: inline-block;	width: 200px;}@font-face {    font-family: "Braille";    src: url("BRAILLE.ttf");}.braille_preview{	font-family: "Braille";	font-size: 30pt;	white-space: pre;}.input_row{	/*height:30px;*/	display:block}.top{padding: 10px;	color: white;}.middle{padding: 10px;	margin-top: 0px; background-color: #95A5A6;}.bottom{padding: 10px;color: white;}.bottom a{color: white;}</style></head><form action="braille_gcode.php" method="post"><div class="top"><p>Preview (this preview is without the dimension settings):</p><p class="braille_preview"></p></div><div class="middle"><span class="input_row">
<span class="description">nozzle_diameter</span>
	<input name="nozzle_diameter" value="0.5" /> <span class="unit"> in mm</span></span><span class="input_row">
<span class="description">
diameter </span>
	<input name="diameter" value="3" type="number" step="0.001" /> <span class="unit"> in mm</span></span><span class="input_row">
<span class="description">
temperature_extruder</span>
	<input name="temperature_extruder" value="210" type="number" step="1"/><span class="unit"> in C</span></span><span class="input_row">
<span class="description">
layer_height</span>
	<input name="layer_height" value="0.7" type="number" step="0.001"/><span class="unit"> in mm</span></span><span class="input_row">
<span class="description">
layer_height_first</span>
	<input name="layer_height_first" value="0" type="number" step="0.001" /><span class="unit"> raft (if 0, no raft will be printed)</span></span><span class="input_row">
<span class="description">
print_speed</span>
	<input name="print_speed" value="1500" type="number" step="1" /><span class="unit"> mm/minute</span></span><span class="input_row">
<span class="description">
travel_speed</span>
	<input name="travel_speed" value="1500" type="number" step="1"/><span class="unit"> mm/minute</span></span><span class="input_row">
<span class="description">
height</span>
	<input name="height" value="10" type="number" step="0.001"/><span class="unit"> mm</span></span><span class="input_row">
<span class="description">
letter_width</span>
	<input name="letter_width" value="7.5" type="number" step="0.001"/></span><span class="input_row">
<span class="description">
text</span>
	<input name="text" value="this is awesome" class="text" type="text"/></span><span class="input_row">
<span class="description">
pre extrusion</span>
	<input name="pre_extrusion" value="0" type="number" step="0.001"/></span><span class="input_row">
<span class="description">
margin</span>
	<input name="margin" value="5" type="number" step="0.001"/></span><span class="input_row">
<span class="description">
move_height</span>
	<input name="move_height" value="4" type="number" step="0.001"/></span><span class="input_row">
<span class="description">
drop</span>
	<input name="drop" value="0.4" type="number" step="0.001"/></span><span class="input_row">
<span class="description">
retract</span>
	<input name="retract" value="4" type="number" step="0.001"/></span><span class="input_row">
<span class="description">
pause</span>
	<input name="pause" value="1000" type="number" step="0.001"/> <span class="unit"> milliseconds</span></span><span class="input_row">
<span class="description">pause2</span>
	<input name="pause2" value="0" type="number" step="0.001"/> <span class="unit"> milliseconds</span></span><span class="input_row">
<span class="description">pause3</span>
	<input name="pause3" value="0" type="number" step="0.001"/> <span class="unit"> milliseconds</span>	</span><span class="input_row">	<span class="description">second_drop_height</span>	<input name="second_drop_height" value="1.4" type="number" step="0.001"/> <span class="unit"> mm</span></span>
<span class="description"><input value="download" type="submit" /></div><div class="bottom">CC-BY-SA Geert RoumenSee also <a href="http://www.thingiverse.com/thing:503408">thingiverse</a>, source code</div></form><script>$( ".text" )  .keyup(function() {    var value = $( this ).val();    $( ".braille_preview" ).text( value );  })  .keyup();</script>