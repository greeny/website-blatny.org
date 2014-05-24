<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\Model;

use LeanMapper\Repository;

class BaseRepository extends Repository {
	public function find($id)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('id = %i', $id)
			->fetch();
		return ($row === false ? NULL : $this->createEntity($row));
	}

	public function findAll()
	{
		return $this->createEntities(
			$this->connection->select('*')
				->from($this->getTable())
				->fetchAll()
		);
	}
}
