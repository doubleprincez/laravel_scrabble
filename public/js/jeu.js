console.clear();  // JUST FOR DEVELOPMENT!

// General dragging functionality:
function makeTilesDraggable() {
	$(".tile").draggable({
		appendTo: ".board td",
		scope: "cells",
		stop: function() {
			$(this).css({
				top: 0,
				left: 0
			});
		}
	});
	$(".board .tile").draggable("disable");
}
makeTilesDraggable();

var enableCells = function() {
	// console.log("enabling");
	$(".board td.cell-occupied").droppable("disable");
	$(".board td:not(.cell-occupied)").droppable("enable");
	$(".board td:not(.cell-occupied)").droppable({
		tolerance: "pointer",
		scope: "cells",
		activeClass: "cell-occupiable",
		hoverClass: "cell-hover",
		accept: ".tile",
		drop: function(evt, ui) {
			if ( $(this).hasClass("cell-tile_droppable") ) {
				var $cell = $(this);
				var $tile = $(ui.draggable);
				$tile.closest(".board td").removeClass("cell-occupied");
				var t = $tile.css("top");
				var l = $tile.css("left");
				// console.log("$cell = " + t + ", " + l);
				$tile.appendTo($cell);
				$tile.addClass("tile-occupier");
				$tile.removeClass("tile-free");
				$tile.css({
					top: 0,
					left: 0
				});
				$cell.addClass("cell-occupied");
				$tile.addClass("tile-activeWord");
				$tile.draggable("disable");
				tileWasDropped($(this).index(), $(this).parent().index());
				setDroppableCells();
				enableCells();
			} else {
				console.log("You can't drop a tile in this cell!");
			}
		}
	});
};

$(".board td").droppable();
enableCells();

// Game specifiy functionality:

let turnIsActive = false; // True if the players turn is ongoing
let activeWords = []; // All words that are build in a turn will be stored in this array

$(".board td").append("<div class='drop_helper'></div>");

