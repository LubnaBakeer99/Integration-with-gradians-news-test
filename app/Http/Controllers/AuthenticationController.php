<?php
 
 namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\ResponseHelper;
  
  
class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $formData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];
  
        $formData['password'] = bcrypt($request->password);
  
        $user = User::create($formData); 
        $token =  $user->createToken('passportToken')->accessToken;

  
        return ResponseHelper::success(
            [
                'user' =>$user,
                'token'=>$token
            ]
        );
          
    }
  
    public function login(Request $request)
    {
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password
        ];
  
        if (Auth::attempt($credentials)) 
        {
            $user =Auth::user();
            $token = $user->createToken('passportToken')->accessToken;
            return ResponseHelper::success(
                [
                    'user' =>$user,
                    'token'=>$token
                ]
            );
        }
  
       return ResponseHelper::uthenticationFail(); 
  
    }
}