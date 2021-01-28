<?php
   
namespace App\Http\Controllers\Api;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Laravel\Passport\Passport;

class RegisterController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error',
            ];
            $response['data'] = $validator->errors();
            return response()->json($response, 404);     
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  bcrypt($input['email']+''+$input['password']);
        $success['name'] =  $user->name;
        $response = [
            'success' => true,
            'data'    => $success,
            'message' => 'User registered successfully.',
        ];
        return response()->json($response, 200); 
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] = bcrypt($request->password); 
            $success['name'] =  $user->name;
            $response = [
                'success' => true,
                'data'    => $success,
                'message' => 'Successfully LoggedIn',
            ];
            return response()->json($response, 200); 
        } 
        else{ 
            $response = [
                'success' => false,
                'message' => 'Unauthorised',
            ];
            return response()->json($response, 404); 
        } 
    }
}