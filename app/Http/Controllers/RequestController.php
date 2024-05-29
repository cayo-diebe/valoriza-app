<?php

namespace App\Http\Controllers;

// require_once 'api/commons/utils.php';
// require_once 'api/commons/core/utils/server.php';
// require_once 'api/component/user.php';

use App\Commons\Util;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\Service;
// use api\component\user\Service;
// use api\component\user\Repository; 
// use Psr\Http\Message\ResponseInterface as Response;
// use Slim\Routing\RouteContext;


//-----------------------CONVERTI ESTE CODIGO :
/*import (
	"api/commons/utils"
	"fmt"
	"net/http"

	coreServerUtils "api/commons/core/utils/server"
	"api/component/user"

	"github.com/gorilla/mux"
)
*/
//-----------------------NESTE:
/*
use api\commons\utils;
use api\commons\core\utils\server as coreServerUtils;
use api\component\user;

require_once 'vendor/autoload.php';

$router = new \Symfony\Component\Routing\RouteCollection();
$router->add('route_name', new \Symfony\Component\Routing\Route('/path', [
    '_controller' => 'ControllerClass::actionMethod',
]));

$httpKernel = new \Symfony\Component\HttpKernel\HttpKernel(
    new \Symfony\Component\EventDispatcher\EventDispatcher(),
    new \Symfony\Component\HttpFoundation\RequestHandler($router)
);

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$response = $httpKernel->handle($request);
$response->send();
*/

//class Server ao inves da class abaixo?
class RequestController extends Controller
{
    // public function Post(Request $r, Response $w)
    // {
    //     $coreServerUtils = new \coreServerUtils\ServerUtils();

    //     $jwtInfo = \utils\ServerUtils::ValidateToken($r, $w);
    //     if ($jwtInfo == null) {
    //         return;
    //     }

    //     $request = new Request();

    //     $err = $coreServerUtils->ParseBody($w, $r->getContent(), &$request);
    //     if ($err != null) {
    //         $coreServerUtils->ResponseError($w, $err);
    //         return;
    //     }

    //     $id = \Service::Post($request, $jwtInfo->UserId);
    //     if ($err != null) {
    //         $coreServerUtils->ResponseError($w, $err);
    //         return;
    //     }
    //     $coreServerUtils->ResponseCreated($w, $id);
    // }
    // public function setExpert(Request $r, Response $w)
    // {
    //     $coreServerUtils = new \coreServerUtils\ServerUtils();

    //     $jwtInfo = \utils\ServerUtils::validateToken($r, $w);
    //     if ($jwtInfo === null) {
    //         return;
    //     }

    //     $id = $coreServerUtils->getPathVariableInt64($w, $r->attributes->get('_route_params'), "id");
    //     if ($id === null) {
    //         return;
    //     }

    //     $expertId = $coreServerUtils->getPathVariableInt64($w, $r->attributes->get('_route_params'), "expert_id");
    //     if ($expertId === null) {
    //         return;
    //     }

    //     $err = \Repository::update($id, $expertId);
    //     if ($err !== null) {
    //         $coreServerUtils->responseError($w, $err);
    //         return;
    //     }
    // }

    // public function get(Request $request, Response $response, array $args): Response {
    //     $coreServerUtils = new CoreServerUtils();

    //     $jwtInfo = (new Utils\ServerUtils())->validateToken($request, $response);
    //     if ($jwtInfo === null) {
    //         return $response;
    //     }

    //     $permission = (new User\Repository())->getUserPermissionByEmail($jwtInfo->email);
    //     if ($permission === null) {
    //         $message = ['message' => "Erro ao carregar solicitações."];
    //         return $coreServerUtils->response($response, 400, $message);
    //     }

    //     if ($permission == 1) {
    //         $requests = (new Service())->getAdminRequests($jwtInfo->userId);
    //         if ($requests === null) {
    //             $message = ['message' => "Erro ao carregar solicitações."];
    //             return $coreServerUtils->response($response, 400, $message);
    //         }
    //         return $coreServerUtils->responseOK($response, $requests);
    //     }

    //     if ($permission == 2) {
    //         $requests = (new Service())->getExpertRequests($jwtInfo->userId);
    //         if ($requests === null) {
    //             $message = ['message' => "Erro ao carregar solicitações."];
    //             return $coreServerUtils->response($response, 400, $message);
    //         }
    //         return $coreServerUtils->responseOK($response, $requests);
    //     }

