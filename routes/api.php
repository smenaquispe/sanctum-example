<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();
    if(!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid email or password'], 401);
    }

    return response()->json([
        'user' => [
            'name' => $user->name,
            'email' => $user->email,
        ],
        'token' => $user->createToken('api')->plainTextToken
    ]);
});

Route::get('/post/create', function (Request $request) {
    return response()->json([
        'post' => [
            'title' => $request->title,
            'body' => $request->body,
        ],
    ]);
})->middleware(['auth:sanctum', 'ability:create-post']);