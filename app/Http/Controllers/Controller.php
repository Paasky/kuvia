<?php

namespace App\Http\Controllers;

use App\Managers\UserManager;
use App\Repositories\UserRepository;
use App\Traits\ManagesDatabaseTransactions;
use App\Traits\SendsResponses;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ManagesDatabaseTransactions, SendsResponses;

    public function __construct(Request $request)
    {
        if ($request->isMethod('POST') && ! $request->all()) {
            throw new BadRequestHttpException('No data or malformed JSON given');
        }
    }

    protected function getUser(int $id = null) : User
    {
        if ($id) {
            return User::findOrFail($id);
        }

        if (! $user = Auth::user()) {
            throw new BadRequestHttpException('No user logged in');
        }

        return $user;
    }
}
