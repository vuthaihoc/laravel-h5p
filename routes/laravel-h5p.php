<?php

Route::group(['middleware' => ['web']], function () {


    if (config('laravel-h5p.use_router') == 'EDITOR' || config('laravel-h5p.use_router') == 'ALL') {
        // h5p and libararys
        Route::group(['middleware' => ['auth']], function () {
            Route::resource('h5p', "Chali5124\LaravelH5p\Http\Controllers\H5pController");
            Route::get('h5p/export', 'Chali5124\LaravelH5p\Http\Controllers\H5pController@export')->name("h5p.export");

            Route::get('h5p/library', "Chali5124\LaravelH5p\Http\Controllers\LibraryController@index")->name("h5p.library.index");
            Route::get('h5p/library/show/{id}', "Chali5124\LaravelH5p\Http\Controllers\LibraryController@show")->name("h5p.library.show");
            Route::post('h5p/library/store', "Chali5124\LaravelH5p\Http\Controllers\LibraryController@store")->name("h5p.library.store");
            Route::delete('h5p/library/destory', "Chali5124\LaravelH5p\Http\Controllers\LibraryController@destory")->name("h5p.library.destory");
            Route::get('h5p/library/restrict', "Chali5124\LaravelH5p\Http\Controllers\LibraryController@restrict")->name("h5p.library.restrict");
            Route::post('h5p/library/clear', "Chali5124\LaravelH5p\Http\Controllers\LibraryController@clear")->name("h5p.library.clear");
        });


        // ajax
        Route::match(['GET', 'POST'], 'ajax/libraries', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@libraries')->name("h5p.ajax.libraries");
        Route::get('ajax/libraries', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@libraries')->name("h5p.ajax.libraries");
        Route::get('ajax/single-libraries', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@singleLibrary')->name("h5p.ajax.single-libraries");
        Route::post('ajax/content-type-cache', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@contentTypeCache')->name("h5p.ajax.content-type-cache");
        Route::post('ajax/library-install', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@libraryInstall')->name("h5p.ajax.library-install");
        Route::post('ajax/library-upload', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@libraryUpload')->name("h5p.ajax.library-upload");
        Route::post('ajax/rebuild-cache', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@rebuildCache')->name("h5p.ajax.rebuild-cache");
        Route::post('ajax/files', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@files')->name("h5p.ajax.files");
        Route::get('ajax/finish', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@finish')->name("h5p.ajax.finish");
        Route::get('ajax/content-user-data', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController@contentUserData')->name("h5p.ajax.content-user-data");
        Route::get('ajax', 'Chali5124\LaravelH5p\Http\Controllers\AjaxController')->name("h5p.ajax");
    }

    // export
    if (config('laravel-h5p.use_router') == 'EXPORT' || config('laravel-h5p.use_router') == 'ALL') {
        Route::get('h5p/embed/{id}', 'Chali5124\LaravelH5p\Http\Controllers\EmbedController')->name("h5p.embed");
        Route::get('h5p/download/{id}', 'Chali5124\LaravelH5p\Http\Controllers\DownloadController')->name("h5p.donwload");
    }
});
