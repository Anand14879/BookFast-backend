<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;


class UserController extends Controller
{
    //
    function register(Request $request){
        try {
        // Attempt to save the user to the database
        $user = new User;
        $user -> name=$request->input('name');
        $user -> email= $request->input('email');
        $user -> password =Hash::make( $request -> input('password'));
        $user -> save();
        return $user;
    } catch (QueryException $exception) {
        $errorCode = $exception->errorInfo[1];
        if($errorCode == '1062'){
            // Duplicate entry exception
            return Response::json([
                'message' => 'The email has already been taken.'
            ], 409);
        }
    }
    }
}
