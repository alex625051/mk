<?php

namespace App\Http\Controllers;

use App\Actions\CheckSmsAction;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SmsRequest;
use App\Models\User;

class SanctumController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->prepareData();
        $confirmCode = rand(10000,999999);

        $user = User::updateOrCreate(
            ['phone'=> $validated['phone']],
            ['confirm_code'=> $confirmCode,'attempts'=>3]
        );

        return response()->json([
            "success"=>true,
            "message"=>$user->id
        ]);
    }

    public function sms(SmsRequest $request)
    {$data = $request->prepareData();
        $action = new CheckSmsAction($data['phone'],$data['confirm_code']);

        if (!$action->execute()) {
            return response()->json([
                "success" => false,
                "message" => $action->message
            ]);
        }

        return response()->json([
            'token' => $action->user->createToken('login')->plainTextToken,
            'success' => true
        ]);



    }
}
