<?php
/*
veio este codigo na maker controller
class payment extends Controller
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

        $payment = new Payment();

        $err = $coreServerUtils->parseBody($w, $r->getBody(), $payment);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        list($url, $err) = (new Service())->payment($payment, $jwtInfo->userId);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $coreServerUtils->responseCreatedObj($w, $url);
    }

    public function get($w, $r) {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->validateToken($r, $w);
        if ($jwtInfo === null) {
            return;
        }

        list($payments, $err) = (new Service())->getPayments();
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $coreServerUtils->responseOK($w, $payments);
    }
}
?>


