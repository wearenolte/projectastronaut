@extends('layouts.report-temp')

@section('pagetitle','{{Company Name}} Report')


@section('grade')

<?php 

if(isset($data)){
echo $data[0]->final_score;};

?>
@endsection

@section('company')

<div id="reportcompany">
    <div style="text-align:center;" class="ctitle">
    <?php
        echo "<img src='" . $companyinfo[0]->fc_logo_url . "' width='80'>";
    ?>
    <h2><?php echo $companyinfo[0]->fc_company_name ?></h2>
    </div>
    <div class="cdetail">
        <h3>Founded</h3>   
        <p> 
            <?php echo $companyinfo[0]->fc_founded ?>
        </p>
        <h3>Employees</h3> 
        <p>   
            <?php echo $companyinfo[0]->fc_approx_employees ?>
        </p>
        <h3>Overview</h3>  
        <p>  
            <?php echo $companyinfo[0]->fc_overview ?>
        </p>
    </div>
</div>


@endsection



@section('companym')

<div id="reportcompany">
    <div style="text-align:center;">
    <?php
        echo "<img src='" . $companyinfo[0]->fc_logo_url . "' width='80'>";
    ?>
    </div>
    <div class="cdetail">
        <p>
        <h3>Founded</h3>    
            <?php echo $companyinfo[0]->fc_founded ?>
        </p>
        <p>
        <h3>Employees</h3>    
            <?php echo $companyinfo[0]->fc_approx_employees ?>
        </p>
        <p>
        <h3>Overview</h3>    
            <?php echo $companyinfo[0]->fc_overview ?>
        </p>
    </div>
</div>

@endsection

@section('companymname')

    <?php echo $companyinfo[0]->fc_company_name ?>

@endsection


@section('pagejs')

@endsection
