<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectscoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospectscores', function (Blueprint $table) {
        		
        	$table->engine = 'InnoDB';
			
			// Primary Auto Incrementing Index
			$table->increments('id');
			
			// Foreign Key for associating with prospects table; unique index constraint
			$table->integer('company_id')->unique();
			
			// report metrics
			$table->integer('unnatural_links'); 
			$table->integer('spam_score');
			$table->integer('trust_metrics');
			$table->integer('link_popularity');
			
			// final score formatted as 000.00 to 100.00
			$table->String('final_score');
			
			// soft delete flag, automagically managed
			$table->softDeletes();
			
			// auto updated created_at and modified_at columns for EloquentORM, automagically managed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('prospectscores');
    }
}