<?php

namespace Animateka\Authentication;
use Magrathea2\Exceptions\MagratheaApiException;
require("../vendor/autoload.php");

use Exception;
use Magrathea2\Admin\Features\User\AdminUserControl;
use Magrathea2\MagratheaApiAuth;
use MagratheaCloud\User;

class AuthApi extends MagratheaApiAuth {

	public function Token() {
		return $this->GetTokenInfo();
	}

	private function ReturnUserToken($id, $email) {
		try {
			$u = new User($id);
			$response = [
				"id" => $id,
				"email" => $email,
				"role" => $u->GetRoleName(),
				"roleName" => $u->role_id,
			];
			return $this->ResponsePayload($response);	
		} catch(Exception $ex) {
			throw $ex;
		}
	}

	public function Login() {
		$data = $this->GetPost();
		$control = new AdminUserControl();
		try {
			$login = $control->Login($data["email"], $data["password"]);
			if(!$login["success"]) {
				throw new MagratheaApiException($login["message"]);
			}
			$user = $login["user"]; 
			return $this->ReturnUserToken($user->id, $user->email);
		} catch(Exception $ex) {
			throw $ex;
		}
	}

	public function GetFullUser() {
		try {
			$token = $this->GetTokenInfo();
			return $this->ReturnUserToken($token->id, $token->email);
		} catch(Exception $ex) {
			throw $ex;
		}
	}

	public function IsManager() {
		$token = $this->GetTokenInfo();
		if(!@$token?->role) return false;
		return ($token->role == "ADMIN" || $token->role == "MANAGER");
	}

	public function IsAdmin() {
		$token = $this->GetTokenInfo();
		if(!@$token?->role) return false;
		return ($token->role == "ADMIN");
	}

	public function IsMe($id) {
		$token = $this->GetTokenInfo();
		return ($token->id == $id);
	}

}
