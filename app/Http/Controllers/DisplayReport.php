<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DisplayReport extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

	// show the report
    public function index(Request $request)
    {
		//return "Yo does this work?";

		// need all pageviews over time
		//$analyticsData = LaravelAnalytics::getVisitorsAndPageViews(7);

		// need conversions over time
		// $conversions = LaravelAnalytics::performQuery($startDate, $endDate, $metrics, $others = array());

		//$data = array('analytics' => $analyticsData,'conversions' => $conversions);
		//return view("report",$data);

        $id = $request -> query('report');
        $realid = 0;
        if(base64_encode(base64_decode($id, true)) === $id){
            $unhash = base64_decode($id);
            $position = strpos($unhash,"_");
            $realid = substr($unhash, 0, $position);
            //$position = strpos($unhash,"=");
            //$userid = substr($unhash, $position+1, strlen($unhash)-$position-1);
            $reportdata = DB::select('select * from prospectscores where company_id='.$realid);
            $companyinfo = DB::select('select * from prospects where id='.$realid);
            $copy = DB::select('select * from copytext');
            foreach ($copy as $value) {
                $currentquad = $value -> quad;
                $finder = $reportdata[0]->$currentquad;
                $finder = strtolower($finder);
                $copydata[$currentquad] = $value -> $finder;
            }
            return view("report",["data"=>$reportdata,"companyinfo"=>$companyinfo,"hash"=>$id,"copycontent" => $copydata]);
        }else{
            return view("404");
        }
    }
	
    

	// Show the page with the modal for the unidentified user
    public function unidentified()
    {
        //
		return view("report");
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
