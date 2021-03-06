<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class dashboard extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
        public function __construct()
	{
    $this->middleware('auth');
	} 
     
     
    public function index()
    {
        //
		//$companyStatus = \Astronaut\ShortUrls::find(1);
		$companyStatus = DB::select('select * from shorturls');
        $newProspects = DB::select('select * from prospectusers order by id desc limit 14');
        $prospects = DB::select('select * from prospectusers');
		// $analytcs 

        $kickoffdate = new \Datetime(date("Y-m-d"));
        $dt = new \Datetime(date("Y-m-d",strtotime('-30 days')));
        $others = array("dimensions" => "ga:date");
        
        // lazy inefficient way to get this in the format that I want
        $gaResponse = json_decode(json_encode(\Spatie\LaravelAnalytics\LaravelAnalyticsFacade::setSiteId('ga:')->performQuery($dt,$kickoffdate,"ga:goal1Completions,ga:sessions",$others)),true);
        //print_r($gaResponse);



        $others2 = array("dimensions" => "ga:date","filters" => "ga:eventCategory==inviteSent");
        $gaResponse2 = json_decode(json_encode(\Spatie\LaravelAnalytics\LaravelAnalyticsFacade::setSiteId('ga:')->performQuery($dt,$kickoffdate,"ga:goal1Completions",$others2)),true);

        $others3 = array("dimensions" => "ga:date","filters" => "ga:eventCategory==contactRequestB");
        $gaResponse3 = json_decode(json_encode(\Spatie\LaravelAnalytics\LaravelAnalyticsFacade::setSiteId('ga:')->performQuery($dt,$kickoffdate,"ga:goal3Completions",$others3)),true);

        $finalGA['schema'] = array('date','conversion','sessions');
        $finalGA['rows'] = $gaResponse['rows'];
        $finalGA['totals'] = $gaResponse['totalsForAllResults'];
        $finalGA['conversionRows'] = $gaResponse2['rows'];
        $finalGA['conversionRows2'] = $gaResponse3['rows'];
                
		return view("dash",["data" => $companyStatus,"newusers" =>$newProspects,"allcontacts"=>$prospects,"analytics"=>$finalGA]);
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
}
