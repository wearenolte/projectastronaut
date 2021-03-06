<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Mail;
use PDF;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Ajax extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
		return json_encode(array("response" => "200", "message" => "Ajax is under construction"));
    }

	/**
     * Upload file
     *
     * @return Response
     */
    public function upload()
    {
        //

		return json_encode(array("response" => "200", "message" => "Ajax Upload is under construction"));
    }

	/**
     * Add New Company
     *
     * @return Response
     */
    public function newCompany()
    {
        //

		return json_encode(array("response" => "200", "message" => "Ajax New Company is under construction"));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function insertuser()
    {
    	if (isset($_POST['userdata']))
    	{
            // all variable names in here are confusing
    		$newdata = $_POST['userdata'];
    		
            $find = DB::table('prospectusers')->where('email','=',$newdata[3])->first();
            if($find != null)
            {
                return 'duplicate';
            }
            else{
    		$indata = ['email' => $newdata[3], 'full_name' => $newdata[1], 'title' => $newdata[2], 'company_id' => $newdata[4], 'fc_gravatar' => $newdata[0], 'company' => $newdata[5],'created_at' => date("Y-m-d H:i:s"),"active" => 1];
    		
    		$id = DB::table('prospectusers')->insertGetId($indata);
    		
            $inurl = $newdata[4] . '_' . $newdata[3] . '=' . $id;

            $hased = base64_encode($inurl);

            $urldata = ['company_id' => $newdata[4], 'user_id' => $id, 'url_hash' => $hased];

            DB::table('shorturls') ->insert($urldata);


            if($newdata[6] == 'reply'){
            //add this contact to REPLY
                $reply = array();
                $reply['campaignId'] = 24295;
                $reply['email'] = $indata['email'];

                if (strpos($indata['full_name'], ' ') !== false) {
                    $name_array = explode(' ', $indata['full_name'],2);
                    $reply['lastName'] = $name_array[1];
                    $reply['firstName'] = $name_array[0];
                }else{
                    $reply['firstName'] = $indata['full_name'];
                }

                // $reply['firstName'] = $indata['full_name'];
                $reply['company'] = $indata['company'];
                $reply['customFields'] = array(array(
                    "key" => "PAURL",
                    "value" => "http://vector.ipullrank.com/display-report?report=".$hased."&company=".str_replace(' ', '_', $indata['company'])
                    ));

                $api_data = json_encode($reply);

                $service_url = 'https://run.replyapp.io/api/v1/actions/addandpushtocampaign';

                // make curl command its own function just in case you need to do something like this again in another place
                $ch = curl_init($service_url); 
                curl_setopt($ch, 
                            CURLOPT_HTTPHEADER, 
                            array('Content-Type: application/json', 
                            ''));
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $api_data);
                curl_setopt($ch, CURLOPT_POST, 1);
                $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $server_output = curl_exec ($ch);
                curl_close ($ch);
            }

		    return $id;
            }
		}else{
		return 'false call';
		};
    }
    
    public function deleteuser()
    {
    	if (isset($_POST['deleteuser']))
    	{
    		$delete = $_POST['deleteuser'];

            $find = DB::table('prospectusers')->where('email','=',$delete)->first();

            $id = $find -> id;
    		
    		DB::table('prospectusers')->where('email', '=', $delete)->delete();

            DB::table('shorturls')->where('user_id', '=', $id)->delete();

    		
    		//DB::insert('insert into prospectusers (email, full_name, title, company_id, fc_gravatar, company) values (?, ?, ?, ?, ?, ?)', [$newuserdata[3], $newuserdata[1], $newuserdata[2], $newuserdata[4], $newuserdata[0], $newuserdata[5]]);
    		
		return "success!";
		
		}
       
        else
        {
		return 'false call';
		};
    }
    
    public function updateuser()
    {
    	if (isset($_POST['update']))
    	{
    		$updater= $_POST['update'];
    		
    		$updateitem = ['email' => $updater[3], 'full_name' => $updater[1], 'title' => $updater[2], 'fc_gravatar' => $updater[0],'updated_at' => date("Y-m-d H:i:s")];

    		DB::table('prospectusers')
                ->where('email','=',$updater[5])
                ->update($updateitem);
    		
    		$uid = DB::table('prospectusers')
                ->where('email', '=', $updater[3])
                ->get();
            
            $output = $uid[0] -> id;
    		
            $inurl = $updater[4] . '_' . $updater[3] . '=' .$output;

            $hased = base64_encode($inurl);

            DB::table('shorturls')
                ->where('user_id', '=', $output)
                ->update(['url_hash' => $hased]);

		    return $output;
		
		}

        else{
		  return 'false call';
		};
    }
    
    public function loadcode()
    {
    	if (isset($_POST['codeid']))
    	{	
    		$companyid = $_POST['codeid'];
    		$code = DB::table('prospects')
                ->where('id', '=', $companyid)
                ->select('code_zone')
                ->get();
    		
    		$output = $code[0] -> code_zone;
    		
    		return $output;
    		
		}else{
		return 'false call';
		};
    }
    
    public function savecode()
    {
    	if (isset($_POST['scode']))
    	{	
			$savecode = $_POST['scode'];
    		
    		DB::table('prospects')
                ->where('id', '=', $savecode[0])
                ->update(['code_zone' => $savecode[1]]);
    		
    		return 'success';
    		
		}else{
		return 'false call!!';
		};
    }

    public function userurl()
    {
        if (isset($_POST['userid']))
        {   
            $user = $_POST['userid'];
            
            $hashurl = DB::table('shorturls')
                ->where('user_id', '=', $user)
                ->select('url_hash')
                ->get();

            $output = $hashurl[0]-> url_hash;
            
            
            //return $output;

            //$a = DB::table('prospects')
            //->where('id', '=', $uesr)
            //->select('code_zone')
            //->get();
            
            //$outa = $a[0] -> code_zone;
            
            return $output;
            
            
        }else{
        return 'false call!!';
        };
    }
    
    public function reportauth()
    {
        if (isset($_POST['fingerprint']))
        {   
            $packdata = $_POST['fingerprint'];
            $hashinfo = $packdata[1];
            $fprint = $packdata[0];

            $find = DB::table('shorturls')
                ->where('url_hash','=',$hashinfo)
                ->first();

            if(strlen($find->uuid) != 0){
                if($find->uuid == $fprint){
                    return [1,$find->company_id,$find->user_id];
                };
            }
            else{

                DB::table('shorturls')
                    ->where('url_hash','=',$hashinfo)
                    ->update(['uuid' => $fprint]);
                return [1,$find->company_id,$find->user_id];
            };    
            
            
            
            
        }
        else{
            return 'false call!!';
        };
    }
