<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateH5pLibrariesCachedassetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('h5p_libraries_cachedassets', function(Blueprint $table)
		{
			$table->integer('library_id')->unsigned();
			$table->string('hash', 64);
			$table->primary(['library_id','hash'], 'fk_primary');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('h5p_libraries_cachedassets');
	}

}
