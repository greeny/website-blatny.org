<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\Model;

class ProjectRepository extends BaseRepository
{
	public function findBySlug($slug)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[slug] = %s', $slug)
			->fetch();
		return $row ? $this->createEntity($row) : NULL;
	}

	public function getPanelProjects()
	{
		return $this->createEntities(
			$this->connection->select('*')
				->from($this->getTable())
				->limit(10)
				->fetchAll()
		);
	}
}
