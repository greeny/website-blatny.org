<?php
/**
 * @author Tomáš Blatný
 */

namespace greeny\Website\Security;

use Nette\Security\Permission;

class Authorizator extends Permission {


	public function __construct()
	{
		$this->addRole('guest');
		$this->addRole('member', 'guest');
		$this->addRole('admin', 'member');
		$this->addRole('owner', 'admin');

		$this->addResource('article');

		$this->allow('owner');
	}
}
