<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class chat extends Controller
{
    //
}
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require 'vendor/autoload.php';

use Api\Commons\Utils\ServerUtils;
use Api\Commons\Core\Utils\Server\ServerUtils as CoreServerUtils;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

class Server
{
    public function post(Request $request, Response $response, array $args): Response
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new ServerUtils())->validateToken($request, $response);
        if ($jwtInfo === null) {
            return $response;
        }

        $message = new Message();

        $body = $request->getParsedBody();
        $err = $coreServerUtils->parseBody($response, $body, $message);
        if ($err !== null) {
            return $coreServerUtils->responseError($response, $err);
        }

        $err = (new Repository())->insertMessage($message);
        if ($err !== null) {
            return $coreServerUtils->responseError($response, $err);
        }

        return $response;
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new ServerUtils())->validateToken($request, $response);
        if ($jwtInfo === null) {
            return $response;
        }

        $requestId = $coreServerUtils->getPathVariableInt64($response, $args, 'requestId');
        if ($requestId === null) {
            return $response;
        }

        $contactId = $coreServerUtils->getPathVariableInt64($response, $args, 'contactId');
        if ($contactId === null) {
            return $response;
        }

        $queryMessages = (new Repository())->getMessages($requestId, $contactId);
        if ($queryMessages === null) {
            return $coreServerUtils->responseError($response, $queryMessages);
        }

        return $coreServerUtils->responseOK($response, $queryMessages);
    }

    public function getList(Request $request, Response $response, array $args): Response
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new ServerUtils())->validateToken($request, $response);
        if ($jwtInfo === null) {
            return $response;
        }

        $requestId = $coreServerUtils->getPathVariableInt64($response, $args, 'requestId');
        if ($requestId === null) {
            return $response;
        }

        $contactId = $coreServerUtils->getPathVariableInt64($response, $args, 'contactId');
        if ($contactId === null) {
            return $response;
        }

        $queryMessages = (new Repository())->getMessagesList($requestId, $contactId);
        if ($queryMessages === null) {
            return $coreServerUtils->responseError($response, $queryMessages);
        }

        return $coreServerUtils->responseOK($response, $queryMessages);
    }
}

$app = AppFactory::create();

$app->post('/post', [Server::class, 'post']);
$app->get('/get', [Server::class, 'get']);
$app->get('/getList', [Server::class, 'getList']);

$app->run();



