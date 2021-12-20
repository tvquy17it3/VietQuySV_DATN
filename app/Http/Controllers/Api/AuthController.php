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
                return response()->json([
                    'error' => $validator->errors(),
                    'status_code' => 401,
                    'message' => 'Lỗi xác thực đầu vào!',
                ]);
            }

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)){
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Sai email hoặc mật khẩu!',
                ]);
            }

            $user = User::where('email', $request->email)->first();
            if($user->employee != null){
                $employee_id = $user->employee->id;
                $token =  $user->createToken($request->device_name)->plainTextToken;
                return response()->json([
                    'status_code' => 200,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'employee_id' => $employee_id,
                    'message' => 'Đăng nhập thành công!',
                ]);
            }

            return response()->json([
                'status_code' => 401,
                'message' => 'Chưa tạo hồ sơ!',
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Đã có lỗi xảy ra!',
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

    public function save_key(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publickey' => 'required|string|',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status_code' => 401,
                'message' => 'Lỗi xác thực đầu vào!',
            ]);
        }

        $user = $request->user();
        $data = $request->only('publickey');
        $rs =  $user->fill($data)->save();
        if($rs){
            return response()->json([
                'status_code' => 200,
                'message' => 'Đã thêm vân tay!',
            ]);
        }
        return response()->json([
            'status_code' => 401,
            'message' => 'Đã có lỗi xảy ra!',
        ]);
    }

    public function verify_key(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payload' => 'required|string|',
            'signature' => 'required|string|',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status_code' => 401,
                'message' => 'Lỗi xác thực đầu vào!',
            ]);
        }

        $publicKey = $request->user()->publickey;

        // Convert publicKey in to PEM format (don't forget the line breaks).
        $publicKey_pem = "-----BEGIN PUBLIC KEY-----\n$publicKey\n-----END PUBLIC KEY-----";

        // Get public key.
        $key = openssl_pkey_get_public($publicKey_pem);
        $result = openssl_verify($request->payload, base64_decode($request->signature), $key, OPENSSL_ALGO_SHA256);

        if ($result == 1)
        {
            return response()->json([
                'status_code' => 200,
                'message' => 'Verified'
            ]);
        }

        return response()->json([
            'status_code' => 401,
            'message' => 'Unverified'
        ]);
   }
}
