<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use App\Model\Provider;
use App\Model\Report;
use App\Model\Mahaagent;
use Carbon\Carbon;
use App\Model\Api;
use App\User;
use App\Model\BBPSV2OpMapping;
use Illuminate\Support\Facades\Log;
use DB;


class Democontroller extends Controller
{
    protected $billapi; protected $kotakbillapi;
   
    public function index(Request $post, $type)
    {
      if(\Myhelper::hasRole(['admin','employee'])||!\Myhelper::can('billpayment_service')){
                abort(403);
            }
            $data['type'] = $type;
            $data['providers'] = Provider::where('type', $type)->where('status', "1")->get();
            

        $activeStatusOfAPI=DB::table('apis')->where('code','bbps')->value('status');
        if($activeStatusOfAPI== 0){
            return response()->json(['status' => "Recharge Service Currently Down."],400); 
          }
            //return view('Demo',['activeStatusOfAPI'=>$activeStatusOfAPI])->with($data);
           return view('Demo')->with($data);
            
   }

    public function fetchbill()
    {
        if(\Myhelper::hasRole(['admin','employee'])||!\Myhelper::can('billpayment_service')){
            abort(403);
        }

       
        
        $activeStatusOfAPI=DB::table('apis')->where('code','bbps')->value('status'); 
        if($activeStatusOfAPI== 0){
            return response()->json(['status' => "Recharge Service Currently Down."], 400);
            
        }


     
    }
    public function encryption()
    {
        $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://180.179.170.6:1012/portal/service/npciservice/aesencryptor',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            ));

            $response = curl_exec($curl);

            curl_close($curl);
    }

  

}
    /*{
    protected $billapi; protected $kotakbillapi;
 
    public function index(Request $post)
    {
        if (\Myhelper::hasRole(['admin','employee']) || !\Myhelper::can('billpayment_service')) {
            abort(403);
        }

       return view('service.bbpslic');
        
            $timestamp= time();
            $secret = "UFMwMDEyNGQ2NTliODUzYmViM2I1OWRjMDc2YWNhMTE2M2I1NQ=="; //bin2hex(random_bytes(32));
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
            $payload = json_encode(['timestamp' => time(),'partnerId' => 'PS001', 'reqid'=>time()]);
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
            
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
            
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
            
            //dd($jwt);
            
            $header = [  
                    'Accept: application/json',
                    'Content-Type: application/json',             
                    'Token:'.$jwt,
                    'Authorisedkey:MzNkYzllOGJmZGVhNWRkZTc1YTgzM2Y5ZDFlY2EyZTQ='
            ];
            
            
            $url="https://paysprint.in/service-api/api/v1/service/bill-payment/bill/fetchlicbill";
            $request=json_encode(array("canumber"=>"334489350",
                     "ad1"=> "pramodpatwa31@gmail.com",
                     "mode"=> "offline"));
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            $contents = curl_exec($ch);
            
            $response = curl_exec($ch);
            $err = curl_error($ch);
    dd($response);
        
        $data['test']="controllerValue";
        $data['type']='BBPS '.ucfirst($type);
         
        do {
            $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);
        
            
            $randTXN=rand(1111111,999999);
         
            $timestamp= time();
            $secret = "UFMwMDE0MmM4YmMxMWIyOWViZTZjM2I5MzU4ZDdmY2E4YmY5MzBl"; //bin2hex(random_bytes(32));
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
            $payload = json_encode(['timestamp' => $timestamp,'partnerId' => 'PS00142', 'reqid'=>"C".$randTXN]);
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
            
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
            
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
            
            require_once('JWTToken_Lib.php');
            $payload2 = array(
                "timestamp" =>  time() ,
                "partnerid" => "PS00142",
                "reqid"=> time(),
                "iss"=>12
                );
            $secret = "UFMwMDE0MmM4YmMxMWIyOWViZTZjM2I5MzU4ZDdmY2E4YmY5MzBl"; //bin2hex(random_bytes(32));
            $jwtToken=JWTToken_Lib::encode($payload2,$secret);
            
            
            //dd($jwt);
            
            $header = [  
                    'Accept: application/json', 
                    'Content-Type: application/json',             
                    'Token:'.$jwtToken
                 //   'Authorisedkey:MzNkYzllOGJmZGVhNWRkZTc1YTgzM2Y5ZDFlY2EyZTQ='
            ];
            
            
            $url="https://api.paysprint.in/api/v1/service/bill-payment/bill/getoperator";
            $request=array("mode"=>"online");
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            $contents = curl_exec($ch);
            
        
            //$response = curl_exec($curl);
            $err = curl_error($ch);
            curl_close($ch);
            
    //dd($jwt.'=='.$contents);
            $data['enableStatus']='';
            if ($err)
            {
              $data["operatorFetchError"]="Service down. Please try again";
              $data['enableStatus']='readonly';
            } 
            else {
               $jsonD=json_decode($contents,true);
    //dd($jsonD);
                $operatorName=array();
        		$operatorID=array();
        		if(isset($jsonD["data"])) {
            		foreach($jsonD["data"] as $item)
            		{
            		    if(strtoupper($item["category"])==strtoupper($type) ){
                			array_push($operatorName,$item['name']);
                		    array_push($operatorID,$item['id']);
                		    
                		    /* $post['v2opid']=$item['id'];
                		    $post['v2opname']=$item['name'];
                		    $action = BBPSV2OpMapping::create($post->all());*/
            		
                   /*    }
            		    
            		}
        		} else {
        		    $data["operatorFetchError"]="Could not fetch operators. Please try again. ";
        		    $data['enableStatus']='readonly';
        		}
              
            }
        
         $data['operatorName'] =$operatorName;
         $data['operatorID']=$operatorID;
         $data['txnid']=$randTXN;
         $data['pageTitle']=$type;
         
        return view('service.bbpselectricity')->with($data);
    }
    
    
    public function getBillDetails(Request $post) 
    {
        $rules = array(
            'email'    => 'required',
            'canumber' =>'required|numeric'
            'mobile' =>'required|numeric|digits:10'
        );

    $validator = \Validator::make($post->all(), $rules);
    if ($validator->fails()) {
        foreach ($validator->errors()->messages() as $key => $value) {
            $error = $value[0];
        }
        return response()->json(['status'=>'ERR', 'message'=> $error]);
    }


    //150921 Added API status check
    $api=Api::where('code','bbps')->first();
    if($api->status==0) {
        return response()->json(['status'=>'ERR', 'message'=> $error]);
    }


        $timestamp= time();
        $secret = "UFMwMDEyNGQ2NTliODUzYmViM2I1OWRjMDc2YWNhMTE2M2I1NQ=="; //bin2hex(random_bytes(32));
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['timestamp' => time(),'partnerId' => 'PS001', 'reqid'=>time()]);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        
        //dd($jwt);
        
        $header = [  
                'Accept: application/json',
                'Content-Type: application/json',             
                'Token:'.$jwt,
                'Authorisedkey:MzNkYzllOGJmZGVhNWRkZTc1YTgzM2Y5ZDFlY2EyZTQ='
        ];
        
        
        $url="https://paysprint.in/service-api/api/v1/service/bill-payment/bill/fetchlicbill";
        $request=json_encode(array(
                "canumber"=>$post->canumber,
                "ad1"=> $post->email,
                "mode"=> "offline"));
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $contents = curl_exec($ch);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $jsonD=json_decode($response) ;

        if (json_last_error() != JSON_ERROR_NONE) {
            return response()->json(['status'=>'ERR', 'message'=> "API call failed"]);
        }
        
        if($jsonD->response_code==1 && $jsonD->status=="true") {
            $output['status']='TXN';
            $output["username"]=isset($jsonD->bill_fetch->userName) ? $jsonD->bill_fetch->userName : "NA";
            $output["billNumber"]=isset($jsonD->bill_fetch->billNumber) ? $jsonD->bill_fetch->billNumber : "NA";
            $output["billAmount"]=isset($jsonD->bill_fetch->billAmount) ? $jsonD->bill_fetch->billAmount : 0;
            $output["dueFrom"]=isset($jsonD->bill_fetch->dueDate) ? $jsonD->bill_fetch->dueDate : "NA";
            $output["dueTo"]=isset($jsonD->bill_fetch->dueTo) ? $jsonD->bill_fetch->dueTo : "NA";
            $output["cellNumber"]=isset($jsonD->bill_fetch->cellNumber) ? $jsonD->bill_fetch->cellNumber : "NA";
            $output["validationId"]=isset($jsonD->bill_fetch->validationId) ? $jsonD->bill_fetch->validationId : "NA";
            $output["billId"]=isset($jsonD->bill_fetch->billId) ? $jsonD->bill_fetch->billId : "NA";
            $output["bill_fetch"]=$jsonD->bill_fetch;
            $output["ad2"]=$jsonD->ad2;
            $output["ad3"]=$jsonD->ad3;
            return response()->json($output);
        } else {
            return response()->json(['status'=>'ERR', 'message'=>isset($jsonD->message) ? $jsonD->message : "Detailes fetch failed"]);
        }

        
      
    }

    public function payBill(Request $post)
    {
            $rules = array(
                    'canumber' =>'required',
                    'email' =>'required'
                );
                
           
            $validator = \Validator::make($post->all(), $rules);
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $key => $value) {
                    $error = $value[0];
                }
                return response()->json(['status'=>'ERR', 'message'=> $error]);
            }
            
            
        $gpsdata =  geoip($post->ip());
         
        
        if (\Myhelper::hasRole(['admin','employee']) || !\Myhelper::can('billpayment_service')) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }
        
        $user = \Auth::user();
        $post['user_id'] = $user->id;
        if($user->status != "active"){
            return response()->json(['status' => "Your account has been blocked."], 400);
        }
        
         //150921 Added API status check
        $api=Api::where('code','bbps')->first();
        if($api->status==0) {
            return response()->json(['status' => "Service is temprorily down"], 400); 
        }
        
        
               
        $provider = Provider::where("recharge1",'licbbps')->first();

        if(!$provider){
          return response()->json(['status' =>"ERR", "message"=> "Operator Not Found"], 400); 
        }

        if($provider->status == 0){
            return response()->json(['status' =>"ERR", "message"=> "Operator Not Found currently down"], 400); 
        }

        if(!$provider->api || $provider->api->status == 0){
          return response()->json(['status' =>"ERR", "message"=> "Bill Payment Service Currently Down."], 400);
        }
        
        $post['provider_id']=$provider->id;
        
        
        $timestamp= time();
        $secret = "UFMwMDEyNGQ2NTliODUzYmViM2I1OWRjMDc2YWNhMTE2M2I1NQ=="; //bin2hex(random_bytes(32));
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['timestamp' => time(),'partnerId' => 'PS001', 'reqid'=>time()]);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        
        //dd($jwt);
        
        $header = [  
                'Accept: application/json',
                'Content-Type: application/json',             
                'Token:'.$jwt,
                'Authorisedkey:MzNkYzllOGJmZGVhNWRkZTc1YTgzM2Y5ZDFlY2EyZTQ='
        ];
        
        
        $url="https://paysprint.in/service-api/api/v1/service/bill-payment/bill/fetchlicbill";
        $request=json_encode(array("canumber"=>$post->canumber,
                 "ad1"=> $post->email,
                 "mode"=> "offline"));
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $contents = curl_exec($ch);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $jsonD=json_decode($response) ;

        if (json_last_error() != JSON_ERROR_NONE) {
            return response()->json(['status'=>'ERR', 'message'=> "API call failed"]);
        }
        
        if($jsonD->response_code==1 && $jsonD->status=="true") {
            $output['status']='TXN';
            $output["username"]=isset($jsonD->bill_fetch->userName) ? $jsonD->bill_fetch->userName : "NA";
            $output["billNumber"]=isset($jsonD->bill_fetch->billNumber) ? $jsonD->bill_fetch->billNumber : "NA";
            $output["billAmount"]=isset($jsonD->bill_fetch->billAmount) ? $jsonD->bill_fetch->billAmount : 0;
            $output["dueFrom"]=isset($jsonD->bill_fetch->dueDate) ? $jsonD->bill_fetch->dueDate : "NA";
            $output["dueTo"]=isset($jsonD->bill_fetch->dueTo) ? $jsonD->bill_fetch->dueTo : "NA";
            $output["cellNumber"]=isset($jsonD->bill_fetch->cellNumber) ? $jsonD->bill_fetch->cellNumber : "NA";
            $output["validationId"]=isset($jsonD->bill_fetch->validationId) ? $jsonD->bill_fetch->validationId : "NA";
            $output["billId"]=isset($jsonD->bill_fetch->billId) ? $jsonD->bill_fetch->billId : "NA";
            $output["bill_fetch"]=$jsonD->bill_fetch;
            $output["ad2"]=$jsonD->ad2;
            $output["ad3"]=$jsonD->ad3;
        } else {
            return response()->json(['status'=>'ERR', 'message'=>isset($jsonD->message) ? $jsonD->message : "Detailes fetch failed"]);
        }
        
        //$post['amount']=$output['billAmount'];
        $post['amount']=1; //=====================================================================================================================================
        
        if($user->mainwallet - $this->mainlocked() < $post->amount){
          return response()->json(['status'=> ' Low Balance, Kindly recharge your wallet.'], 400);
        }
        
        do {
            $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);
            
        
        $post['profit'] = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
        
        $debit = User::where('id', $user->id)->decrement('mainwallet', $post->amount - $post->profit);
                if ($debit) {
                   
                    $insert = [
                        'number' => $post->canumber,
                        'mobile' => $post->email,
                        'provider_id' => $provider->id,
                        'api_id' => $provider->api->id,
                        'amount' => $post->amount,
                        'profit' => $post->profit,
                        'txnid' => $post->txnid,
                        'payid' => $output['cellNumber'],
                        'option1' => $output['username'],
                        'option2' => $output['billNumber'],
                        'status' => 'pending',
                        'user_id'    => $user->id,
                        'credit_by'  => $user->id,
                        'rtype'      => 'main',
                        'via'        => 'portal',
                        'balance'    => $user->mainwallet,
                        'trans_type' => 'debit',
                        'product'    => 'billpay'
                    ];
                    
                    $report = Report::create($insert);
                    
                    $timestamp= time();
                    $secret = "UFMwMDEyNGQ2NTliODUzYmViM2I1OWRjMDc2YWNhMTE2M2I1NQ=="; //bin2hex(random_bytes(32));
                    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
                    $payload = json_encode(['timestamp' => time(),'partnerId' => 'PS001', 'reqid'=>time()]);
                    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
                    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
                    
                    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
                    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
                    
                    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
                    
                    //dd($jwt);
                    
                    $header = [  
                            'Accept: application/json',
                            'Content-Type: application/json',             
                            'Token:'.$jwt,
                            'Authorisedkey:MzNkYzllOGJmZGVhNWRkZTc1YTgzM2Y5ZDFlY2EyZTQ='
                    ];
                    
                    
                    $url="https://paysprint.in/service-api/api/v1/service/bill-payment/bill/paylicbill";
                    $request=json_encode(array
                            ("canumber"=>$post->canumber,
                              "amount"=>$output['billAmount'],
                              "ad1"=>$post->email,
                              "ad2"=>$output['ad2'],
                              "ad3"=>$output['ad3'],
                              "referenceid"=>"TEST0104221928",
                              "latitude"=>$gpsdata->lat,
                              "longitude"=>$gpsdata->lon,
                              "bill_fetch"=>$output['bill_fetch'],
                              "mode"=>"offline"
                             ));
                    
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                    $response = curl_exec($ch);
                    $err = curl_error($ch);
                    $jsonD=json_decode($response) ;
            
                    if (json_last_error() != JSON_ERROR_NONE) {
                        return response()->json(['status'=>'ERR', 'message'=> "API call failed"]);
                    }
                    
                    
                    
                    
                    
                    if ($err)
                    {
                      return response()->json(['status'=>'ERR', 'message'=> "API response failed"]);
                    } 
                    else {
                       $jsonD=json_decode($response,true) ;
                       
                       if (json_last_error() != JSON_ERROR_NONE) {
                           return response()->json(['status'=>'ERR', 'message'=> "JSPARSE Err"]);
                       }
                       
                       //$custName=$jsonD["name"];
                       
                       //$billdata=$jsonD["bill_fetch"];
                        //dd($jsonD);
                        //dd($jsonD["status"]==true);
        Log::info("BBPSLic",["Response"=>$jsonD, "jsonD Status"=>$jsonD["status"]]);                
        
                        if($jsonD["status"]==true && $jsonD["response_code"]=="1")  //payment success
                        {
                            Log::info("BBPSLic",["InSuccess with response code 1"]);
                            $update['status'] = "success";
                            $update['refno'] = $jsonD["ackno"];
                            $update['option4']=$response;
                            $update['description'] = "Billpayment Accepted (".$jsonD["message"]. ')';
                            $update['payid']=$jsonD["operatorid"];
                        } elseif($jsonD["status"]==false && $jsonD["response_code"]=="2") {  //payment failed
                        Log::info("BBPSLic",["InFailed with response code 2"]);
                            $update['status'] = "failed";
                            $update['payid'] = "failed";
                            $update['option4']=$response;
                            $update['description'] = (isset($jsonD["message"])) ? $jsonD["message"] : "failed";
                        }elseif($jsonD["status"]==false && $jsonD["response_code"]=="18") {  //validation err
                        Log::info("BBPSLic",["InFailed with validation err"]);
                            $update['status'] = "failed";
                            $update['payid'] = "failed";
                            $update['option4']=$response;
                            $update['description'] = (isset($jsonD["message"])) ? $jsonD["message"] : "failed";
                        } else {
                            Log::info("BBPSLic",["InPending with unkown err"]);
                            $update['status'] = "pending";
                            $update['payid'] = $jsonD["response_code"];
                            $update['option4']=$response;
                            $update['description'] = (isset($jsonD["message"])) ? $jsonD["message"] : "failed";
                        }
                        
                        if($update['status'] == "success" || $update['status'] == "pending"){
                            Log::info("BBPSLic",["Updating record as".$update["status"]]);
                            
                            Report::where('id', $report->id)->update($update);
                            \Myhelper::commission($report);
                            return response()->json(['status' => "TXN", 'message' => $update['description'], "created_at"=>$report->created_at,
                                    "amount"=>$report->amount,
                                    "txnid"=>$report->txnid,
                                    "number"=>$report->number,
                                    "id"=>$report->id], 200);
                        }elseif($update['status']=="failed"){
                            Log::info("BBPSLic",["Updating record as2 ".$update["status"]]);
                            User::where('id', $user->id)->increment('mainwallet', $post->amount - $post->profit);
                            Report::where('id', $report->id)->update($update);
                            return response()->json(['status' => "ERR", 'message' => $update['description']], 200);
                        }
                        
                        Log::info("BBPSLic",["TXN completed".$post->txnid]);
                        return response()->json(['status' => "TUP", 'message' => "Transaction under processing"], 200);
                        
                      
                    }
                }
        
    }
    
    public function statusenquiry(Request $post) 
    {
        //return response()->json(['status'=>'ERR', 'message'=> "Permission not allowed"]);
        
        $post['referenceid']= $post->referenceid;    //number = canumber
        
         $rules = array(
                    'referenceid'    => 'required',
                );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(['status'=>'ERR', 'message'=> $error]);
        }
        
            //$curl = curl_init();
            
           // $param=
            
            $timestamp= time();
            $secret = "UFMwMDE0MmM4YmMxMWIyOWViZTZjM2I5MzU4ZDdmY2E4YmY5MzBl"; //bin2hex(random_bytes(32));
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
            $payload = json_encode(['timestamp' => time(),'partnerId' => 'PS00142', 'reqid'=>time()]);
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
            
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
            
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
            
            //dd($jwt);
            
            $header = [  
                    'Accept: application/json',
                    'Content-Type: application/json',             
                    'Token:'.$jwt
                //    'Authorisedkey:MzNkYzllOGJmZGVhNWRkZTc1YTgzM2Y5ZDFlY2EyZTQ='
            ];
            
            
            $url="https://api.paysprint.in/api/v1/service/bill-payment/bill/status";
            $request=json_encode(array("referenceid"=>$post->referenceid));
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            $contents = curl_exec($ch);
            
            $response = curl_exec($ch);
            $err = curl_error($ch);
           // dd($response);
            curl_close($ch);
            
            if ($err)
            {
              return response()->json([
                                    'statuscode' => "ERR",
                                    'data'       => [
                                        "customername" => "",
                                        "duedate"      => "",
                                        "dueamount"       => "",
                                    ]
                                ], 200);
            } 
            else {
                
               $jsonD=json_decode($response,true) ;
               $custName=$jsonD["name"];
               
               $billdata=$jsonD["bill_fetch"];
               if($jsonD['status']==true) {
                   
               }
               return response()->json([
                                    'statuscode' => "TXN",
                                    'data'       => [
                                        "customername" => $jsonD["name"],
                                        "duedate"      => $jsonD["duedate"],
                                        "dueamount"       => $jsonD["amount"],
                                        
                                        "referenceid" =>$post->txnid,
                                        "latitude" =>"12.12",
                                        "longitude" =>"23.23",
                                        "mode" =>"online",
                                        "bill_fetch" =>$billdata
                                        
                                    ]
                                ], 200);
              
            }
    }
    
     
}*/