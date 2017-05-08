<?php

/*
 *
 * @Project        laravel-h5p
 * @Copyright      leechanrin
 * @Created        2017-03-20 오후 5:00:58 
 * @Filename       h5p.php
 * @Description    
 *
 */

return [

    'H5P_DEV' => FALSE,
    'language' => 'en',
    'domain' => 'http://localhost',
    'slug' => 'laravel-h5p',
    'H5P_DISABLE_AGGREGATION' => FALSE,
    'h5p_frame' => TRUE,
    'h5p_export' => TRUE,
    'h5p_embed' => TRUE,
    'h5p_copyright' => TRUE,
    'h5p_icon' => TRUE,
    'h5p_track_user' => TRUE,
    'h5p_ext_communication' => TRUE,
    'h5p_save_content_state' => FALSE,
    'h5p_save_content_frequency' => 30,
    'h5p_site_key' => [
        'h5p_h5p_site_uuid' => TRUE
    ],
    'h5p_content_type_cache_updated_at' => 0,
    'h5p_check_h5p_requirements' => FALSE,
    'h5p_hub_is_enabled' => FALSE,
    'h5p_version' => '1.8.2',
];
