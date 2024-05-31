<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserController extends Controller
{
    public function register(Request $request)
    {
        $code = Str::random(12);
        $secret = Str::random(32);

        $user = User::createOrFirst([
            'code' => $code,
            'secret' => HASH::make($secret)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'auth_recover_secret' => $code.':'.$secret,
            'token' => $token,
        ]);
    }
    
}