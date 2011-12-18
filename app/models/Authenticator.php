<?php

use Nette\Security as NS;


/**
 * Třída, která se stará o autentifikaci uživatelů.
 *
 * @author     David Grudl
 * @package    Quickstart
 */
class Authenticator extends Nette\Object implements NS\IAuthenticator
{
	/** @var Nette\Database\Table\Selection */
	private $users;



	public function __construct(Nette\Database\Table\Selection $users)
	{
		$this->users = $users;
	}



	/**
	 * Provede ověření zadaných přístupových údajů.
	 * @param  $credentials array Pole obsahující klíče IAuthenticator::USERNAME a IAuthenticator::PASSWORD.
	 * @return Nette\Security\Identity Identita uživatele.
	 * @throws Nette\Security\AuthenticationException V případě, že zadané údaje nejsou platné.
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;
		$row = $this->users->where('username', $username)->fetch();

		if (!$row) {
			throw new NS\AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
		}

		if ($row->password !== $this->calculateHash($password)) {
			throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
		}

		unset($row->password);
		return new NS\Identity($row->id, NULL, $row->toArray());
	}



	/**
	 * Vypočítá osolený hash hesla.
	 * @param  $password string Heslo v plaintextu.
	 * @return string Vypočítaný hash.
	 */
	public function calculateHash($password)
	{
		return hash('sha512', $password);
	}


	/**
	 * Změní heslo uživatele.
	 * @param $user_id string ID uživatele.
	 * @param $password string Nové heslo.
	 */
	public function setPassword($id, $password)
	{
		$this->users->where(array('id' => $id))
			->update(array('password' => $this->calculateHash($password)));
	}

}
