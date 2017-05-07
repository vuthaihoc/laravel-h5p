<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateH5pTmpfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('h5p_tmpfiles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('path');
			$table->integer('created_at')->unsigned()->index('created_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('h5p_tmpfiles');
	}

}
