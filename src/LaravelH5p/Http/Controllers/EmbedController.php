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
use Chali5124\LaravelH5p\Services\H5PLaravelAdmin;

class EmbedController extends Controller {

    public function __invoke(Request $request) {

        // Allow other sites to embed
        header_remove('X-Frame-Options');

        // Find content
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id !== NULL) {
            $h5p = App::make('LaravelH5p');
            $content = $h5p->get_content($id);
            if (!is_string($content)) {
                // Everyone is allowed to embed, set through settings
                $embed_allowed = (config('laravel-h5p.h5p_embed', TRUE) && !($content['disable'] & H5PCore::DISABLE_EMBED));
                /**
                 * Allows other plugins to change the access permission for the
                 * embedded iframe's content.
                 *
                 * @since 1.5.3
                 *
                 * @param bool $access
                 * @param int $content_id
                 * @return bool New access permission
                 */
                $embed_allowed = apply_filters('h5p_embed_access', $embed_allowed, $id);
                if (!$embed_allowed) {
                    // Check to see if embed URL always should be available
                    $embed_allowed = (defined('H5P_EMBED_URL_ALWAYS_AVAILABLE') && H5P_EMBED_URL_ALWAYS_AVAILABLE);
                }
                if ($embed_allowed) {
                    $lang = $h5p->get_language();
                    $cache_buster = '?ver=' . H5P_Plugin::VERSION;
                    // Get core settings
                    $integration = $h5p->get_core_settings();
                    // TODO: The non-content specific settings could be apart of a combined h5p-core.js file.
                    // Get core scripts
                    $scripts = array();
                    foreach (H5PCore::$scripts as $script) {
                        $scripts[] = plugins_url('h5p/h5p-php-library/' . $script) . $cache_buster;
                    }
                    // Get core styles
                    $styles = array();
                    foreach (H5PCore::$styles as $style) {
                        $styles[] = plugins_url('h5p/h5p-php-library/' . $style) . $cache_buster;
                    }
                    // Get content settings
                    $integration['contents']['cid-' . $content['id']] = $h5p->get_content_settings($content);
                    $core = $h5p->get_h5p_instance('core');
                    // Get content assets
                    $preloaded_dependencies = $core->loadContentDependencies($content['id'], 'preloaded');
                    $files = $core->getDependenciesFiles($preloaded_dependencies);
                    $h5p->alter_assets($files, $preloaded_dependencies, 'external');
                    $scripts = array_merge($scripts, $core->getAssetsUrls($files['scripts']));
                    $styles = array_merge($styles, $core->getAssetsUrls($files['styles']));
                    include_once(plugin_dir_path(__FILE__) . '../h5p-php-library/embed.php');
                    // Log embed view
                    new H5P_Event('content', 'embed', $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'] . '.' . $content['library']['minorVersion']);
                    exit;
                }
            }
        }
        // Simple unavailble page
        print '<body style="margin:0"><div style="background: #fafafa url(' . plugins_url('h5p/h5p-php-library/images/h5p.svg') . ') no-repeat center;background-size: 50% 50%;width: 100%;height: 100%;"></div><div style="width:100%;position:absolute;top:75%;text-align:center;color:#434343;font-family: Consolas,monaco,monospace">' . __('Content unavailable.', $this->plugin_slug) . '</div></body>';
        exit;
    }

}
