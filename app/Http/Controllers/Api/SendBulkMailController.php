<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Validator;
use App\Jobs\SendBulkEmailTest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use App\Models\User;
use App\Mail\BulkMailTask;
use Mail;
use Illuminate\Support\Facades\Storage;

class SendBulkMailController extends Controller
{
    
    /**
     * Dispatch a Job
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendBulkMail(Request $request)
    {
        $input = $request->all();
        $mailArray=[];
        $count=0;
        $response = [
            'success' => false,
            'message' => 'Something Went Wrong',
        ];
        $validator = Validator::make($input, [
            'api_token'=>'required',
            'emails' =>'required|array'
        ]);
        if($validator->fails()){
            $response['message'] = "Validation Error";
            $response['data'] = $validator->errors();
            return response()->json($response, 400);         
        }   

        ######################### CHECK TOKEN ##############################
        $user = User::where('remember_token',$input['api_token'])->count(); 
        if ($user<=0){
            $response['message'] = "UnAuthorized USer";
            $response['data'] = $validator->errors();
            return response()->json($response, 400);      
        }
        
        #######################################################
        ############# Custom Validator for File Chaeck ##############
        #######################################################
        Validator::extend('is_file',function($attribute, $value, $params, $validator) {
            if ($this->is_base64_encoded($value)){
                $file_type = base64_decode($value);
                $f = finfo_open();
                $result = finfo_buffer($f, $file_type, FILEINFO_MIME_TYPE);
                return in_array($result,['text/plain','image/png','image/jpeg','application/msword']);
            }else{
                return false;
            }            
        },"Invalid File type");
        
        foreach($input['emails'] as $mail){
            $validator = Validator::make($mail, [
                'toEmail' => 'required|email',
                'subject' => 'required',
                'body' => 'required',
                "attachemnts"  => "array"
            ]);
            
            if($validator->fails()) {
                $response['data'] = $validator->errors();
                return response()->json($response, 404); 
            }
            if(array_key_exists('attachemnts',$mail) && sizeof($mail['attachemnts'])>0){
                foreach($mail['attachemnts'] as $attch){
                    $validator = Validator::make($attch, [
                        'value' =>'required|is_file',
                        'name'=>'required'
                    ]);
                    if($validator->fails()) {
                        $response['data'] = $validator->errors();
                        return response()->json($response, 404); 
                    }
                }
            }
            $mailArray[$count]=$mail;
            if (!array_key_exists('attachemnts',$mail)){
                $mailArray[$count]['attachemnts']=[];
            }
            $count++;
        }    
        
        /**** Now Dispatch a Job ***** */
        SendBulkEmailTest::dispatch(new SendBulkEmailTest($mailArray))->onQueue('sendingMail');

        $response['success'] = true;
        $response['message'] = 'Done';

        return response()->json($response, 200);
    }
    function is_base64_encoded($data)
    {
        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
            return true;
        } else {
            return false;
        }
    }
}