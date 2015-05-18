<?
/*
    jsPaint v0.1 - a web-based drawing tool
    Copyright (C) 2009 Simone Baracchi

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

include("inc_db.php");

// tool types
define('LINE', 0 );
define('RECT', 1 );
define('ELLIPSE', 2 );
define('TEXT', 3 );

/*
echo strlen( $_SERVER['QUERY_STRING'] );
echo " " . strlen( $_GET['1'] );
echo " " . $_SERVER['QUERY_STRING'];
*/
$index = urldecode( $_SERVER['QUERY_STRING'] );
$index = intval( $index );

connetti_al_db();
$ris_id = mysql_query("SELECT * FROM `paintings` WHERE `id` = $index LIMIT 1");
if( ! $ris_id || mysql_num_rows($ris_id) == 0 ) {
	header('HTTP/1.0 404 Not Found');
	die();
}
$ris = mysql_fetch_array( $ris_id );

$data = json_decode( $ris["code"] );

/*
echo $rawdata . "<br>";
print_r($data);
die();
*/

// TODO
$im_width = 512;
$im_height = 384;

$image = new Imagick();
$image->newImage($im_width, $im_height, "white");
$image->setImageFormat('png');

$draw = new ImagickDraw();
$draw->setFillOpacity(0.0);

// bug?
$pixel = new ImagickPixel();
$pixel->setColor("rgb(1,1,1)");
$draw->setStrokeColor($pixel);


foreach( $data as $index => $instr ) {
	/*
	$draw->setFontSize(52);
	$draw->annotation(20, 50, "Hello World!");
	*/
	$hue = $instr->{'hue'};
	$stroke = $instr->{'stroke'};

	$pixel = new ImagickPixel();
	if( $hue != null ) {
		$pixel->setColor($hue);
		$draw->setStrokeColor($pixel);
	}
	if( $stroke != 0 ) {
		$draw->setStrokeDashArray(array(0));
		$draw->setStrokeWidth($stroke);	
	} else {
		$draw->setStrokeDashArray( array(1,1) );
		$draw->setStrokeWidth(1);			
	}
	if( $instr->{'type'} == LINE )
		$draw->line( $instr->{'x1'}, $instr->{'y1'}, $instr->{'x2'}, $instr->{'y2'} );
	else if( $instr->{'type'} == RECT )
		$draw->rectangle( $instr->{'x1'}, $instr->{'y1'}, $instr->{'x2'}, $instr->{'y2'} );
	else if( $instr->{'type'} == ELLIPSE ) {
		$width = abs( $instr->{'x1'}-$instr->{'x2'} ) / 2;
		$height = abs( $instr->{'y1'}-$instr->{'y2'} ) / 2;
		$draw->ellipse( $instr->{'x1'}+$width, $instr->{'y1'}+$height, $width, $height, 0, 360 );
	} else if( $instr->{'type'} == TEXT ) {
		/*
		echo "<ol>";
		$fonts_obj = new Imagick(); 
		$fonts = $fonts_obj->queryFonts();
		foreach( $fonts as $key=>$var)
		   { echo "<li>$var\n";  }
		echo "</ol>";
		die(  ); 
		*/
		$draw->setStrokeWidth(0.9);
		$draw->setFont('fonts/FreeMono.ttf');
		$draw->setFontSize( 12 );
		$image->annotateimage( $draw, $instr->{'x1'}, $instr->{'y1'}, 0, $instr->{'text'} );
	}

}


$pixel->setColor("white");
$draw->setStrokeColor($pixel);
$draw->setStrokeWidth(0.9);
$draw->setFont('FreeMono.ttf');
$draw->setFontSize( 12 );
$image->annotateimage( $draw, 229, $im_height-2, 0, "http://yesterdaysforecasts.heliohost.org" );
$pixel->setColor("gray");
$draw->setStrokeColor($pixel);
$image->annotateimage( $draw, 230, $im_height-3, 0, "http://yesterdaysforecasts.heliohost.org" );


$image->drawImage($draw);
header('Content-type: image/png');
echo $image;
?>
