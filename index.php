<!--
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

-->
<html>

<head>
<script type="text/javascript" src="wz_jsgraphics.js"></script>
<script type="text/javascript" src="prototype.js"></script>
<script type="text/javascript" src="painter.js"></script>
<link type="text/css" rel="stylesheet" href="style.css"/>
</head>

<body>
<div>
	<div style="float:left;">
		<div class="tool" onClick="setTool(LINE);"><img src="imgs/line.png"></div>
		<div class="tool" onClick="setTool(RECT);"><img src="imgs/rect.png"></div>
		<div class="tool" onClick="setTool(ELLIPSE);"><img src="imgs/ellipse.png"></div>
		<div class="tool" onClick="setTool(TEXT);">Text</div>

		<div class="hue_container">
			<div class="hue" style="background-color: #fff" onClick="setHue(this.style.backgroundColor);"></div>
			<div class="hue" style="background-color: #000" onClick="setHue(this.style.backgroundColor);"></div>
			<div class="hue" style="background-color: #aaa" onClick="setHue(this.style.backgroundColor);"></div>
			<div class="hue" style="background-color: #444" onClick="setHue(this.style.backgroundColor);"></div>
			<div class="hue" style="background-color: #f00" onClick="setHue(this.style.backgroundColor);"></div>
			<div class="hue" style="background-color: #0f0" onClick="setHue(this.style.backgroundColor);"></div>
			<div class="hue" style="background-color: #00f" onClick="setHue(this.style.backgroundColor);"></div>
			<div class="hue" style="background-color: #ff0" onClick="setHue(this.style.backgroundColor);"></div>
			<div class="hue" style="background-color: #f0f" onClick="setHue(this.style.backgroundColor);"></div>
			<div class="hue" style="background-color: #0ff" onClick="setHue(this.style.backgroundColor);"></div>
			<div style="clear: both;"></div>
		</div>

		<div class="stroke_container">
			<div class="stroke" onClick="setStroke(0);"><img src="imgs/dotted.png"></div>
			<div class="stroke" onClick="setStroke(1);"><img src="imgs/1.png"></div>
			<div class="stroke" onClick="setStroke(3);"><img src="imgs/3.png"></div>
			<div class="stroke" onClick="setStroke(5);"><img src="imgs/5.png"></div>
			<div style="clear: both;"></div>
		</div>

		<div class="tool" onClick="undo();"><img src="imgs/undo.png"></div>

	</div>

	<div id="canvas"></div>

	<div id="tools_container" class="tools_container">
		<div id="image_tools" class="tools" style="border-bottom: 1px dotted lightgray;">
			Image size: <select id="image_size">
				<option value="128x96">128x96</option>
				<option value="256x192">256x192</option>
				<option value="384x288">384x288</option>
				<option value="512x384" selected="selected">512x384</option>
				<option value="640x480">640x480</option>
			</select><br>
		</div>
	

		<div id="text_tools" class="tools" style="display: none;">
			Font: 
			<select id="text_font">
				<option value="arial">Arial</option>
			</select><br>
			Font size: 
			<select id="text_font_size">
				<option value="10">10</option>
			</select><br>
			Enter text here:<br>
			<textarea id="text_text" onChange="setTextBuffer(this.value);"></textarea>
			<input type=button value="Confirm text" onClick="$('MyCanvas').focus();">
		</div>
	</div>
	<div style="clear: both;"></div>
</div>
<div id="messages">&nbsp;</div>
<div id="messages2">&nbsp;</div>
<input type="button" onClick="saveLink();" value="Save and share!">
&nbsp;<input type="button" onClick="clearCanvas();" value="Clear">
<p>
<div id="imageurl_error"></div>
<div id="savedimage" style="display:none;">
	Image link: <span id="loading" style="display:none;"><img src="imgs/wait.gif"></span><br>

	<input id="imageurl" type="text" onClick="this.focus(); this.select();"><br>
	<a id="imageurl_a" href="#">Direct link to this image</a><br>

	<!-- <img id="image" src=""> -->
</div>

<script>
<!--
$("text_text").value = "";
new Painter( 'canvas' );

//-->
</script>

</body>
</html>
