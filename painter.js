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

// pixel type
var TEMP = 0;

// tool types
var LINE = 0;
var RECT = 1;
var ELLIPSE = 2;
var TEXT = 3;

var jg = null;
var oldCoord = null;
var coord = new Object();
var canvas = null;
var cOffset = null;
var canDraw = true;
var behaviour = LINE;
var actionCounter = TEMP + 1;
var textBuffer = "";

var _stroke = 1;
var _hue = "rgb(0,0,0)";

var myLog = new Object();

saveLink = function() {
	$('savedimage').style.display = "";
	var p = Object.toJSON(myLog);
	p = p.replace(/ /g, "");

	//$('messages2').innerHTML = p;
	p = escape(p);
	//$('imageurl').value = p;
	//$('image').setAttribute("src", "view.php?" + p);

	$("loading").style.display="";
	new Ajax.Request('commit.php', {
		parameters: { vgc: p },
		method: "post",
		onSuccess: function(t) {
			$("loading").style.display="none";
			var imagelink = t.responseText;
			$('imageurl_error').innerHTML = "";
			$('imageurl').value = imagelink;
			$('imageurl_a').href = imagelink;
		},
		onFailure: function(t) {
			$("loading").style.display="none";
			$('imageurl_error').innerHTML = "Error: " + t.responseText;
			$('imageurl').value = "";
			$('imageurl_a').href = "";
		}
	});
}

clearCanvas = function() {
	jg.clear();
	actionCounter = TEMP + 1;
	myLog = new Object();
}

setHue = function( hue ) {
	jg.setColor(hue);
	_hue = hue;
}
setStroke = function( stroke ) {
	if( stroke == 0 )
		jg.setStroke( Stroke.DOTTED );
	else
		jg.setStroke( stroke );
	_stroke = stroke;
}

toWH = function( x, y, x2, y2, fhandle ) {
	var width = x2 - x;
	var height = y2 - y;
	if( width < 0 ) {
		x = x + width;
		width = -width;
	}
	if( height < 0 ) {
		y = y + height;
		height = -height;
	}
	fhandle = fhandle.bind(jg);
	fhandle( x, y, width, height );
}


drawLine = function( counter, x, y, x2, y2 ) {
	jg.drawLine( x, y, x2, y2 );
	jg.paint();
	markType(counter);
	if( counter != 0 )
		myLog[counter] = {
			type: "" + LINE,
			hue: _hue,
			stroke: "" + _stroke,
			x1: "" + x,
			y1: "" + y,
			x2: "" + x2,
			y2: "" + y2
		};
}
drawRect = function( counter, x, y, x2, y2 ) {
	toWH( x, y, x2, y2, jg.drawRect );
	jg.paint();
	markType(counter);
	if( counter != 0 )
		myLog[counter] = {
			type: "" + RECT,
			hue: _hue,
			stroke: "" + _stroke,
			x1: "" + x,
			y1: "" + y,
			x2: "" + x2,
			y2: "" + y2
		};
}
drawEllipse = function( counter, x, y, x2, y2 ) {
	toWH( x, y, x2, y2, jg.drawEllipse );
	jg.paint();
	markType(counter);
	if( counter != 0 )
		myLog[counter] = {
			type: "" + ELLIPSE,
			hue: _hue,
			stroke: "" + _stroke,
			x1: "" + x,
			y1: "" + y,
			x2: "" + x2,
			y2: "" + y2
		};
}

drawText = function( counter, x, y, string ) {
	jg.drawString( string, x, y );
	jg.paint();
	markType(counter);
	if( counter != 0 )
		myLog[counter] = {
			type: "" + TEXT,
			hue: _hue,
			stroke: "" + _stroke,
			x1: "" + x,
			y1: "" + y,
			text: string
		};
}


undo = function() {
	if( actionCounter > TEMP+1 ) actionCounter --;
	delete myLog[actionCounter];
	removeType(actionCounter);
}

drawPreview = function() {
	if( oldCoord == null && behaviour != TEXT )
		return;
	if( canDraw == true ) {
		removeType(TEMP);
		if( behaviour == LINE ) {
			drawLine( TEMP, oldCoord[0], oldCoord[1], coord[0], coord[1] );
		} else if( behaviour == RECT ) {
			drawRect( TEMP, oldCoord[0], oldCoord[1], coord[0], coord[1] );
		} else if( behaviour == ELLIPSE ) {
			drawEllipse( TEMP, oldCoord[0], oldCoord[1], coord[0], coord[1] );
		} else if( behaviour == TEXT ) {
			drawText( TEMP, coord[0], coord[1], textBuffer );
		}
		// set a timeout
		canDraw = false;
		setTimeout( "canDraw = true; drawPreview();", 100 );
	}
}

/*
handleKey = function( event ) {
	if( behaviour == TEXT ) {
		removeType(TEMP);
		//if( event.keyCode == Object.KEY_BACKSPACE )
		if( event.keyCode == 8 )
			textBuffer = textBuffer.slice( 0, textBuffer.length-1 );
		else {
			var code = event.which;
			var character = String.fromCharCode( code );
			if(code >= 33 && code <= 249) 
				textBuffer += character;
			$("messages2").innerHTML += " " + code;
		}
		drawPreview();
	}
}
*/

handleClick = function(event) {
	removeType(TEMP);
	if( oldCoord == null && behaviour != TEXT ) {
		oldCoord = new Object();
		oldCoord[0] = coord[0];
		oldCoord[1] = coord[1];
	} else {
		if( behaviour == LINE ) {
			drawLine( actionCounter, oldCoord[0], oldCoord[1], coord[0], coord[1] );
		} else if( behaviour == RECT ) {
			drawRect( actionCounter, oldCoord[0], oldCoord[1], coord[0], coord[1] );
		} else if( behaviour == ELLIPSE ) {
			drawEllipse( actionCounter, oldCoord[0], oldCoord[1], coord[0], coord[1] );
		} else if( behaviour == TEXT ) {
			drawText( actionCounter, coord[0], coord[1], textBuffer );
		}
		actionCounter++;

		oldCoord = null;
	}
}

handleMouseMove = function(event) {
	coord[0] = event.pointerX() - cOffset[0];
	coord[1] = event.pointerY() - cOffset[1];
	drawPreview();
}

var Painter = Class.create({
	initialize: function( element ) {
		// Operations on the "outer" canvas
		canvas = $(element);
		cOffset = canvas.positionedOffset();
		jg = new jsGraphics(canvas);
		jg.setColor("#000000");
		canvas.observe('mousemove', handleMouseMove );
		canvas.observe('click', handleClick );
		//document.observe('keypress', handleKey );

		// switch to the "inner" canvas
		markType("MyCanvas");
		canvas = $("MyCanvas");	
	}
});

removeType = function( type ) {
	for( j=0; j<7; j++ ) {
		for( i=0; i< canvas.childNodes.length; i++ ) {
			if( canvas.childNodes[i].id == type ) {
				Element.remove(canvas.childNodes[i]);
			}
		}
	}
}

markType = function( type ) {
	for( i=0; i< canvas.childNodes.length; i++ ) {
		if( canvas.childNodes[i].id == "" ) {
			canvas.childNodes[i].id = type;
		}
	}
}


setTool = function( tool ) {
	behaviour = tool;
	if( tool == TEXT )
		$("text_tools").style.display="";
	else
		$("text_tools").style.display="none";
}
setTextBuffer = function( string ) {
	textBuffer = string;
}