    //     if ($permission == 3) {
    //         $requests = (new Service())->getClientRequests($jwtInfo->userId);
    //         if ($requests === null) {
    //             $message = ['message' => "Erro ao carregar solicitações."];
    //             return $coreServerUtils->response($response, 400, $message);
    //         }
    //         return $coreServerUtils->responseOK($response, $requests);
    //     }

    //     return $response;
    // }

    // public function getById(Request $request, Response $response, array $args): Response {
    public function getById() {
        $jwtInfo = $this->validateToken($request);
        if ($jwtInfo === null) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid ID'], Response::HTTP_BAD_REQUEST);
        }

        $requestData = $this->service->getRequestById($id);
        if ($requestData === null) {
            return response()->json(['error' => 'Error fetching request by ID'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($requestData, Response::HTTP_OK);
    }

    // public function acceptExpert(Request $request, Response $response, array $args): Response {
    //     $coreServerUtils = new CoreServerUtils();

    //     $jwtInfo = (new Utils\ServerUtils())->validateToken($request, $response);
    //     if ($jwtInfo === null) {
    //         return $response;
    //     }

    //     $id = $coreServerUtils->getPathVariableInt64($response, $args, 'id');
    //     if ($id === null) {
    //         return $response;
    //     }

    //     $result = (new Service())->setAcceptRequestById($id, $jwtInfo->userId);
    //     if ($result === null) {
    //         return $coreServerUtils->responseError($response, "Error accepting request");
    //     }

    //     return $coreServerUtils->responseNoContent($response);
    // }

    // public function rejectExpert(Request $request, Response $response, array $args): Response {
    //     $coreServerUtils = new CoreServerUtils();

    //     $jwtInfo = (new Utils\ServerUtils())->validateToken($request, $response);
    //     if ($jwtInfo === null) {
    //         return $response;
    //     }

    //     $id = $coreServerUtils->getPathVariableInt64($response, $args, 'id');
    //     if ($id === null) {
    //         return $response;
    //     }

    //     $result = (new Service())->setRejectRequestById($id, $jwtInfo->userId);
    //     if ($result === null) {
    //         return $coreServerUtils->responseError($response, "Error rejecting request");
    //     }

    //     return $coreServerUtils->responseNoContent($response);
    // }

    // public function delete(Request $request, Response $response, array $args): Response {
    //     $coreServerUtils = new CoreServerUtils();

    //     $jwtInfo = (new Utils\ServerUtils())->validateToken($request, $response);
    //     if ($jwtInfo === null) {
    //         return $response;
    //     }

    //     $id = $coreServerUtils->getPathVariableInt64($response, $args, 'id');
    //     if ($id === null) {
    //         return $response;
    //     }

    //     $result = (new Repository())->delete($id, $jwtInfo->userId);
    //     if ($result === null) {
    //         return $coreServerUtils->responseError($response, "Error deleting request");
    //     }

    //     return $coreServerUtils->responseNoContent($response);
    // }

    // public function updateStatus(Request $request, Response $response, array $args): Response {
    //     $coreServerUtils = new CoreServerUtils();

    //     $jwtInfo = (new Utils\ServerUtils())->validateToken($request, $response);
    //     if ($jwtInfo === null) {
    //         return $response;
    //     }

    //     $parsedBody = $request->getParsedBody();
    //     $requestUpdateStatus = new RequestUpdateStatus($parsedBody);

    //     $re = (new Service())->getRequestById($requestUpdateStatus->id);
    //     if ($re === null) {
    //         return $coreServerUtils->responseError($response, "Error fetching request by ID");
    //     }

    //     $result = (new Service())->updateStatus($requestUpdateStatus, $re->clientId);
    //     if ($result === null) {
    //         return $coreServerUtils->responseError($response, "Error updating status");
    //     }

    //     return $response;
    // }

    // public function getPaid(Request $request, Response $response, array $args): Response {
    //     $coreServerUtils = new CoreServerUtils();

    //     $jwtInfo = (new Utils\ServerUtils())->validateToken($request, $response);
    //     if ($jwtInfo === null) {
    //         return $response;
    //     }

    //     $id = $coreServerUtils->getPathVariableInt64($response, $args, 'id');
    //     if ($id === null) {
    //         return $response;
    //     }

    //     $paid = (new Service())->getPaidRequest($id);
    //     if ($paid === null) {
    //         return $coreServerUtils->responseError($response, "Error fetching paid request");
    //     }

    //     return $coreServerUtils->responseOK($response, $paid);
    // }
}
