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
        $title = '';
        $library = 0;
        $parameters = '{}';
        $display_options = $core->getDisplayOptionsForEdit('');

        // view 에서 출력할 파일과 세팅을 가져온다
        $settings = $h5p::get_editor();

        // create event dispatch
        event(new H5PEvent('content', 'new'));

        $user = Auth::user();
        return view('laravel-h5p::h5p.create', compact("settings", 'user', 'library', 'parameters', 'display_options'));
    }

    private function get_disabled_content_features($core, &$content) {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;

        $set = array(
            H5PCore::DISPLAY_OPTION_FRAME => filter_input(INPUT_POST, 'frame', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_DOWNLOAD => filter_input(INPUT_POST, 'download', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_EMBED => filter_input(INPUT_POST, 'embed', FILTER_VALIDATE_BOOLEAN),
            H5PCore::DISPLAY_OPTION_COPYRIGHT => filter_input(INPUT_POST, 'copyright', FILTER_VALIDATE_BOOLEAN),
        );
        $content['disable'] = $core->getStorableDisplayOptions($set, $content['disable']);
    }

//    public function store(PostH5pContent $request) {
    public function store(Request $request) {
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;

//        $content = []; //$request->all();
//        // Keep track of the old library and params
//        if ($content) {
//            $oldLibrary = $content['library'];
//            $oldParams = json_decode($content['params']);
//        } else {
//            
//        }
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
//        $content[''] = $request->get('');
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

        event(new H5PEvent('content', $event_type, $content['id'], $content['title'], $content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        return redirect()
                        ->route('h5p.edit', $content['id'])
                        ->with('success', trans('laravel-h5p::laravel-h5p.content.created'));
    }

    public function edit(Request $request, $id) {
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
        event(new H5PEvent('content', 'edit', $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'] . '.' . $content['library']['minorVersion']));

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

        event(new H5PEvent('content', $event_type, $content['id'], $content['title'], $content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        return redirect()
                        ->route('h5p.edit', $content['id'])
                        ->with('success', trans('laravel-h5p::laravel-h5p.content.updated'));

//        $content = $request->all();
//        $content['updated_at'] = \Carbon\Carbon::now();
////        $content['created_at'] = \Carbon\Carbon::now();
//        $content['embed_type'] = 'div';
//        $content['embedType'] = $content['embed_type'];
//        $content['user_id'] = Auth::id();
//        $content['filtered'] = $request->get('filtered') ? $request->get('filtered') : '';
//        $content['disable'] = $request->get('disable') ? $request->get('disable') : false;
//        $content['slug'] = config('laravel-h5p.slug');
//        $content['library'] = $core->libraryFromString($request->get('library'));
//        $content['library_id'] = $core->h5pF->getLibraryId($content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']);
//        $content = $request->all();
////        $content['updated_at'] = \Carbon\Carbon::now();
//        $content['created_at'] = \Carbon\Carbon::now();
//        $content['embed_type'] = 'div';
//        $content['user_id'] = Auth::id();
//        $content['filtered'] = '';
//        $content['disable'] = $request->get('disable') ? $request->get('disable') : false;
//        $content['slug'] = config('laravel-h5p.slug');
//        $content['library'] = $core->libraryFromString($request->get('library'));
//        $content['library_id'] = $core->h5pF->getLibraryId($content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']);
//        $content['id'] = $id;
//
//        // Save new content
//        $core->saveContent($content);
//        $core->filterParameters($content);
//
//        $event_type = 'update';
//        if ($request->hasFile('h5p_file')) {
//            $event_type .= ' upload';
//        }
//
//        event(new H5PEvent('content', $event_type, $content['id'], $content['title'], $content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']));
//        return redirect()
//                        ->route('laravel-h5p.library.edit', $content->id)
//                        ->with('success', trans('laravel-h5p::laravel-h5p.h5p.updated'));
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
//        H5P_Plugin::get_instance()->add_settings();

        event(new H5PEvent('content', NULL, $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        return view('laravel-h5p::h5p.show', compact("settings", 'user', 'embed_code'));
    }

    public function destroy(Request $request, $id) {
        $content = H5pContent::findOrFail($id);
        $content->destory();
    }

    public function download(Request $request, $id) {
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

    public function embed(Request $request, $id) {
//        return view('laravel-h5p::h5p.embed', compact("entry"));
    }

    //---------------------------------------------------------------------------------------------

    /**
     * Add assets and JavaScript settings for the editor.
     *
     * @since 1.1.0
     * @param int $id optional content identifier
     */
//    public function add_editor_assets($id = NULL) {
//        $plugin = LaravelH5p::get_instance();
//        $plugin->add_core_assets();
//        // Make sure the h5p classes are loaded
//        $plugin->get_h5p_instance('core');
//        $this->get_h5peditor_instance();
//        // Add JavaScript settings
//        $settings = $plugin->get_settings();
//        $cache_buster = '?ver=' . LaravelH5p::VERSION;
//        // Use jQuery and styles from core.
//        $assets = array(
//            'css' => $settings['core']['styles'],
//            'js' => $settings['core']['scripts']
//        );
//        // Use relative URL to support both http and https.
//        $upload_dir = plugins_url('h5p/h5p-editor-php-library');
//        $url = '/' . preg_replace('/^[^:]+:\/\/[^\/]+\//', '', $upload_dir) . '/';
//        // Add editor styles
//        foreach (H5peditor::$styles as $style) {
//            $assets['css'][] = $url . $style . $cache_buster;
//        }
//        // Add editor JavaScript
//        foreach (H5peditor::$scripts as $script) {
//            // We do not want the creator of the iframe inside the iframe
//            if ($script !== 'scripts/h5peditor-editor.js') {
//                $assets['js'][] = $url . $script . $cache_buster;
//            }
//        }
//        // Add JavaScript with library framework integration (editor part)
//        LaravelH5p_Admin::add_script('editor-editor', 'h5p-editor-php-library/scripts/h5peditor-editor.js');
//        LaravelH5p_Admin::add_script('editor', 'admin/scripts/h5p-editor.js');
//        // Add translation
//        $language = $plugin->get_language();
//        $language_script = 'h5p-editor-php-library/language/' . $language . '.js';
//        if (!file_exists(plugin_dir_path(__FILE__) . '../' . $language_script)) {
//            $language_script = 'h5p-editor-php-library/language/en.js';
//        }
//        LaravelH5p_Admin::add_script('language', $language_script);
//        // Add JavaScript settings
//        $content_validator = $plugin->get_h5p_instance('contentvalidator');
//        $settings['editor'] = array(
//            'filesPath' => $plugin->get_h5p_url() . '/editor',
//            'fileIcon' => array(
//                'path' => plugins_url('h5p/h5p-editor-php-library/images/binary-file.png'),
//                'width' => 50,
//                'height' => 50,
//            ),
//            'ajaxPath' => admin_url('admin-ajax.php?token=' . wp_create_nonce('h5p_editor_ajax') . '&action=h5p_'),
//            'libraryUrl' => plugin_dir_url('h5p/h5p-editor-php-library/h5peditor.class.php'),
//            'copyrightSemantics' => $content_validator->getCopyrightSemantics(),
//            'assets' => $assets,
//            'deleteMessage' => __('Are you sure you wish to delete this content?', $this->plugin_slug),
//            'apiVersion' => H5PCore::$coreApi
//        );
//        if ($id !== NULL) {
//            $settings['editor']['nodeVersionId'] = $id;
//        }
//        
//        var_dump($settings);
////        $plugin->print_settings($settings);
//    }

    /**
     * Returns the instance of the h5p editor library.
     *
     * @since 1.1.0
     * @return \H5peditor
     */
//    private function get_h5peditor_instance() {
//        if (self::$h5peditor === null) {
//            $upload_dir = storage_path('h5p');
//            $plugin = LaravelH5p::get_instance();
//            self::$h5peditor = new H5peditor(
//                    $plugin->get_h5p_instance('core'), new LaravelH5pEditorStorage()
//            );
//        }
//        return self::$h5peditor;
//    }
}