// public function reportAuthEmail()

    public function reportauthemail()
    {
        if (isset($_POST['email']))
        {   
            $uesremail = $_POST['email'];
            $find = DB::table('prospectusers')->where('email','=',$uesremail)->first();
            if($find != null){
                if($find->active == 1)
                {
                    return [1,$find->company_id,$find->id];
                }
                else{
                    return 0;
                }
            }
            else{
                return 0;
            };
            
            
        }
        else{
            return 'false call!!';
        };
    }

    public function sendmail()
    {
        if (isset($_POST['indata']))
        {   
            // watch spelling on var names

            $pdata = json_decode($_POST['indata'],true);
            $id = $pdata['id'];
            $sender = DB::table('adminusers')
                ->where('email','=',$pdata['account'])->first();
            
            $user = DB::table('prospectusers')->where('id','=',$id)->first();
            $url = DB::table('shorturls')->where('user_id','=',$id)->first();
            
            $url = $url -> url_hash;
            $user -> url = $url;
            $user -> sender = $pdata['account'];
            $user -> sender_name = $sender -> username;
            $user -> ucompany = str_replace(' ', '_', $user->company);

            Mail::send('emails.newuser', ['user' => $user], function ($m) use ($user) {
                $m->from($user->sender, 'Vector Report');
                $m->to($user->email, $user->full_name)->subject($user->full_name . ', your site’s SEO report card is ready for you!');
            });


            return json_encode($user->url);
            
        }
        else{
            return 'false call!!';
        };
    }

    public function sendmail2()
    {
        if (isset($_POST['getdata']))
        {   
            $data = json_decode($_POST['getdata'],true);
           
            $user = DB::table('prospectusers')->where('id','=',$data['id'])->first();
            $url = DB::table('shorturls')->where('user_id','=',$data['id'])->first();
           
            $unhash = base64_decode($data['hash']);
            $position = strpos($unhash,"=");
            $invite = (int)substr($unhash, $position+1);
           
            $inviterinfo = DB::table('prospectusers')->where('id','=',$invite)->first();
            
            $sender = DB::table('adminusers')
                ->where('email','=',$data['sender'])->first();

            $url = $url -> url_hash;
            $user -> url = $url;
            $user -> msg = $data['msg'];
            $user -> inviter = $inviterinfo ->full_name;
            $user -> invitermail = $inviterinfo ->email;
            $user -> sub = $user -> inviter . " sent you top-secret information about your company website!
";
            $user -> sender = $data['sender'];
            $user -> sender_name = $sender -> username;
            $user -> ucompany = str_replace(' ', '_', $user->company);

            Mail::send('emails.inviteuser', ['user' => $user], function ($m) use ($user) {
                $m->from($user->sender, 'Vector Report');
                $m->to($user->email, $user->full_name)->subject('SEO help is on the way from ' . $user -> inviter);
            });


            return json_encode($user);
            
        }
        else{
            return 'false call!!';
        };
    }

    public function sendmail3(Request $request)
    {

        $data = $request->all();
        if (isset($data['indata']))
        {   
            $pdata = json_decode($data['indata'],true);

            $sender = DB::table('adminusers')
                ->where('email','=',$pdata['accountowner'])->first();
            $senderemail = $sender -> email;
            $sendername = $sender -> username;

            $user = DB::table('prospectusers')
                ->where('id','=',$pdata['contactid'])->first();
            $url = DB::table('shorturls')->where('id','=',$pdata['contactid'])->first();


            $user -> url = $url -> url_hash;
            $user -> sendername = $sendername;
            $user -> senderemail = $senderemail;
            $user -> emailcontent = $pdata['maincontent'];
            $user -> subjectline = $pdata['subject'];
            $user -> ucompany = str_replace(' ', '_', $user->company);
                
            Mail::send('emails.initial', ['user' => $user], function ($m) use ($user) {
                $m->from($user->senderemail, 'Vector Report');
                $m->to($user->email, $user->full_name)->subject($user->subjectline);
            });


            return $user->id;



            
        }
        else{
            return 'false call!!';
        };
    }

    public function deletecompany()
    {
        if (isset($_POST['deletecompany']))
        {
            $delete = $_POST['deletecompany'];

            $find = DB::table('prospects')->where('fc_company_name','=',$delete)->first();

            $id = $find -> id;
            
            DB::table('prospects')->where('id', '=', $id)->delete();

            DB::table('prospectusers')->where('company_id', '=', $id)->delete();

            DB::table('shorturls')->where('company_id', '=', $id)->delete();

            DB::table('rank')->where('id', '=', $id)->delete();

            
            //DB::insert('insert into prospectusers (email, full_name, title, company_id, fc_gravatar, company) values (?, ?, ?, ?, ?, ?)', [$newuserdata[3], $newuserdata[1], $newuserdata[2], $newuserdata[4], $newuserdata[0], $newuserdata[5]]);
            
        return "success!";
        
        }else{
            return 'false call';
        };
    }

    public function editor()
    {
        if (isset($_POST['companyid']))
        {
            $id = $_POST['companyid'];

            $info = DB::table('prospects')->where('id','=',$id)->first();

            $grade = DB::table('prospectscores')->where('company_id','=',$id)->first();

            //DB::insert('insert into prospectusers (email, full_name, title, company_id, fc_gravatar, company) values (?, ?, ?, ?, ?, ?)', [$newuserdata[3], $newuserdata[1], $newuserdata[2], $newuserdata[4], $newuserdata[0], $newuserdata[5]]);
            $passdata = [$info,$grade];
        return $passdata;
        
        }
        else{
            return 'false call';
        };
    }

        public function activeuser()
    {
        if (isset($_POST['uid']))
        {
            $user_id= $_POST['uid'];
            
            $updateitem = ['active' => 1,'updated_at' => date("Y-m-d H:i:s")];

            DB::table('prospectusers')
                ->where('id', $user_id)
                ->update($updateitem);

            return 'activeted!';
        
        }else{
        return 'false call';
        };
    }

    public function updatemanager()
    {
        if (isset($_POST['indata']))
        {
            $pdata= $_POST['indata'];

            $companyid = $pdata[0];

            $manager_account = $pdata[1];
            
            $updateitem = ['account_with' => $manager_account,'updated_at' => date("Y-m-d H:i:s")];

            DB::table('prospects')
                ->where('id', $companyid)
                ->update($updateitem);

            return 'updated!';
        
        }else{
        return 'false call';
        };
    }

    public function activecompanyusers()
    {
        if (isset($_POST['indata']))
        {
            $pdata= $_POST['indata'];

            $companyid = $pdata;

            $updateitem = ['active' => 1,'updated_at' => date("Y-m-d H:i:s")];

            DB::table('prospectusers')
            ->where('company_id', $companyid)
            ->update($updateitem);

            return 'updated!';
        
        }else{
        return 'false call';
        };
    }

    public function webhook(){
    
            // remove this from within the function

            // how you'd use it if it was part of the object $variable = $this->stripslahses_deep($value);

            function stripslashes_deep($value) {
              $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);
              return $value;
            }     


            if (get_magic_quotes_gpc()) {
              $unescaped_post_data = stripslashes_deep($_POST);
            } else {
              $unescaped_post_data = $_POST;
            }
            $form_data = json_decode($unescaped_post_data['data_json']);

            // no need to set these as variables. Just use them from the $form_data var
            $email_address = $form_data->email[0];
            $company_name = $form_data->company_name[0];
            $contact_name = $form_data->name[0];
            $need_agency = $form_data->need_agency[0];

            $find = DB::table('prospects')->where('fc_company_name','like',$company_name)->first();
            if($find != null){
                // $indata['field'] = $email_address." is good";


                $indata = ['email' => $email_address, 'full_name' => $contact_name, 'title' => '', 'company_id' => $find -> id, 'fc_gravatar' => '', 'company' => $find -> fc_company_name,'created_at' => date("Y-m-d H:i:s")];
            
                $id = DB::table('prospectusers')->insertGetId($indata);
            
                $inurl = $find -> id . '_' . $email_address . '=' . $id;

                // spelling - "hashed"
                $hashed = base64_encode($inurl);

                $urldata = ['company_id' => $find -> id, 'user_id' => $id, 'url_hash' => $hashed];

                DB::table('shorturls') ->insert($urldata);


                            //add this contact to REPLY
                $reply = array();
                $reply['campaignId'] = 24295;
                $reply['email'] = $indata['email'];

                if (strpos($indata['full_name'], ' ') !== false) {
                    $name_array = explode(' ', $indata['full_name'],2);
                    $reply['lastName'] = $name_array[1];
                    $reply['firstName'] = $name_array[0];
                }else{
                    $reply['firstName'] = $indata['full_name'];
                }


                // $reply['firstName'] = $indata['full_name'];
                $reply['company'] = $indata['company'];
                $reply['customFields'] = array(array(
                    "key" => "PAURL",
                    "value" => "http://vector.ipullrank.com/display-report?report=".$hashed."&company=".str_replace(' ', '_', $indata['company'])
                    ));

                $api_data = json_encode($reply);

                $service_url = 'https://run.replyapp.io/api/v1/actions/addandpushtocampaign';

                // make curl command its own function just in case you need to do something like this again in another place
                $ch = curl_init($service_url); 
                curl_setopt($ch, 
                            CURLOPT_HTTPHEADER, 
                            array('Content-Type: application/json', 
                            'X-Api-Key:'));
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $api_data);
                curl_setopt($ch, CURLOPT_POST, 1);
                $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $server_output = curl_exec ($ch);
                curl_close ($ch);


            }
            else
            {
                $indata['field'] = $email_address;
                DB::table('webhook')->insert($indata);
                
                $lead = array();
                //call to insightly api

                if(strpos($contact_name,' ') === false){
                        $form_lastname = $contact_name;
                        $form_firstname = '';
                }

                // this won't work on a long name like Laura Almeida de Viera
                // instead look for the first space and split what's before and what's after
                // strpos on the space
                // and the substr before and after the space
                else{
                    $name_array = explode(' ', $contact_name,2);
                    $form_lastname = $name_array[1];
                    $form_firstname = $name_array[0];
                }

                $arr = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => $email_address
                ),
                array(
                    'property' => 'firstname',
                    'value' => $form_firstname
                ),
                array(
                    'property' => 'lastname',
                    'value' => $form_lastname
                ),
                array(
                    'property' => 'company',
                    'value' => $company_name
                ),
                array(
                    'property' => 'form_source',
                    'value' => 'vector landingpage form'
                ),
                array(
                        'property' => 'hs_lead_status',
                        'value' => 'NEW'
                    )
            )
        );

        $post_json = json_encode($arr);


        $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=';

                $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL, $endpoint);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);


                $server_output = curl_exec ($ch);
                $reponse_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
                $curl_errors = curl_error($ch);
                curl_close ($ch);

            }


    }

    public function ranklist(){

        $api = $_SERVER['HTTP_API_KEY'];

        if ($api == ''){
            $ranklist = DB::select('select * from rank');
            return json_encode($ranklist);
        }else{
            return 'wrong api key';
        }


    }

    public function contactForm(Request $request){
        $data = $request->all();

        $arr = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => $data['email']
                ),
                array(
                    'property' => 'firstname',
                    'value' => $data['FirstName']
                ),
                array(
                    'property' => 'lastname',
                    'value' => $data['LastName']
                ),
                array(
                    'property' => 'company',
                    'value' => $data['OrganizationName']
                ),
                array(
                    'property' => 'phone',
                    'value' => $data['phone']
                ),
                array(
                    'property' => 'form_source',
                    'value' => 'vector contact form'
                ),
                array(
                    'property' => 'hs_lead_status',
                    'value' => 'NEW'
                )

            )
        );

        $post_json = json_encode($arr);


        $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=';

                $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL, $endpoint);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);


                $server_output = curl_exec ($ch);
                $reponse_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
                $curl_errors = curl_error($ch);
                curl_close ($ch);


                echo json_encode($reponse_code);

    }
   

}
