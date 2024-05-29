<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function ($email, $senha){
    return 'Primeiro endpoint';
} );

// -----------------------------------------------------------------------------------------------------------------------------------------------
// router.go inteirinho convertido
// ----------------------------------------------------------------------------------------------------------------------------------------

<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

if (empty(getenv('JWTSECRET'))) {
    error_log("HTTP server unable to start, expected a JWTSECRET for JWT auth");
    exit(0);
}

// Middleware for authentication
$authMiddleware = function (Request $request, $handler) {
    // Implement your authentication logic here
    return $handler->handle($request);
};

// Authenticate
$app->post('/auth/login', function (Request $request, Response $response) {
    $authenticateServer = new AuthenticateServer();
    return $authenticateServer->authenticate($request, $response);
});

// User routes
$app->group('', function (RouteCollectorProxy $group) {
    $group->post('/user/register', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->postRegister($request, $response);
    });

    $group->post('/user/activate', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->activate($request, $response);
    });

    $group->get('/auth/me', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->getUserInfo($request, $response);
    });

    $group->post('/user', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->insertUser($request, $response);
    });

    $group->get('/user/experts', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->getExperts($request, $response);
    });

    $group->get('/user/get-by-id/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $user = new UserServer();
        return $user->getById($request, $response, $args);
    });

    $group->post('/user/inactive/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $user = new UserServer();
        return $user->delete($request, $response, $args);
    });

    $group->put('/user', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->update($request, $response);
    });

    $group->post('/user/update-thumbnail', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->postThumb($request, $response);
    });

    $group->post('/user/registration', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->postRegistration($request, $response);
    });

    $group->get('/user/registration', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->getRegistration($request, $response);
    });

    $group->get('/user/registration/byuserid', function (Request $request, Response $response) {
        $user = new UserServer();
        return $user->getRegistrationByUserId($request, $response);
    });
})->add($authMiddleware);

// Category routes
$app->group('', function (RouteCollectorProxy $group) {
    $group->post('/category', function (Request $request, Response $response) {
        $category = new CategoryServer();
        return $category->post($request, $response);
    });

    $group->get('/category', function (Request $request, Response $response) {
        $category = new CategoryServer();
        return $category->get($request, $response);
    });

    $group->get('/category/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $category = new CategoryServer();
        return $category->getById($request, $response, $args);
    });

    $group->get('/category/active', function (Request $request, Response $response) {
        $category = new CategoryServer();
        return $category->getForClient($request, $response);
    });
})->add($authMiddleware);

// Request routes
$app->group('', function (RouteCollectorProxy $group) {
    $group->post('/request', function (Request $request, Response $response) {
        $requests = new RequestsServer();
        return $requests->post($request, $response);
    });

    $group->get('/request', function (Request $request, Response $response) {
        $requests = new RequestsServer();
        return $requests->get($request, $response);
    });

    $group->get('/request/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $requests = new RequestsServer();
        return $requests->getById($request, $response, $args);
    });

    $group->delete('/request/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $requests = new RequestsServer();
        return $requests->delete($request, $response, $args);
    });

    $group->post('/request/set-expert/{id:[0-9]+}/{expert_id:[0-9]+}', function (Request $request, Response $response, $args) {
        $requests = new RequestsServer();
        return $requests->setExpert($request, $response, $args);
    });

    $group->put('/request/{id:[0-9]+}/accept', function (Request $request, Response $response, $args) {
        $requests = new RequestsServer();
        return $requests->acceptExpert($request, $response, $args);
    });

    $group->put('/request/{id:[0-9]+}/reject', function (Request $request, Response $response, $args) {
        $requests = new RequestsServer();
        return $requests->rejectExpert($request, $response, $args);
    });

    $group->put('/request/status', function (Request $request, Response $response) {
        $requests = new RequestsServer();
        return $requests->updateStatus($request, $response);
    });

    $group->get('/paid/request/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $requests = new RequestsServer();
        return $requests->getPaid($request, $response, $args);
    });
})->add($authMiddleware);

// Analysis routes
$app->post('/request/analysis', function (Request $request, Response $response) {
    $analysis = new AnalysisServer();
    return $analysis->post($request, $response);
})->add($authMiddleware);

// Document routes
$app->group('', function (RouteCollectorProxy $group) {
    $group->post('/document', function (Request $request, Response $response) {
        $document = new DocumentServer();
        return $document->post($request, $response);
    });

    $group->post('/pdf', function (Request $request, Response $response) {
        $document = new DocumentServer();
        return $document->postPdf($request, $response);
    });
})->add($authMiddleware);

// Payment routes
$app->group('', function (RouteCollectorProxy $group) {
    $group->post('/payment', function (Request $request, Response $response) {
        $payment = new PaymentServer();
        return $payment->post($request, $response);
    });

    $group->get('/payment', function (Request $request, Response $response) {
        $payment = new PaymentServer();
        return $payment->get($request, $response);
    });
})->add($authMiddleware);

// Notification routes
$app->get('/notifications/{user_id:[0-9]+}', function (Request $request, Response $response, $args) {
    $notification = new NotificationServer();
    return $notification->get($request, $response, $args);
})->add($authMiddleware);

// Chat routes
$app->group('', function (RouteCollectorProxy $group) {
    $group->post('/chat-message', function (Request $request, Response $response) {
        $chat = new ChatServer();
        return $chat->post($request, $response);
    });

    $group->get('/chat-message/{requestId:[0-9]+}/{contactId:[0-9]+}', function (Request $request, Response $response, $args) {
        $chat = new ChatServer();
        return $chat->get($request, $response, $args);
    });

    $group->get('/chat-message/list/{requestId:[0-9]+}/{contactId:[0-9]+}', function (Request $request, Response $response, $args) {
        $chat = new ChatServer();
        return $chat->getList($request, $response, $args);
    });
})->add($authMiddleware);

// Price routes
$app->group('', function (RouteCollectorProxy $group) {
    $group->post('/price', function (Request $request, Response $response) {
        $price = new PriceServer();
        return $price->updatePrice($request, $response);
    });

    $group->get('/price', function (Request $request, Response $response) {
        $price = new PriceServer();
        return $price->getPriceById($request, $response);
    });
})->add($authMiddleware);

$app->run();