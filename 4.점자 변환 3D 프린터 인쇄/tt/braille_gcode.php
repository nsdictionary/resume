<?php
//Let users download the file
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename=braille.gcode');
header('Content-Transfer-Encoding: binary');

//For debugging
//header("Content-Type: text/plain");

//Variables from form: 
$nozzle_diameter = $_POST["nozzle_diameter"]; //in mm
$diameter = $_POST["diameter"]; //in mm
$temperature_extruder = $_POST["temperature_extruder"];//in C
$layer_height = $_POST["layer_height"];//in mm
$layer_height_first = $_POST["layer_height_first"];
$print_speed = $_POST["print_speed"];//mm/minute
$travel_speed = $_POST["travel_speed"];//mm/minute
$height = $_POST["height"];//mm
$letter_width = $_POST["letter_width"];
$pre_extrusion = $_POST["pre_extrusion"];
$margin = $_POST["margin"];
$move_height = $_POST["move_height"];
$drop = $_POST["drop"];;
$retract = $_POST["retract"];
$pause = $_POST["pause"]; //milliseconds
$pause2 = $_POST["pause2"]; //milliseconds
$pause3 = $_POST["pause3"]; //milliseconds
$second_drop_height = $_POST["second_drop_height"];

//Other variables
$text = strtoupper($_POST["text"]);
$text_len = strlen($text);
$extrusion = 0;
$letter_num = 0;

function start(){
	global $temperature_extruder,$print_speed,$travel_speed,$pre_extrusion;
	echo "G21 ; set units to millimeters\n";
	echo "M104 S$temperature_extruder ; set temperature\n";
	echo "G1 Z10 F$travel_speed ; lift nozzle\n";
	echo "G90 ; use absolute coordinates\n";
	echo "G92 E0; set extrusion to zero\n";
	echo "M109 S$temperature_extruder ; wait for temperature to be reached\n";
	echo "G1 E$pre_extrusion ; extrude 5mm\n";
	echo "G90 ; use absolute coordinates\n";
	echo "G92 E0; set extrusion to zero\n";
	echo "M82 ; use absolute distances for extrusion\n";
	//echo "G1 X0.0 Y0.0 Z0.0\n";
	echo "F$print_speed\n";
}
echo ";start code";
start();
echo ";raft code";
raft();
echo ";braille code";
fetch_string($text);
echo ";end code";
echo "G1 X0 Y0 Z50";
function raft(){
	global $nozzle_diameter,$height,$extrusion,$text_len,$letter_width,$layer_height_first;
	if ($layer_height_first>0){
	printf("G1 X0.0 Y0.0 Z%.1f\n",$layer_height_first);
	for($i = 0;$i*$nozzle_diameter<$height;$i++){
		if ($i % 2 == 0){
		printf ("G1 X%.1f Y0.0 E%.1f\n",($i*$nozzle_diameter),$extrusion);
		//calculate the volume needed to print one line of raft.
		$volume = $layer_height_first*$nozzle_diameter*$text_len*$letter_width;
		//calculate the lenght of fillament to fill this volume (v = PI*r^2 * lenght)
		$lenght = $volume/(M_PI*($nozzle_diameter/2)^2);
		$extrusion += $lenght;
		printf ("G1 X%.1f Y%.1f E%.1f\n",($i*$nozzle_diameter),$text_len*$letter_width,$extrusion);
		}else{
		printf ("G1 X%.1f Y%.1f E%.1f\n",($i*$nozzle_diameter),$text_len*$letter_width,$extrusion);
		$extrusion += $lenght;
		printf ("G1 X%.1f Y0.0 E%.1f\n",($i*$nozzle_diameter),$extrusion);
		}
	}
	}
}

