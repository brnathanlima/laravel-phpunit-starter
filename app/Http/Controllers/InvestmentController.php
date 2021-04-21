<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvestmentController extends Controller
{
    public function index()
    {
        return $this->successResponse(
            InvestmentResource::collection(Investment::all())
        );
    }

    public function store(Request $request)
    {
        $requestArray = $request->validate([
            'user_id' => 'required|exists:users,id',
            'strategy_id' => 'required|exists:strategies,id',
            'amount' => 'required',
        ]);

        $successful = (bool)random_int(0, 1);
        $requestArray['successful'] = $successful;

        $strategy = Strategy::find($requestArray['strategy_id']);
        $multiplier = $successful ?
            $strategy->yield :
            $strategy->relief;
        $requestArray['returns'] = $requestArray['amount'] * $multiplier;
        $investment = Investment::create($requestArray);

        return $this->successResponse(
            new InvestmentResource($investment),
            true
        );
    }

    public function show(Investment $investment)
    {
        return $this->successResponse(
            new InvestmentResource($investment)
        );
    }

    public function update()
    {
        return $this->errorResponse(
            'You can\'t update an investment',
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function destroy()
    {
        return $this->errorResponse(
            'You can\'t delete an investment',
            Response::HTTP_UNAUTHORIZED
        );
    }
}
