<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class analysis extends Controller
{
    //
}*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

class Server
{
    public function post(Request $request, Response $response, array $args): Response
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = Utils::validateToken($request, $response);
        if ($jwtInfo === null) {
            return $response;
        }

        $analysis = new Analysis();

        $body = $request->getParsedBody();
        $err = $coreServerUtils->parseBody($response, $body, $analysis);
        if ($err !== null) {
            return $coreServerUtils->responseError($response, $err);
        }

        $err = Service::insertOrUpdateAnalysis($analysis, $jwtInfo->userId);
        if ($err !== null) {
            return $coreServerUtils->responseError($response, $err);
        }

        return $response;
    }

    public function getById(Request $request, Response $response, array $args): Response
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = Utils::validateToken($request, $response);
        if ($jwtInfo === null) {
            return $response;
        }

        $id = $coreServerUtils->getPathVariableInt64($response, $args, 'id');
        if ($id === null) {
            return $response;
        }

        $analysis = Repository::getById($id);
        if ($analysis === null) {
            return $coreServerUtils->responseError($response, 'Analysis not found');
        }

        return $coreServerUtils->responseOK($response, $analysis);
    }
}

class CoreServerUtils
{
    public function parseBody(Response $response, $body, &$analysis)
    {
        // Implement body parsing logic here
        return null;
    }

    public function responseError(Response $response, $err)
    {
        // Implement error response logic here
        return $response->withStatus(500)->withJson(['error' => $err]);
    }

    public function responseOK(Response $response, $data)
    {
        // Implement success response logic here
        return $response->withStatus(200)->withJson($data);
    }

    public function getPathVariableInt64(Response $response, $args, $key)
    {
        if (!isset($args[$key])) {
            return null;
        }
        return (int)$args[$key];
    }
}

class Utils
{
    public static function validateToken(Request $request, Response $response)
    {
        // Implement token validation logic here
        return (object)['userId' => 1]; // Dummy implementation
    }
}

class Service
{
    public static function insertOrUpdateAnalysis($analysis, $userId)
    {
        // Implement insert or update logic here
        return null;
    }
}

class Repository
{
    public static function getById($id)
    {
        // Implement get by id logic here
        return (object)['id' => $id, 'data' => 'sample data']; // Dummy implementation
    }
}

$app = AppFactory::create();

$app->post('/analysis', [Server::class, 'post']);
$app->get('/analysis/{id}', [Server::class, 'getById']);

$app->run();



