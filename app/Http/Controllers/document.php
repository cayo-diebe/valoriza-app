<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class document extends Controller
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

        $document = new Document();

        $err = $coreServerUtils->parseBody($w, $r->getBody(), $document);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        list($url, $err) = (new Service())->postDocument($document);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $coreServerUtils->responseOK($w, $url);
    }

    public function postPdf($w, $r) {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->validateToken($r, $w);
        if ($jwtInfo === null) {
            return;
        }

        $pdf = new Pdf();

        $err = $coreServerUtils->parseBody($w, $r->getBody(), $pdf);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        list($url, $err) = (new Service())->postPdf($pdf);
        if ($err !== null) {
            $coreServerUtils->responseError($w, $err);
            return;
        }

        $coreServerUtils->responseOK($w, $url);
    }
}
?>



