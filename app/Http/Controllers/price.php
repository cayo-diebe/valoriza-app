<?php

//veio esse codigo quando fiz maker controller
/*namespace App\Http\Controllers;

use Illuminate\Http\Request;

class price extends Controller
{
    
}
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;


require_once 'api/commons/core/utils/server.php';
require_once 'api/commons/utils.php';

class Server {

    public function getPriceById($w, $r) {
        $coreServerUtils = new ServerUtils();

        $jwtInfo = (new ServerUtils())->validateToken($r, $w);
        if ($jwtInfo === null) {
            return;
        }

        list($price, $err) = (new Repository())->getPriceById(1);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $coreServerUtils->responseOK($w, $price);
    }

    public function updatePrice($w, $r) {
        $coreServerUtils = new ServerUtils();

        $jwtInfo = (new ServerUtils())->validateToken($r, $w);
        if ($jwtInfo === null) {
            return;
        }

        $price = new Price();

        $err = $coreServerUtils->parseBody($w, $r->getBody(), $price);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $err = (new Repository())->updatePrice($price);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $coreServerUtils->responseOK($w, "Price updated");
    }
}
?>



