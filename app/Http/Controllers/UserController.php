<?php

namespace App\Http\Controllers;

use App\Permissions\UserPermissions;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function list() : array
    {
        UserPermissions::viewAllUsers();
        return User::all();
    }

    public function get(int $id) : User
    {
        $user = $this->getUser($id);
        UserPermissions::viewUser($user);
        return $user;
    }

    public function edit(Request $request, int $id) : User
    {
        $user = $this->getUser($id);
        UserPermissions::editUser($user);
        $user->update($request->all());
        return $user;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id) : bool
    {
        $user = $this->getUser($id);
        UserPermissions::deleteUser($user);
        $user->delete();
        return true;
    }

    public function collages(int $id) : array
    {
        $user = $this->getUser($id);
        UserPermissions::viewUser($user);
        return $user->collages()->get();
    }

    public function moderatedCollages(int $id) : array
    {
        $user = $this->getUser($id);
        UserPermissions::viewUser($user);
        return $user->collageModerators()->get();
    }

    public function purchases(int $id) : array
    {
        $user = $this->getUser($id);
        UserPermissions::viewUser($user);
        return $user->purchases()->get();
    }

    public function subscriptions(int $id) : array
    {
        $user = $this->getUser($id);
        UserPermissions::viewUser($user);
        return $user->subscriptions()->get();
    }

    public function friends(int $id) : array
    {
        $user = $this->getUser($id);
        UserPermissions::viewUser($user);
        return $user->friends();
    }
}