function fetch_string($string){
	global $move_height;
	printf ("G1 Z$move_height\n");
	printf ("G1 X0 Y0\n");
	$len = strlen($string);
	for($i=0;$i<$len;$i++){
		//adds one char to the list of chars.
		add_char(substr($string,$i,1));
	}
}
function add_char($char){
	global $letter_num;
	if ($char == "A") {
		letter(
			1,0,
			0,0,
			0,0
		);
	} else if ($char == "B") {
		letter(
			1,0,
			1,0,
			0,0
		);
	} else if ($char == "C") {
		letter(
			1,1,
			0,0,
			0,0
		);
	} else if ($char == "D") {
		letter(
			1,1,
			0,1,
			0,0
		);
	} else if ($char == "E") {
		letter(
			1,0,
			0,1,
			0,0
		);
	} else if ($char == "F") {
		letter(
			1,1,
			1,0,
			0,0
		);
	} else if ($char == "G") {
		letter(
			1,1,
			1,1,
			0,0
		);
	} else if ($char == "H") {
		letter(
			1,0,
			1,1,
			0,0
		);
	} else if ($char == "I") {
		letter(
			0,1,
			1,0,
			0,0
		);
	} else if ($char == "J") {
		letter(
			0,1,
			1,1,
			0,0
		);
	} else if ($char == "K") {
		letter(
			1,0,
			0,0,
			1,0
		);
	} else if ($char == "L") {
		letter(
			1,0,
			1,0,
			1,0
		);
	} else if ($char == "M") {
		letter(
			1,1,
			0,0,
			1,0
		);
	} else if ($char == "N") {
		letter(
			1,1,
			0,1,
			1,0
		);
	} else if ($char == "O") {
		letter(
			1,0,
			0,1,
			1,0
		);
	} else if ($char == "P") {
		letter(
			1,1,
			1,0,
			1,0
		);
	} else if ($char == "Q") {
		letter(
			1,1,
			1,1,
			1,0
		);
	} else if ($char == "R") {
		letter(
			1,0,
			1,1,
			1,0
		);
	} else if ($char == "S") {
		letter(
			0,1,
			1,0,
			1,0
		);
	} else if ($char == "T") {
		letter(
			0,1,
			1,1,
			1,0
		);
	} else if ($char == "U") {
		letter(
			1,0,
			0,0,
			1,1
		);
	} else if ($char == "V") {
		letter(
			1,0,
			1,1,
			1,0
		);
	} else if ($char == "W") {
		letter(
			0,1,
			1,1,
			0,1
		);
	} else if ($char == "X") {
		letter(
			1,1,
			0,0,
			1,1
		);
	} else if ($char == "Y") {
		letter(
			1,1,
			0,1,
			1,1
		);
	} else if ($char == "Z") {
		letter(
			1,0,
			0,1,
			1,1
		);
	} else {
		
	}
	$letter_num += 1;
}
function letter($a1,$a2,$b1,$b2,$c1,$c2){
	global $nozzle_diameter,$height,$extrusion,$text_len,$letter_width,$margin,$letter_num,$drop,$layer_height_first,$layer_height,$move_height,$travel_speed,$retract,$pause,$pause2,$pause3,$second_drop_height;
	printf("G1 F%.1f\n",$travel_speed);
	if ($a1){
		$x = $margin/2;
		$y = ($letter_num * $letter_width) + $margin/2;
		$z = $layer_height + $layer_height_first;
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$z);
		$extrusion += $drop;
		printf("G1 E%.1f\n",$extrusion);
		printf("G4 P$pause\n");
		printf("G1 E%.1f\n",$extrusion-$retract);
		printf("G4 P$pause2\n");
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$second_drop_height);
		printf("G4 P$pause3\n");
		//printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);		
	}
	if ($a2){
		$y = ($letter_num * $letter_width) + ($letter_width-$margin/2);
		$x = $margin/2;
		$z = $layer_height + $layer_height_first;
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$z);
		$extrusion += $drop;
		printf("G1 E%.1f\n",$extrusion);
		printf("G4 P$pause\n");
		printf("G1 E%.1f\n",$extrusion-$retract);
		printf("G4 P$pause2\n");
		printf("G1 F%.1f\n",$travel_speed);
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$second_drop_height);
		printf("G4 P$pause3\n");
		//printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);		
	}
	if ($b1){
		$y = ($letter_num * $letter_width) + $margin/2;
		$x = $margin/2 + ($height - $margin)*1/2;
		$z = $layer_height + $layer_height_first;
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$z);
		$extrusion += $drop;
		printf("G1 E%.1f\n",$extrusion);
		printf("G4 P$pause\n");
		printf("G1 E%.1f\n",$extrusion-$retract);
		printf("G4 P$pause2\n");
		printf("G1 F%.1f\n",$travel_speed);		
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$second_drop_height);
		printf("G4 P$pause3\n");
		//printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);

	}
	if ($b2){
		$y = ($letter_num * $letter_width) + ($letter_width-$margin/2);
		$x = $margin/2 + ($height - $margin)*1/2;
		$z = $layer_height + $layer_height_first;
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$z);
		$extrusion += $drop;
		printf("G1 E%.1f\n",$extrusion);
		printf("G4 P$pause\n");
		printf("G1 E%.1f\n",$extrusion-$retract);
		printf("G4 P$pause2\n");
		printf("G1 F%.1f\n",$travel_speed);		
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$second_drop_height);
		printf("G4 P$pause3\n");
		//printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);		
	}
	if ($c1){
		$y = ($letter_num * $letter_width) + $margin/2;
		$x = $margin/2 + ($height - $margin);
		$z = $layer_height + $layer_height_first;
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$z);
		$extrusion += $drop;
		printf("G1 E%.1f\n",$extrusion);
		printf("G4 P$pause\n");		
		printf("G1 E%.1f\n",$extrusion-$retract);
		printf("G4 P$pause2\n");
		printf("G1 F%.1f\n",$travel_speed);
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$second_drop_height);
		printf("G4 P$pause3\n");
		//printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);		
	}
	if ($c2){
		$y = ($letter_num * $letter_width) + ($letter_width-$margin/2);
		$x = $margin/2 + ($height - $margin);
		$z = $layer_height + $layer_height_first;
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$z);
		$extrusion += $drop;
		printf("G1 E%.1f\n",$extrusion);
		printf("G4 P$pause\n");
		printf("G1 E%.1f\n",$extrusion-$retract);
		printf("G4 P$pause2\n");
		printf("G1 F%.1f\n",$travel_speed);
		printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$second_drop_height);
		printf("G4 P$pause3\n");
		//printf("G1 X%.1f Y%.1f Z%.1f\n",$x,$y,$move_height);		
	}
	
}
?>