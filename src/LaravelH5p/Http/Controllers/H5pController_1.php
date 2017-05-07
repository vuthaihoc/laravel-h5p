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
        $entrys = $where->paginate(25);

        return view('laravel-h5p::h5p.index', compact("entrys"));
    }

    public function create(Request $request) {
        $contentExists = FALSE;
        $hubIsEnabled = config('laravel-h5p.h5p_hub_is_enabled');
//        $hubIsEnabled = config('laravel-h5p.h5p_hub_is_enabled', TRUE);
        $h5p = App::make('LaravelH5p');
        $core = $h5p::$core;

        // Prepare form
        $title = '';
        $library = 0;
        $parameters = '{}';

        // 실행이 가능한가 아니면 업로드가 필요할때
        if (!$hubIsEnabled && !$contentExists && !$this->has_libraries()) {
            $upload = TRUE;
            $examplesHint = TRUE;
        } else {
//            $upload = (filter_input(INPUT_POST, 'action') === 'upload');
            $upload = TRUE;
            $examplesHint = FALSE;
        }

        $display_options = $core->getDisplayOptionsForEdit('');
        // view 에서 출력할 파일과 세팅을 가져온다
        $settings = $h5p::get_editor();

//        dd($settings);
//        $this->add_editor_assets($contentExists ? $this->content['id'] : NULL);
//        H5P_Plugin_Admin::add_script('jquery', 'h5p-php-library/js/jquery.js');
//        H5P_Plugin_Admin::add_script('disable', 'h5p-php-library/js/h5p-display-options.js');
//        H5P_Plugin_Admin::add_script('toggle', 'admin/scripts/h5p-toggle.js');
        // create event dispatch
        event(new H5PEvent('content', 'new'));

        $user = Auth::user();
        return view('laravel-h5p::h5p.create', compact("settings", 'user', 'title', 'library', 'parameters', 'examplesHint'));
    }

    /**
     * 현재 실행가능한 라이브러리가 있는가? 
     * @return boolean
     */
    private function has_libraries() {
        return H5pLibrary::where('runnable', 1)->first() !== NULL;
    }

    public function edit(Request $request) {

        return view('laravel-h5p::h5p.edit', compact("settings", 'user', 'title', 'library', 'parameters', 'examplesHint'));
    }

    public function store(PostH5pContent $request) {

        $data = $request->all();
        $data['embed_type'] = 'div';
        $data['user_id'] = Auth::id();
        $content = H5pContent::create($data);

        $event_type = 'create';
        if ($request->uploaded) {
            $event_type .= ' upload';
        }

        new H5P_Event('content', $event_type, $content->id, $content->title, $content->library->machineName, $content->library->majorVersion, $content->library->minorVersion);
        return redirect()
                        ->route('laravel-h5p.library.update', $content->id)
                        ->with('success', trans('laravel-h5p::laravel-h5p.h5p.created'));
    }

    public function update(PostH5pContent $request, $id) {
        $content = H5pContent::findOrFail($request->id);
        $content->update($request->all());

        $event_type = 'update';

        if ($request->uploaded) {
            $event_type .= ' upload';
        }

        new H5P_Event('content', $event_type, $content->id, $content->title, $content->library->machineName, $content->library->majorVersion, $content->library->minorVersion);
        return redirect()
                        ->route('laravel-h5p.library.update', $content->id)
                        ->with('success', trans('laravel-h5p::laravel-h5p.h5p.created'));
    }

    public function destroy($id) {
        $content = H5pContent::findOrFail($id);
        $content->destory();
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

    public function embed(Request $request, $id) {
        return view('laravel-h5p::h5p.embed', compact("entry"));
    }

}
