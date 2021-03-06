<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospectusers', function (Blueprint $table) {
        		
        	$table->engine = 'InnoDB';
			
			// Primary Auto Incrementing Index
			$table->increments('id');
			
			// email address to send short link to
            $table->string('email')->unique();
            
            // single column for user's name
            $table->string('full_name');
			
			// job title
			$table->string('title')->nullable();
			
			// user entered company name; will be used for manual association with company in prospects table
			$table->string('company')->nullable();
			
			// foreign key to prospects table for displaying prospect users associated with a prospect company
			$table->integer('company_id')->index();
			
			// full contact API fields
			$table->string('fc_location_general')->nullable();
			$table->string('fc_gravatar')->nullable();
			
			// check if twitter is present under social, if so insert 1, else insert 0
			$table->boolean('fc_has_twitter')->nullable();
			
			// check if facebook is present under social, if so insert 1, else insert 0
			$table->boolean('fc_has_facebook')->nullable();
			
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
        Schema::drop('prospectusers');
    }
}