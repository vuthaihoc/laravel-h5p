<?php

namespace Chali5124\LaravelH5p\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostH5pContent extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'title' => 'required|min:1',
//            'content' => 'required|min:1',
            'user_id' => 'required|exists:boards,id',
            'uploaded' => '',
            'library_id' => 'required|exists:h5p_libraries,id',
            'parameters' => 'required',
            'filtered' => '',
            'slug' => '',
            'embed_type' => '',
            'disable' => '',
            'content_type' => '',
            'author' => '',
            'license' => '',
            'keywords' => '',
            'description' => ''
        ];
    }

}
