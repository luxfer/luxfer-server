<?php

namespace Model;

use Nette;

class CellsRepository extends Repository
{
	public function findCells($maxId)
	{
		return $this->connection->query('SELECT state FROM cells WHERE id < ? ORDER BY id ASC', $maxId);
	}
	
	public function insertString($values)
	{
		return $this->connection->query('INSERT INTO cells (id, state) VALUES' . $values . ' ON DUPLICATE KEY UPDATE state = VALUES(state)');
	}
}
