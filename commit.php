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

/**
	The database table is supposed to contain:
	id: integer, auto increment, primary key
	insertion: timestamp
	lastsaw: timestamp
	code: text (hopefully big enough, a line may be around 100 bytes)
*/
include("inc_db.php");

//$rawdata = urldecode( $_SERVER['QUERY_STRING'] );
$data = urldecode( $_POST["vgc"] );

if( $data == null || $data == "" ) {
	header("HTTP/1.1 500 Internal Server Error");
	die("Empty code");
}
$decoded = json_decode( $data, true );
if( $decoded == null || gettype($decoded) != "array" ) {
	header("HTTP/1.1 500 Internal Server Error");
	die("Code error");
} 

connetti_al_db();
$data = mysql_real_escape_string($data);
mysql_query("INSERT INTO paintings VALUES ( NULL, NOW(), NULL, \"$data\" );");
echo mysql_insert_id();

?>
