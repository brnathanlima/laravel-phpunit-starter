<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvestmentResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function index() {

        return $this->successResponse(
            UserResource::collection(User::all())
        );

    }

    public function store(Request $request) {

        $user = User::create(
            [
                'first_name' => $request->input('first_name'),
                'last_name'  => $request->input('last_name'),
                'email'      => $request->input('email')
            ]
        );
        Wallet::create(
            [
                'balance' => 0,
                'user_id' => $user->id
            ]
        );

        return $this->successResponse(
            new UserResource($user),
            true
        );
    }

    public function show(User $user) {

        return $this->successResponse(
            new UserResource($user)
        );

    }

    public function update(Request $request, User $user) {

        $user->update(
            $request->only(
                [
                    'first_name',
                    'last_name',
                    'email'
                ]
            )
        );

        return $this->successResponse(
            new UserResource($user)
        );
    }

    public function investments(User $user) {

        $userInvestments = $user->investments;

        return $this->successResponse(
            InvestmentResource::collection($userInvestments)
        );
    }

    public function destroy(User $user) {

        $user->delete();

        return $this->deleteResponse();
    }
}
