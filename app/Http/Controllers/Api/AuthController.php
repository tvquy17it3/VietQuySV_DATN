<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
                'device_name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'code' => 401]);
            }

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)){
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized!',
                ]);
            }

            $user = User::where('email', $request->email)->first();
            $employee_id = "";
            if($user->employee != null){
                $employee_id = $user->employee->id;
            }

            $token =  $user->createToken($request->device_name)->plainTextToken;
            return response()->json([
                'status_code' => 200,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'employee_id' => $employee_id,
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error->getMessage(),
            ]);
        }
    }

    public function register(Request $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        return response()->json(['success' => 'Tạo thành công'], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => 'Logout Success'], 200);
    }

    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
}
