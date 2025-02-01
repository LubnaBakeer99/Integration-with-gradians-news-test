<?php


namespace App\Helpers;

use Illuminate\Http\Response;

class ResponseHelper
{
    public static function success($data = "operation Success")
    {
        return response()->json(['status' => 'OK', 'data' => $data], 200);
    }
     
 
    public static function create($data = null ,$message=null)
    {
        return response()->json(['status' => 'OK', 'data' => $data ,'message'=>$message], 201);
    }

    

    public static function DataNotFound($message = "Data Not Found")
    {
        return response()->json(['status' => 0, 'message' => $message], 400);
    }

    public static function AlreadyExists($message = "Already Exists")
    {
        return response()->json(['status' => 0, 'message' => $message], 400);
    }

    public static function authorizationFail($message = "Not Authorized")
    {
        return response()->json(['status' => 0, 'message' => $message ,'code' =>401], 401);
    }

    public static function authenticationFail($message = "Authentication Fail")
    {
        return response()->json(['status' => 0, 'message' => $message], 401);
    }

   
    public static function operationFail($message = "operation Fail")
    {
        return response()->json(['status' => 0, 'message' => $message], 500);
    }

    public static function successWithMessage($data  , $message)
    {
        return response()->json(['status' => true, 'message' =>$message ,'data' => $data ], 200);
    }

    public static function unauthorizedOutput()
    {

        return response()->json(
            [
                'status' => false,
                'code' => Response::HTTP_UNAUTHORIZED,
                'msg' => 'User not Authenticated',
                'data' => [],
            ], Response::HTTP_UNAUTHORIZED);
    }

   


}
