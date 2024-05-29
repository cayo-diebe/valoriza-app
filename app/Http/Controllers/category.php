<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class category extends Controller
{
    //
}
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

class Server
{
    public function post(Request $request, Response $response, array $args): Response
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->validateToken($request, $response);
        if ($jwtInfo === null) {
            return $response;
        }

        $body = $request->getBody()->getContents();
        $m = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $coreServerUtils->responseError($response, json_last_error_msg());
        }

        // corrigir um erro onde o id em ativar vem como int64 e
        // na hoa de fazer update vem como string
        $category = new Category();

        foreach ($m as $k => $v) {
            if ($k === '_id') {
                $category->id = (int)$v;
            }
            if ($k === 'name') {
                $category->name = (string)$v;
            }
            if ($k === 'parent') {
                $category->parentId = (int)$v;
                $category->parentIdValid = true;
            }
        }

        $service = new Service();
        $err = $service->insertCategory($category);
        if ($err !== null) {
            $message = ['message' => 'Erro ao Inserir categoria.'];
            return $coreServerUtils->response($response, 400, $message);
        }

        $message = ['message' => 'Categoria criada com sucesso.'];
        return $coreServerUtils->response($response, 201, $message);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->validateToken($request, $response);
        if ($jwtInfo === null) {
            return $response;
        }

        $repository = new Repository();
        $categories = $repository->getCategories();
        if ($categories === null) {
            $message = ['message' => 'Erro ao carregar categorias.'];
            return $coreServerUtils->response($response, 400, $message);
        }

        if (count($categories) === 0) {
            return $coreServerUtils->responseOK($response, []);
        }

        return $coreServerUtils->responseOK($response, $categories);
    }

    public function getById(Request $request, Response $response, array $args): Response
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils())->validateToken($request, $response);
        if ($jwtInfo === null) {
            return $response;
        }

        $id = $coreServerUtils->getPathVariableInt64($response, $args, 'id');
        if ($id === null) {
            return $response;
        }

        $repository = new Repository();
        $category = $repository->getById($id);
        if ($category === null) {
            return $coreServerUtils->responseError($response, 'Category not found');
        }

        return $coreServerUtils->responseOK($response, $category);
    }

    public function getForClient(Request $request, Response $response, array $args): Response
    {
        $coreServerUtils = new CoreServerUtils();

        $repository = new Repository();
        $categories = $repository->getCategories();
        if ($categories === null) {
            $message = ['message' => 'Erro ao carregar categorias.'];
            return $coreServerUtils->response($response, 400, $message);
        }

        if (count($categories) === 0) {
            return $coreServerUtils->responseOK($response, []);
        }

        return $coreServerUtils->responseOK($response, $categories);
    }
}



