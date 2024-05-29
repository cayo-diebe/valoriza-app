<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class authenticate extends Controller
{
    //
}
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Server
{
    public function Authenticate($w, $r)
    {
        $coreServerUtils = new CoreServerUtils();

        $authRequest = new AuthRequest();

        $err = $coreServerUtils->ParseBody($w, $r->getBody(), $authRequest);
        if ($err) {
            return;
        }

        $email = strtolower(trim($authRequest->Email));

        $repository = new Repository();
        $userLogin = $repository->QueryOneByEmail($email);
        if (!$userLogin) {
            if (strpos($err->getMessage(), 'no rows in result set') !== false) {
                $this->ResponseNotAuthorized($w);
                return;
            }
            $coreServerUtils->ResponseError($w, $err);
            return;
        }

        if (!$this->MatchPassword($userLogin->Password, strtolower($authRequest->Password))) {
            $this->ResponseNotAuthorized($w);
            return;
        }

        if ($userLogin->Disabled || !empty($userLogin->DeletedAt)) {
            $this->ResponseNotAuthorized($w);
            return;
        }

        $exp = intval(getenv('EXPIRATION_TOKEN_DEFAULT'));

        $token = [
            "UserId" => $userLogin->Id,
            "Email" => $userLogin->Email,
            "exp" => time() + (3600 * $exp)
        ];

        $tokenStr = JWT::encode($token, config::JWTSECRET, 'HS256');
        if (!$tokenStr) {
            $err = new Exception("error.token.generation.failed | Token generation failed");
            $coreServerUtils->ResponseError($w, $err);
            return;
        }

        $authResponse = new AuthResponse();
        $authResponse->AccessToken = $tokenStr;

        $coreServerUtils->ResponseOK($w, $authResponse);
    }

    public function GetAccountMe($w, $r)
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->ValidateToken($r, $w);
        if (!$jwtInfo) {
            return;
        }

        $repository = new Repository();
        $accountMe = $repository->QueryByIdForAccountMe($jwtInfo->UserId);
        if (!$accountMe) {
            $coreServerUtils->ResponseError($w, $err);
            return;
        }

        $accountMe->Roles = $repository->QueryRolesByUserIdForAccountMe($jwtInfo->UserId);
        if (!$accountMe->Roles) {
            $coreServerUtils->ResponseError($w, $err);
            return;
        }

        $accountMe->Sellers = $repository->QuerySellersByUserIdForAccountMe($jwtInfo->UserId);
        if (!$accountMe->Sellers) {
            $coreServerUtils->ResponseError($w, $err);
            return;
        }

        $coreServerUtils->ResponseOK($w, $accountMe);
    }

    public function PutAccountPassword($w, $r)
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->ValidateToken($r, $w);
        if (!$jwtInfo) {
            return;
        }

        $accountPassword = new AccountPassword();

        $err = $coreServerUtils->ParseBody($w, $r->getBody(), $accountPassword);
        if ($err) {
            return;
        }

        $repository = new Repository();
        $err = $repository->UpdateAccountPassword($jwtInfo->UserId, $accountPassword->Password);
        if ($err) {
            $coreServerUtils->ResponseError($w, $err);
            return;
        }

        $coreServerUtils->ResponseNoContent($w);
    }

    public function PutAccount($w, $r)
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->ValidateToken($r, $w);
        if (!$jwtInfo) {
            return;
        }

        $accountData = new AccountData();

        $err = $coreServerUtils->ParseBody($w, $r->getBody(), $accountData);
        if ($err) {
            return;
        }

        $repository = new Repository();
        $err = $repository->UpdateAccount($jwtInfo->UserId, $accountData, $jwtInfo->UserId);
        if ($err) {
            $coreServerUtils->ResponseError($w, $err);
            return;
        }

        $coreServerUtils->ResponseNoContent($w);
    }

    public function MatchPassword($hash, $password)
    {
        return password_verify($password, $hash);
    }

    public function ResponseNotAuthorized($w)
    {
        $err = new Exception("error.not.authorized | Invalid Credentials");

        $bodyResponse = (new CoreServerUtils())->Exception()->Make($err->getMessage());

        (new CoreServerUtils())->Response($w, 401, $bodyResponse);
    }
}
?>