<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\Model;

use Nette\Utils\ArrayHash;
use Nette\Security\AuthenticationException;
use Nette\Utils\Random;
use greeny\Website\Security\PasswordHasher;

class UserRepository extends BaseRepository
{
	public function findByNick($nick)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[nick] = %s', $nick)
			->fetch();
		return $row ? $this->createEntity($row) : NULL;
	}

	public function findByEmail($email)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('[email] = %s', $email)
			->fetch();
		return $row ? $this->createEntity($row) : NULL;
	}

	public function register(ArrayHash $data)
	{
		if($this->findByNick($data->nick)) {
			throw new AuthenticationException("User '$data->nick' already exists.");
		}

		if($this->findByEmail($data->email)) {
			throw new AuthenticationException("User with email '$data->email' already exists.");
		}

		/** @var User $user */
		$user = User::from($data);
		$user->salt = Random::generate(5, 'A-Za-z0-9');
		$user->password = PasswordHasher::hashPassword($user->nick, $user->password, $user->salt);
		$user->role = 'member';
		$this->persist($user);
	}

}