function tileWasDropped(x, y) {
	console.log(`Tile was dropped in the cell [X: ${x} | Y: ${y}]`);
	
	// ToDo optional: Send (hidden) tile to the other players
	
	let $topNeighborTile =    getCell(x, y - 1).children(".tile");
	let $rightNeighborTile =  getCell(x + 1, y).children(".tile");
	let $bottomNeighborTile = getCell(x, y + 1).children(".tile");
	let $leftNeighborTile =   getCell(x - 1, y).children(".tile");
	
	let active_vWord = []; // All vertical (sub-)words that are build with this tile-drop will be stored here
	let active_hWord = []; // All horizontal (sub-)words that are build with this tile-drop will be stored here
	
	function addLetter(xx, yy, dir) {
		if ( getCell(xx, yy).children(".tile").length > 0 ) {
			// Push the letter to the vertical/horizontal word:
			if ( (dir === "top") || (dir === "bottom") ) active_vWord.push({"x": xx, "y": yy});
			else active_hWord.push({"x": xx, "y": yy});
			// Check (and add) the next letter:
			if      ( dir === "top" )    addLetter(xx, yy - 1, "top");
			else if ( dir === "right" )  addLetter(xx + 1, yy, "right");
			else if ( dir === "bottom" ) addLetter(xx, yy + 1, "bottom");
			else if ( dir === "left" )   addLetter(xx - 1, yy, "left");
		}
	}
	
	if ( ($topNeighborTile.length > 0) || ($bottomNeighborTile.length > 0) ) {
		active_vWord = [{ "x": x, "y": y }]
		addLetter(x, y - 1, "top"); // Add top letters
		addLetter(x, y + 1, "bottom"); // Add bottom letters
	}
	if ( ($leftNeighborTile.length > 0) || ($rightNeighborTile.length > 0) ) {
		active_hWord = [{ "x": x, "y": y }]
		addLetter(x - 1, y, "left"); // Add left letters
		addLetter(x + 1, y, "right"); // Add right letters
	}
	
	// Sort the active_vWord and active_hWord:
	function compareTiles(tile1, tile2) {
		if ( tile1.x < tile2.x ) return -1;
		else if ( tile1.x > tile2.x ) return 1;
		else {
			if ( tile1.y < tile2.y ) return -1;
			else if ( tile1.y > tile2.y ) return 1;
		}
		return 0;
	}
	active_vWord.sort(compareTiles);
	active_hWord.sort(compareTiles);
	
	console.log("\n\n\n"); // Just a placeholder for testing
	
	// console.log("Active Vertical Word: ", active_vWord);
	// console.log("Active Horizontal Word: ", active_hWord);
	
	// Check if any word that is already in activeWords is a subset of active_vWord or active_hWord. If so, replace it with its superset. If not, just add the new word
	function containsWord(word) {
		let list = activeWords;
		for (let i = 0; i < list.length; i++) { // Iterate over all words in activeWords
			// console.log("i loop", word, list[i]);
			let foundWord = true;
			for (let j = 0; j < list[i].length; j++) { // Iterate over all tiles in the word
				let foundTile = false;
				// console.log("j loop", word, list[i][j]);
				for (let k = 0; k < word.length; k++) { // Iterate over all tiles in this word
					// console.log( "k loop", (word[k].x == list[i][j].x) && (word[k].y == list[i][j].y), word[k], list[i][j] );
					if ( (word[k].x == list[i][j].x) && (word[k].y == list[i][j].y) ) {
						foundTile = true;
						break;
					}
				}
				foundWord = (foundWord && foundTile);
			}
			// console.log("Match found:", foundWord, i);
			if ( foundWord ) return i;
		}
		return -1;
	}
	
	function checkWord(active_word) {
		if	( active_word.length !== 0 ) {
			let wordIndex = containsWord(active_word);
			if ( wordIndex >= 0 ) {
				activeWords[wordIndex] = active_word;
			} else {
				activeWords.push(active_word);
			}
		}
	}
	
	checkWord(active_vWord);
	checkWord(active_hWord);
	
	// Create a list of all words (in letter format) with there respective points:
	let wordPoints = [];
	for (let i = 0; i < activeWords.length; i++) {
		wordPoints.push({ "word": "", "value": 0, "special": [], "printWord": "" });
		let pointMultiplier = 1;
		for (let j = 0; j < activeWords[i].length; j++) {
			let cell = getCell(activeWords[i][j].x, activeWords[i][j].y);
			let letter = cell.children(".tile").attr("d-letter");
			let value = parseInt(cell.children(".tile").attr("d-value"));
			if ( cell.hasClass("l2") ) {  // Double letter value
				value = value * 2;
				wordPoints[i].special.push("l2");
				wordPoints[i].printWord += `<span class="word-l2">${letter}</span>`;
			} else if ( cell.hasClass("l3") ) { // Triple letter value
				value = value * 3;
				wordPoints[i].special.push("l3");
				wordPoints[i].printWord += `<span class="word-l3">${letter}</span>`;
			} else if ( cell.hasClass("w2") ) {  // Double word value
				pointMultiplier = pointMultiplier * 2;
				wordPoints[i].special.push("w2");
				wordPoints[i].printWord += `<span class="word-w2">${letter}</span>`;
			} else if ( cell.hasClass("w3") ) {  // Triple word value
				pointMultiplier = pointMultiplier * 3;
				wordPoints[i].special.push("w3");
				wordPoints[i].printWord += `<span class="word-w3">${letter}</span>`;
			} else {  // Default cell (or start cell)
				wordPoints[i].special.push(null);
				wordPoints[i].printWord += `<span>${letter}</span>`;
			}
			console.log(cell, letter, value);
			wordPoints[i].word += letter;
			wordPoints[i].value += value;
		}
		wordPoints[i].value = wordPoints[i].value * pointMultiplier;
	}
	
	console.log("activeWords: ", activeWords);
	console.log("wordPoints: ", wordPoints);
	
	// Add words and values to the word_box and recalculate total points and add them to point_box:
	let totalPoints = 0;
	$(".word_box").empty();
	for (let i = 0; i < wordPoints.length; i++) {
		totalPoints += parseInt(wordPoints[i].value);
		$(".word_box").append(`<div d-value="${wordPoints[i].value}">${wordPoints[i].printWord}</div>`);
	}
	$(".point_box").text(totalPoints);
}

$(".star").addClass("cell-tile_droppable");
function setDroppableCells() {
	$(".board td").removeClass("cell-tile_droppable");
	$.each( $(".board td"), function(index, thisCell) {
		let x = $(thisCell).index();
		let y = $(thisCell).parent().index();
		// console.log("X: " + x + " | Y: " + y);
		if ( getCell(x, y - 1).hasClass("cell-occupied") ||
			  getCell(x + 1, y).hasClass("cell-occupied") ||
			  getCell(x, y + 1).hasClass("cell-occupied") ||
			  getCell(x - 1, y).hasClass("cell-occupied") ) {
			$(thisCell).addClass("cell-tile_droppable");
		}
	});
}

