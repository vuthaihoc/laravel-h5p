/* 
 *
 * @Project        
 * @Copyright      leechanrin
 * @Created        2017-04-05 오전 11:42:35 
 * @Filename       laravel-h5p-editor.js
 * @Description    
 *
 */
(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': window.parent.Laravel
        }
    });

})(H5P.jQuery);
