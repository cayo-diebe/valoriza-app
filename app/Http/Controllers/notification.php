<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class notification extends Controller
{
    //
}
*/

//veio no conversor
namespace Notification;

//veio na maker
//namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Api\Commons\Utils;
use Api\Commons\Core\Utils\Server as CoreServerUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Server
{
    /**
     * @Route("/notifications", methods={"GET"})
     */
    public function get(Request $request)
    {
        $coreServerUtils = new CoreServerUtils();

        $jwtInfo = (new Utils\ServerUtils())->validateToken($request);
        if ($jwtInfo === null) {
            return;
        }

        $userId = $coreServerUtils->getPathVariableInt64($request, 'user_id');
        if ($userId === null) {
            $coreServerUtils->responseError(new Response(), 'Invalid user ID');
            return;
        }

        $repository = new Repository();
        $notification = $repository->getNotificationsByUserId($userId);
        if ($notification === null) {
            $coreServerUtils->responseError(new Response(), 'Error fetching notifications');
            return;
        }

        $coreServerUtils->responseOk(new Response(), $notification);
    }

    // /**
    //  * @Route("/notifications/{id}", methods={"GET"})
    //  */
    // public function getById(Request $request)
    // {
    //     $coreServerUtils = new CoreServerUtils();

    //     $jwtInfo = (new Utils\ServerUtils())->validateToken($request);
    //     if ($jwtInfo === null) {
    //         return;
    //     }

    //     $id = $coreServerUtils->getPathVariableInt64($request, 'id');
    //     if ($id === null) {
    //         return;
    //     }

    //     $repository = new Repository();
    //     $user = $repository->getExpertById($id);
    //     if ($user === null) {
    //         $coreServerUtils->responseError(new Response(), 'Error fetching user');
    //         return;
    //     }

    //     $coreServerUtils->responseOk(new Response(), $user);
    // }
}