function getCell(x, y) {
	return $(`.board tr:nth-child(${y + 1}) > td:nth-child(${x + 1})`);
}

const tiles = [
	{ letter: "A", value: 1, quantity: 9 },
	{ letter: "B", value: 3, quantity: 2 },
	{ letter: "C", value: 3, quantity: 2 },
	{ letter: "D", value: 2, quantity: 4 },
	{ letter: "E", value: 1, quantity: 12 },
	{ letter: "F", value: 4, quantity: 2 },
	{ letter: "G", value: 2, quantity: 3 },
	{ letter: "H", value: 4, quantity: 2 },
	{ letter: "I", value: 1, quantity: 9 },
	{ letter: "J", value: 8, quantity: 1 },
	{ letter: "K", value: 5, quantity: 1 },
	{ letter: "L", value: 1, quantity: 4 },
	{ letter: "M", value: 3, quantity: 2 },
	{ letter: "N", value: 1, quantity: 6 },
	{ letter: "O", value: 1, quantity: 8 },
	{ letter: "P", value: 3, quantity: 2 },
	{ letter: "Q", value: 10, quantity: 1 },
	{ letter: "R", value: 1, quantity: 6 },
	{ letter: "S", value: 1, quantity: 4 },
	{ letter: "T", value: 1, quantity: 6 },
	{ letter: "U", value: 1, quantity: 4 },
	{ letter: "V", value: 4, quantity: 2 },
	{ letter: "W", value: 4, quantity: 2 },
	{ letter: "X", value: 8, quantity: 1 },
	{ letter: "Y", value: 4, quantity: 2 },
	{ letter: "Z", value: 10, quantity: 1 },
	{ letter: "", value: 0, quantity: 2 }
]
let tilePouch = [];

$(".refreshTiles").on("click", () => {  // JUST FOR DEVELOPMENT!
	for (let i = 1; i <= 7; i++) {
		let randomTile = tiles[Math.floor(Math.random() * tiles.length)];
		$(`.rack_spot:nth-child(${i})`).empty().append(`<div class="tile" d-letter="${randomTile.letter}" d-value="${randomTile.value}"></div>`);
		makeTilesDraggable();
	}
});

function shuffle(array) {  // Helper function to shuffle a array using the Fisher-Yates (aka Knuth) Shuffle
	var currentIndex = array.length, temporaryValue, randomIndex;
	while (0 !== currentIndex) {  // While there remain elements to shuffle...
		randomIndex = Math.floor(Math.random() * currentIndex);  // ...pick a random remaining element...
		currentIndex -= 1;
		// ...and swap it with the current element:
		temporaryValue = array[currentIndex];
		array[currentIndex] = array[randomIndex];
		array[randomIndex] = temporaryValue;
	}
	return array;
}

function begin_game() {  // This will be run by the first player at the beginning of the game
	// Build the "tile pouch", which is the pouch of all tiles left in the game that is also synchronized between players:
	for (let i = 0; i < tiles.length; i++) {
		for (let j = 0; j < tiles[i].quantity; j++) {
			console.log("Pushing", tiles[i].letter);
			tilePouch.push({ letter: tiles[i].letter, value: tiles[j].value });
		}
	}
	// Shuffle the "tilePouch" array:
	tilePouch = shuffle(tilePouch);
	
	draw_tile(7);  // Draw 7 tiles
	
	// ToDo: Send the "tiles" array to the oter players
	
	start_turn();
}

begin_game();  // JUST FOR DEVELOPMENT!

function draw_tile(nbr = 1) {  // Abstracted funktion for DRAWING the first "nbr" tiles from tilePouch
	// Take (shift) the first "nbr" tiles from the "tilePouch" array and put them in the tile rack:
	for (let i = 0; i < nbr; i++) {
		let newTile = tilePouch.shift();  // Get the first tile from the tilePouch array and delete it
		
	}
}

function dump_tile(nbr = 1) {  // Abstracted funktion for DELETING the first "nbr" tiles from tilePouch
	for (let i = 0; i < nbr; i++) tilePouch.shift();
}

function start_turn() {
	console.log("starting turn");
	turnIsActive = true;
	
	// ToDo optional: add a class to the body and to the tiles to signal that they are set in this round
}

function end_turn() {
	console.log("ending turn");
	turnIsActive = false;
	
	// ToDo: Draw tiles until you have 7 tiles again
}

$(".startTurn").on("click", () => { start_turn() });  // JUST FOR DEVELOPMENT!
$(".endTurn").on("click", () => { end_turn() });