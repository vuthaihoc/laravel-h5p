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
use Chali5124\LaravelH5p\Events\H5pEvent;
use Chali5124\LaravelH5p\Eloquents\H5pContent;
use Chali5124\LaravelH5p\Http\Requests\PostH5pContent;
use Chali5124\LaravelH5p\Eloquents\H5pLibrary;

class H5pController extends Controller {

    public function index(Request $request) {

        $where = H5pContent::orderBy('id', 'desc');
        $entrys = $where->paginate(10);
        return view('laravel-h5p::h5p.index', compact("entrys"));
    }

    public function create(Request $request) {

        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;

        // Prepare form
        $library = 0;
        $parameters = '{}';

        $display_options = $core->getDisplayOptionsForEdit('');
        
        // view 에서 출력할 파일과 세팅을 가져온다
        $settings = $h5p::get_editor();

        // create event dispatch
        event(new H5pEvent('content', 'new'));

        $user = Auth::user();
        return view('laravel-h5p::h5p.create', compact("settings", 'user', 'library', 'parameters', 'display_options'));
    }

//    public function store(PostH5pContent $request) {
    public function store(Request $request) {
        
//        $this->validate($request, [
//            'title' => 'required|max:250',
//            'library' => 'required',
//            'parameters' => 'required'
//                ], [
//            'title' => trans('laravel-h5p::laravel-h5p.content.title'),
//            'library' => trans('laravel-h5p::laravel-h5p.content.library'),
//            'parameters' => trans('laravel-h5p::laravel-h5p.content.parameters')
//        ]);
        
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;

        $oldLibrary = NULL;
        $oldParams = NULL;
        $content = array(
            'disable' => H5PCore::DISABLE_NONE
        );

        $content['library'] = $core->libraryFromString($request->get('library'));
        $content['library_id'] = $core->h5pF->getLibraryId($content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']);
        $content['embed_type'] = 'div';
        $content['user_id'] = Auth::id();
        $content['created_at'] = \Carbon\Carbon::now();
        $content['filtered'] = '';
        $content['disable'] = $request->get('disable') ? $request->get('disable') : false;
        $content['slug'] = config('laravel-h5p.slug');
        $content['title'] = $request->get('title');
        $content['parameters'] = $request->get('parameters');
        $content['params'] = $request->get('parameters');
        $params = json_decode($content['params']);
        $this->get_disabled_content_features($core, $content);

        // Save new content
        $content['id'] = $core->saveContent($content);
        // Move images and find all content dependencies
        $editor = $h5p::$h5peditor;
        $editor->processParameters($content['id'], $content['library'], $params, $oldLibrary, $oldParams);
        //$content['params'] = json_encode($params);

        $event_type = 'create';
        if ($request->hasFile('h5p_file')) {
            $event_type .= ' upload';
        }

        event(new H5pEvent('content', $event_type, $content['id'], $content['title'], $content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        return redirect()
                        ->route('h5p.edit', $content['id'])
                        ->with('success', trans('laravel-h5p::laravel-h5p.content.created'));
    }

    public function edit(Request $request, $id) {

//        $this->validate($request, [
//            'title' => 'required|max:250',
//            'library' => 'required',
//            'parameters' => 'required'
//                ], [
//            'title' => trans('laravel-h5p::laravel-h5p.content.title'),
//            'library' => trans('laravel-h5p::laravel-h5p.content.library'),
//            'parameters' => trans('laravel-h5p::laravel-h5p.content.parameters')
//        ]);

        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;
        $content = $h5p::get_content($id);

        // Prepare form
        $library = $content['library'] ? H5PCore::libraryToString($content['library']) : 0;
        $parameters = $content['params'] ? $content['params'] : '{}';
        $display_options = $core->getDisplayOptionsForEdit($content['disable']);

        // view 에서 출력할 파일과 세팅을 가져온다
        $settings = $h5p::get_editor($content);

        // create event dispatch
        event(new H5pEvent('content', 'edit', $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'] . '.' . $content['library']['minorVersion']));

        $user = Auth::user();
        return view('laravel-h5p::h5p.edit', compact("settings", 'user', 'id', 'content', 'library', 'parameters', 'display_options'));
    }

    public function update(Request $request, $id) {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;

        $content = $h5p::get_content($id);
        $oldLibrary = $content['library'];
        $oldParams = json_decode($content['params']);

        $content['library'] = $core->libraryFromString($request->get('library'));
        $content['library_id'] = $core->h5pF->getLibraryId($content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']);
        $content['embed_type'] = 'div';
        $content['user_id'] = Auth::id();
        $content['created_at'] = \Carbon\Carbon::now();
        $content['filtered'] = '';
        $content['disable'] = $request->get('disable') ? $request->get('disable') : false;
        $content['slug'] = config('laravel-h5p.slug');
        $content['title'] = $request->get('title');
        $content['parameters'] = $request->get('parameters');
        $content['params'] = $request->get('parameters');
        $content['params'] = $request->get('id');
        $params = json_decode($content['params']);
        $this->get_disabled_content_features($core, $content);

        // Save new content
        $core->saveContent($content);
        // Move images and find all content dependencies
        $editor = $h5p::$h5peditor;
        $editor->processParameters($content['id'], $content['library'], $params, $oldLibrary, $oldParams);


        $event_type = 'update';
        if ($request->hasFile('h5p_file')) {
            $event_type .= ' upload';
        }

        event(new H5pEvent('content', $event_type, $content['id'], $content['title'], $content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        return redirect()
                        ->route('h5p.edit', $content['id'])
                        ->with('success', trans('laravel-h5p::laravel-h5p.content.updated'));
    }

    public function show(Request $request, $id) {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;

        $content = $h5p->get_content($id);

//        if (!is_string($content)) {
//            $tags = $wpdb->get_results($wpdb->prepare(
//                            "SELECT t.name
//             FROM {$wpdb->prefix}h5p_contents_tags ct
//             JOIN {$wpdb->prefix}h5p_tags t ON ct.tag_id = t.id
//            WHERE ct.content_id = %d", $id
//            ));
//            $this->content['tags'] = '';
//            foreach ($tags as $tag) {
//                $content['tags'] .= ($this->content['tags'] !== '' ? ', ' : '') . $tag->name;
//            }
//        }

        $settings = $h5p::get_core();
        $embed_code = $h5p->get_embed($content, $settings);

        event(new H5pEvent('content', NULL, $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        return view('laravel-h5p::h5p.show', compact("settings", 'user', 'embed_code'));
    }

    public function destroy(Request $request, $id) {
        $content = H5pContent::findOrFail($id);
        $content->destory();
    }

    private function get_disabled_content_features($core, &$content) {
        $set = array(
            H5PCore::DISPLAY_OPTION_FRAME => filter_input(INPUT_POST, 'frame', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_DOWNLOAD => filter_input(INPUT_POST, 'download', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_EMBED => filter_input(INPUT_POST, 'embed', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_COPYRIGHT => filter_input(INPUT_POST, 'copyright', FILTER_VALIDATE_BOOLEAN),
        );
        $content['disable'] = $core->getStorableDisplayOptions($set, $content['disable']);
    }

}
