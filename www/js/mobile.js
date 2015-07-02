'use strict';

(function(){

function touchSetCell(startNumber){
	if(!moveSelectionFlag){
		var pattern = patterns[patternName];
		var databaseCells = [];
		for(var x = 0; x < pattern.length; x ++){
			for(var y = 0; y < pattern[x].length; y ++){
				var number = ((startNumber + x) % cellsX) + ((Math.floor(startNumber / cellsX) + y) % cellsY) * cellsX;

				// set cell in view
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
}

function touchMoveSelection(event, state){
	if(state){
		var handle = $('.handle'); // handle element
		var grid = $('.grid'); // grid element
		var eX = event.changedTouches[0].pageX;
		var eY = event.changedTouches[0].pageY;
		var left = Math.floor((eX - handle.offset().left) / 27) * 27 + handle.offset().left; 
		var top = Math.floor((eY - handle.offset().top) / 27) * 27 + handle.offset().top;

		if(left < grid.offset().left) left = grid.offset().left - 11;
		if(top < grid.offset().top) top = grid.offset().top - 11;
		if(left > grid.offset().left + grid.width() - 146) left = grid.offset().left + grid.width() - 146;  
		if(top > grid.offset().top + grid.height() - 281) top = grid.offset().top + grid.height() - 281;  

		$('.handle').offset({left: left, top: top});
	}
}

$(function ready(){
	$(document).on('touchstart', function(){
		touchDeviceFlag = true;	
	});

	// move selection
	$('.handle').on('touchstart', function(event){
		$(this).addClass('active');
		moveSelectionFlag = true;
	});
	$('.handle').on('touchend', function(event){
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
			$(this).removeClass('active');
			moveSelectionFlag = false;
		}
	});

	$('.grid').on('touchmove', function(event){
		event = event.originalEvent;

		// move selection
		touchMoveSelection(event, moveSelectionFlag);

		// set state of cell on the server
		if(event.changedTouches.length == 1){
			var touch = event.changedTouches[0];
			var grid = $(this).offset();
			var x = Math.floor((touch.pageX - grid.left) / 27);
			var y = Math.floor((touch.pageY - grid.top) / 27);
			touchSetCell(y * cellsX + x);
			event.preventDefault();
		}
	});
});

})();