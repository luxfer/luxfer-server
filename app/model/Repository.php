<?php

namespace Model;

use Nette;

abstract class Repository extends Nette\Object
{
	protected $connection;

	public function __construct(Nette\Database\Context $db)
	{
		$this->connection = $db;
	}

	protected function getTable()
	{
		preg_match('#(\w+)Repository$#', get_class($this), $m);
		$table = strtolower(lcfirst($m[1]));
		return $this->connection->table($table);
	}

	public function findAll()
	{
		return $this->getTable();
	}

	public function findBy(array $by)
	{
		return $this->getTable()->where($by);
	}

	public function findOneBy(array $by)
	{
		return $this->getTable()->where($by)->fetch();		
	}
	
	public function add($row)
	{
		$res = $this->getTable()->insert($row);
		return $res->id;
	}

	public function remove($id)
	{
		preg_match('#(\w+)Repository$#', get_class($this), $m);
		$table = lcfirst($m[1]);
		return $this->connection->query('DELETE FROM ' . $table .' WHERE id = ?', $id);
	}

	public function removeBy($by)
	{
		preg_match('#(\w+)Repository$#', get_class($this), $m);
		$table = strtolower(lcfirst($m[1]));
		return $this->connection->query('DELETE FROM ' . $table .' WHERE ?', $by);
	}

	public function insertUpdate($row)
	{
		preg_match('#(\w+)Repository$#', get_class($this), $m);
		$table = strtolower(lcfirst($m[1]));
		$update = implode(',', array_map(function($d){ return "{$d}=VALUES({$d})"; }, array_keys($row[0])));
		$this->connection->query('INSERT INTO ' . $table .' ? ON DUPLICATE KEY UPDATE ' . $update, $row);
	}

	public function updateBy($by, $row)
	{
		preg_match('#(\w+)Repository$#', get_class($this), $m);
		$table = strtolower(lcfirst($m[1]));
		$this->connection->query('UPDATE ' . $table .' SET ? WHERE ?', $row, $by);
	}
}
