var patterns = {
	// one cell
	default0: [[1]],
	// blinker
	default1: [[1,1,1]],
	// toad
	default2: [[0,1,1,1],[1,1,1,0]],
	// beacon
	default3: [[1,1,0,0],[1,1,0,0],[0,0,1,1],[0,0,1,1]],
	// block
	default4: [[1,1],[1,1]],
	// beehive
	default5: [[0,1,1,0],[1,0,0,1],[0,1,1,0]],
	// loaf
	default6: [[0,1,1,0],[1,0,0,1],[0,1,0,1],[0,0,1,0]],
	// boat
	default7: [[1,1,0],[1,0,1],[0,1,0]],
	// glider
	default8: [[0,1,0],[0,0,1],[1,1,1]],
	// lightweight spaceship
	default9: [[0,1,1,1,1],[1,0,0,0,1],[0,0,0,0,1],[1,0,0,1,0]],
	// the R-pentomino
	default10: [[0,1,1],[1,1,0],[0,1,0]],
	// Diehard
	default11: [[0,1,0],[0,1,1],[0,0,0],[0,0,0],[0,0,0],[0,0,1],[1,0,1],[0,0,1]],
	// Acorn
	default12: [[0,0,1],[1,0,1],[0,0,0],[0,1,0],[0,0,1],[0,0,1],[0,0,1]]
};