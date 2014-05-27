<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\Model;

class ArticleRepository extends BaseRepository
{
	public function findAll()
	{
		return $this->createEntities(
			$this->connection->select('*')
				->from($this->getTable())
				->where('[published] = %b', TRUE)
				->orderBy('[time] DESC')
				->fetchAll()
		);
	}

	public function findNotPublished()
	{
		return $this->createEntities(
			$this->connection->select('*')
				->from($this->getTable())
				->where('[published] = %b', FALSE)
				->orderBy('[time] DESC')
				->fetchAll()
		);
	}

	public function findBySlug($slug)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[slug] = %s', $slug)
			->fetch();
		return $row ? $this->createEntity($row) : NULL;
	}

	public function getPrevious(Article $article)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[time] < %i', $article->time)
			->where('[published] = %b', TRUE)
			->orderBy('[time] DESC')
			->fetch();
		return $row ? $this->createEntity($row) : NULL;
	}

	public function getNext(Article $article)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[time] > %i', $article->time)
			->where('[published] = %b', TRUE)
			->orderBy('[time] ASC')
			->fetch();
		return $row ? $this->createEntity($row) : NULL;
	}

	public function getPanelArticles()
	{
		return $this->createEntities(
			$this->connection->select('*')
				->from($this->getTable())
				->where('[published] = %b', TRUE)
				->orderBy('[time] DESC')
				->limit(10)
				->fetchAll()
		);
	}
}
