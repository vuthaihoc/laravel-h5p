<?php

namespace Chali5124\LaravelH5p\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use H5PEditorEndpoints;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use H5pCore;
use H5peditor;
use Chali5124\LaravelH5p\LaravelH5p;
use Chali5124\LaravelH5p\Events\H5pEvent;
use Chali5124\LaravelH5p\Eloquents\H5pContent;
use Chali5124\LaravelH5p\Services\H5PLaravelAdmin;

class AjaxController extends Controller {

    public function libraries(Request $request) {
        $machineName = $request->get('machineName');
        $major_version = $request->get('majorVersion');
        $minor_version = $request->get('minorVersion');

        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;

        if ($machineName) {
            $editor->ajax->action(H5PEditorEndpoints::SINGLE_LIBRARY, $machineName, $major_version, $minor_version, $h5p->get_language(), '', $h5p->get_h5plibrary_url('', TRUE));
            // Log library load
            event(new H5pEvent('library', NULL, NULL, NULL, $machineName, $major_version . '.' . $minor_version));
        } else {
            // Otherwise retrieve all libraries
            $editor->ajax->action(H5PEditorEndpoints::LIBRARIES);
        }
    }

    public function singleLibrary(Request $request) {
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::SINGLE_LIBRARY, $request->get('token'));
    }

    public function contentTypeCache(Request $request) {
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::CONTENT_TYPE_CACHE, $request->get('token'));
    }

    public function libraryInstall(Request $request) {
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::LIBRARY_INSTALL, $request->get('token'), $request->get('machineName'));
    }

    public function libraryUpload(Request $request) {
        $filePath = $request->file('h5p')->tmp_name;
        $h5p = App::make('LaravelH5p');
        $editor = $h5p::$h5peditor;
        $editor->ajax->action(H5PEditorEndpoints::LIBRARY_UPLOAD, $request->get('token'), $filePath, $request->get('contentId'));
    }

    public function files(Request $request) {

        if ($request->file('h5p_file')->isValid()) {

            $path = storage_path('h5p/upload' . date('Y/m/d/H/i/'));

            $file = $request->file('h5p_file');
            $new_name = $file->getExtension() . "." . time() . "." . str_random();
            $file->move($path, $new_name);

            return url('/upload/' . $path . $new_name);
        }
        return;
    }

    public function __invoke(Request $request) {
        return response()->json($request->all());
    }

    public function finish(Request $request) {
        
    }

    public function contentUserData(Request $request) {
        dd($request->all());
    }

}
