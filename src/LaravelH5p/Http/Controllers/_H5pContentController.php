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

class H5pContentController extends Controller {

    public function index(Request $request) {
        $where = H5pContent::orderBy('id', 'desc');
        $entrys = $where->paginate(25);

        return view('laravel-h5p::h5p.index', compact("entrys"));
    }

    public function create(Request $request) {
        return view('laravel-h5p::h5p.create', compact(""));
    }

    public function store(Request $request) {

        $this->validate($request, [
            'title' => 'required|min:1',
//            'content' => 'required|min:1',
//            'user_id' => 'exists:users,id',
//            'is_shown' => [
//                'required',
//                Rule::in(Code::getCodeFieldArray('post_shown_role')->toArray()),
//            ],
                ], [], [
            'title' => trans('laravel-h5p::laravel-h5p.title'),
            'content' => trans('laravel-h5p::laravel-h5p.content'),
//            'user_id' => trans('laravel-h5p::h5p.user_id'),
//            'is_shown' => trans('laravel-h5p::h5p.is_shown'),
        ]);

        $input = $request->all();
        $input['user_id'] = Auth::id() ? Auth::id() : 1;
        $input['ip'] = $request->ip();
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();

        $content = H5pContent::create($input);

        return redirect()
                        ->route('h5p.edit', $content->id)
                        ->with('success', trans('aravel-h5p::laravel-h5p.created'));
    }

    public function edit(Request $request, $id) {
        return view('laravel-h5p::h5p.edit', compact("entry"));
    }

    public function update(Request $request, $id) {
        
    }

    public function destroy(Request $request, $id) {
        
    }

    public function show(Request $request, $id) {

        return view('laravel-h5p::h5p.show', compact("entry"));
    }


}
