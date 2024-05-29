<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class email extends Controller
{
    //
}
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require_once 'api/commons/core/utils/server.php';
require_once 'api/commons/utils.php';

class Server {

    public function post($w, $r) {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->validateToken($r, $w);
        if ($jwtInfo === null) {
            return;
        }

        $message = new Message();

        $err = $coreServerUtils->parseBody($w, $r->getBody(), $message);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $err = (new Service())->sendAlertEmail($message);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $coreServerUtils->responseNoContent($w);
    }

    public function registerEmail($w, $r) {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->validateToken($r, $w);
        if ($jwtInfo === null) {
            return;
        }

        $message = new CreateMailMessage();

        $err = $coreServerUtils->parseBody($w, $r->getBody(), $message);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $err = (new Service())->sendEmailActivate($message);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $coreServerUtils->responseNoContent($w);
    }
}
?>


