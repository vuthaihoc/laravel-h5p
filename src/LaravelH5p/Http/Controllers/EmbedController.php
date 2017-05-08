<?php

namespace Chali5124\LaravelH5p\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use H5pCore;
use H5peditor;
use Chali5124\LaravelH5p\LaravelH5p;
use Chali5124\LaravelH5p\Events\H5PEvent;
use Chali5124\LaravelH5p\Eloquents\H5pContent;
use Chali5124\LaravelH5p\Http\Requests\PostH5pContent;
use Chali5124\LaravelH5p\Eloquents\H5pLibrary;

class EmbedController extends Controller {

    public function __invoke(Request $request, $id) {
//        return view('laravel-h5p::h5p.embed', compact("entry"));
    }

}
