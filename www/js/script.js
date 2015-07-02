'use strict';

var moveSelectionFlag = false;
var typeOfNewState = 1;
var patternName = 'default0';
var pauseFlag = false;
var touchDeviceFlag = false;
var background = '';

(function(){ 

// set states to HTML
function setCellsView(cells, states, background){
	var len = cells.length;

	for(var i = 0; i < len; i ++){
		var cell = cells.eq(i);
		cell.attr('state', states[i]);
		var outline = '';
		if(cell.is('.outline'))
			outline = ' outline';
		cell.attr('class', 'cell state' + states[i] + outline);
		if(states[i] == 0)
			cell.css('background-color', '#' + background);  
	}
}

function setSelectionView(selection, position){
	var grid = $('.grid').offset();
	var left = Math.floor((position.x)) * 27 + grid.left - 11; 
	var top = Math.floor((position.y)) * 27 + grid.top - 11;
	selection.offset({left: left, top: top});
}

// get cells state from server
function getData(){
	// cells
	$.post('homepage/getCells')
	.done(function(data){
		var cells = $('.cell');
		var selection = $('.handle');

    background = data.background;
		setCellsView(cells, data.cells, data.background); // cells
		if(!moveSelectionFlag)
			setSelectionView(selection, data.selection);
		
		// background
		var bgElement = $('#background option[value="' + data.background + '"]');
	});
}

function moveSelection(event, state, position){
	if(state){
		var handle = $('.handle'); // handle element
		var grid = $('.grid'); // grid element
		var left = Math.floor((event.pageX - handle.offset().left) / 27) * 27 + handle.offset().left; 
		var top = Math.floor((event.pageY - handle.offset().top) / 27) * 27 + handle.offset().top;

		if(left < grid.offset().left) left = grid.offset().left - 11;
		if(top < grid.offset().top) top = grid.offset().top - 11;
		if(left > grid.offset().left + grid.width() - 146) left = grid.offset().left + grid.width() - 146;  
		if(top > grid.offset().top + grid.height() - 281) top = grid.offset().top + grid.height() - 281;  

		$('.handle').offset({left: left, top: top});
	}
}

function setCell(event, buttons){
	var startNumber = ($(event.target).attr('number')|0); // index of event.target
	var pattern = patterns[patternName]; // patterns are defined in patterns.js

	// alive cells
	if(buttons && !moveSelectionFlag){
		var databaseCells = [];
		for(var x = 0; x < pattern.length; x ++){
			for(var y = 0; y < pattern[x].length; y ++){
				// set cell in view
				var number = ((startNumber + x) % cellsX) + ((Math.floor(startNumber / cellsX) + y) % cellsY) * cellsX;
				var cell = $('.cell[number="' + number + '"]');
				var state = pattern[x][y] == 0 ? 0 : typeOfNewState;
				cell.attr('state', state);
				cell.attr('class', 'cell state' + state);
				databaseCells.push({id: number, state: state}); 
			}
		}
		// set in database
		if(!pauseFlag)
			$.post('homepage/setCells', {cells: JSON.stringify(databaseCells)});
	}
	// move outline	
	else if(!buttons && !moveSelectionFlag){
		$('.outline').removeClass('outline');
		if(!touchDeviceFlag){
			var databaseCells = [];
			for(var x = 0; x < pattern.length; x ++){
				for(var y = 0; y < pattern[x].length; y ++){
					var number = ((startNumber + x) % cellsX) + ((Math.floor(startNumber / cellsX) + y) % cellsY) * cellsX;
					var cell = $('.cell[number="' + number + '"]');
					if(pattern[x][y] == 1){
						cell.addClass('outline');
					}
				}
			}
		}	
	}
}

function setColors(){
	var colors = ['#F80000', '#FF4500', '#00FF00', '#99FF00', '#FFFF00', '#FF00FF', '#66FFFF', '#3300FF', '#0000FF', '#FFFFCC', '#660099', '#FFC0CB', '#222222'];
	var len = colors.length;
	$('.colorBlock').each(function(){
		var id = Math.floor(Math.random() * len) % len;
		if(!$(this).hasClass('colorPicker')){
			$(this).css('background-color', colors[id]);
		}
		if(!$(this).is('select'))
			$(this).css('color', colors[(id +  Math.floor(Math.random() * (len - 1)) + 1) % len]);
		$(this).css('box-shadow', '4px 4px 0 0 ' + colors[(id +  Math.floor(Math.random() * (len - 1)) + 1) % len]);
	});
	$('h1').each(function(){
		var id = Math.floor(Math.random() * len) % len;
		$(this).css('color', colors[id]);
		$(this).css('text-shadow', '4px 4px 0 ' + colors[(id +  Math.floor(Math.random() * (len - 1)) + 1) % len]);
		$(this).css('color', colors[(id +  Math.floor(Math.random() * (len - 1)) + 1) % len]);
	});
}

$(function ready(){
	getData();
	var interval = setInterval(function(){ getData(); }, animationStep * 1000);

	// colors
	setColors();
	setInterval(function(){ setColors(); }, 2500);

	$('.pause').click(function(event){
		$(this).toggleClass('play');
		$(this).toggleClass('stop');
		if(pauseFlag){ // post changes to databse
			
			// positions
			var handle = $('.handle').offset();
			var grid = $('.grid').offset();

			// states
			var states = [];
			var cells = $('.cell').each(function(){
				states.push({id: $(this).attr('number'), state: $(this).attr('state')});
			});

			// post call			
			$.post('homepage/setCells', {
				cells: JSON.stringify(states), 
				selection: JSON.stringify({
					x: Math.floor((handle.left - grid.left) / 27 + 1),
					y: Math.floor((handle.top - grid.top) / 27 + 1)
				})
			});

			interval = setInterval(function(){ getData() }, animationStep * 1000); // make it run
			$(this).html('pause'); // pause button
			$('.clearGrid').hide(); // show clear button
			pauseFlag = false; // flag
		}
		else{
			clearInterval(interval); // stop execution
			$(this).html('play'); // pause button
			$('.clearGrid').show(); // hide clear button
			pauseFlag = true; // flag
		}
	});
	
	// set state of cell on the server
 	$('.cell').click(function(event){ setCell(event, 1); });
 	$('.cell').hover(function(event){	setCell(event, event.buttons); });
 
	// selection move
	$('.handle').mousedown(function(event){ moveSelectionFlag = true; }); // mouse down on handle
	$(document).mouseup(function(event){ // mouse up
		if(moveSelectionFlag){
			// set selection in database
			if(!pauseFlag){
				var grid = $('.grid').offset();
				var handle = $('.handle').offset();
				var pos = {
					x: Math.floor((handle.left - grid.left) / 27 + 1),
					y: Math.floor((handle.top - grid.top) / 27 + 1)
				};
				$.post('homepage/setSelection', pos);
			}
			moveSelectionFlag = false;
		}
	}); 
	$(document).mousemove(function(event){ moveSelection(event, moveSelectionFlag); });
	
	// clear grid
	$('.clearGrid').click(function(){
		if(pauseFlag){
			console.log(background);
			$('.cell').attr('state', '0').attr('class', 'cell');
			$('.cell').css('background-color', '#' + background);
		}
	});

	// change dead cells color
	$('.colorPicker').each(function(){
		var val = $(this).val(); // changed value
		if(val)
			$(this).css('background-color', '#' + val); // change css style
	});
	$('.colorPicker').change(function(event){
		var val = $(this).val(); // changed value
		var option = $(this).find('option[value="' + val + '"]');
		if($(event.target).is('#background')){
	    background = val;
			$.post('homepage/changeColor', {background: val});
		}
		else if($(this).is('#foreground')){
			typeOfNewState = option.prevAll().length;
		}
		$(this).css('background-color', '#' + val); // change css style
	});

	// font family
	$('select').change(function(){
		var val = $(this).val(); // changed value
		var option = $(this).find('option[value="' + val + '"]');
		$(this).find('option').css('font-family', 'helveticaregular');
		option.css('font-family', 'helveticabold');		
	});

	// change pattern
	$('#pattern').change(function(){
		var val = $(this).val();
		patternName = val;
	});
});

})();