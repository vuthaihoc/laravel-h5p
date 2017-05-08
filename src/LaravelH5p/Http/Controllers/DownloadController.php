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

class DownloadController extends Controller {

    public function __invoke(Request $request, $id) {
        $content = H5pContent::findOrFail($id);
        $content->update($request->all());

        $event_type = 'update';

        if ($request->uploaded) {
            $event_type .= ' upload';
        }

        event(new H5P_Event('content', $event_type, $content->id, $content->title, $content->library->machineName, $content->library->majorVersion, $content->library->minorVersion));
        return redirect()
                        ->route('laravel-h5p.library.update', $content->id)
                        ->with('success', trans('laravel-h5p::laravel-h5p.h5p.created'));
    }

}
