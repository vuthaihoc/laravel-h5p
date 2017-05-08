@extends( config('laravel-h5p.layout') )

@section( 'content' )
<div class="container-fluid">

    <div class="row">

        <div class="col-md-9">

            <div class="panel panel-primary">

                {!! Form::open(['route' => ['laravel-h5p.library.store'], 'id'=>'h5p-library-form', 'class'=>'form-horizontal', 'enctype'=>"multipart/form-data"]) !!}
                <div class="panel-body">

                    <div class="form-group {{ $errors->has('h5p_file') ? 'has-error' : '' }}" style="margin-bottom: 0px;">
                        <label for="inputTitle" class="control-label col-md-3">라이브러리 업로드</label>
                        <div class="col-md-9">
                            <input type="file" name="h5p_file" id="h5p-file" class="form-control">

                            <ul class="list-unstyled" style="margin:10px 0px 0px;">
                                <li>
                                    <label for="h5p-upgrade-only" class="">
                                        <input type="checkbox" name="h5p_upgrade_only" id="h5p-upgrade-only">

                                        등록된 라이브러리만 업데이트</label>
                                </li>
                                <li> <label for="h5p-disable-file-check" class="">
                                        <input type="checkbox" name="h5p_disable_file_check" id="h5p-disable-file-check">

                                        업로드파일 확장자 체크안함</label>
                                </li>
                            </ul>
                            @if ($errors->has('h5p_file'))
                            <span class="help-block">
                                {{ $errors->first('h5p_file') }}
                            </span>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="panel-footer">
                    <input type="submit" name="submit" value="업로드" class="btn btn-primary">
                </div>
                {!! Form::close() !!}

            </div>

        </div>
        <div class="col-md-3">
            <div class="panel panel-primary">

                {!! Form::open(['route' => ['laravel-h5p.library.clear'], 'id'=>'h5p-update-content-type-cache', 'class'=>'form-horizontal', 'enctype'=>"multipart/form-data"]) !!}


                <div class="panel-body">
 <!--<p>Making sure the content type cache is up to date will ensure that you can view, download and use the latest libraries. This is different from updating the libraries themselves.</p>-->

                    <h4>라이브러리 캐시삭제</h4>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row">Last update</th>
                                <td>{{ $last_update }}</td>
                            </tr>
                        </tbody>
                    </table>



                </div>

                <div class="panel-footer">
                    <input type="hidden" id="sync_hub" name="sync_hub" value="d3cf01408e"><input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=h5p_libraries">          
                    <input type="submit" name="updatecache" id="updatecache" class="btn btn-danger btn-large" value="삭제">
                </div>
                {!! Form::close() !!}

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-12">


            <p class="form-control-static">
                {{ trans('laravel-h5p::laravel-h5p.library.search-result', ['count' => count($entrys)]) }}
            </p>

            <table class="table text-middle text-center h5p-lists">
                <colgroup>
                    <col width="*">
                    <col width="10%">
                    <col width="10%">                    
                    <col width="10%">                    
                    <col width="10%">
                    <col width="10%">
                </colgroup>

                <thead>
                    <tr class="active">
                        <th class="text-left">라이브러리</th>
                        <th class="text-center">사용제한</th>
                        <th class="text-center">컨텐츠</th>
                        <th class="text-center">의존컨텐츠</th>
                        <th class="text-center">의존라이브러리</th>
                        <th class="text-center">처리</th>
                    </tr>
                </thead>

                <tbody>

                    @unless(count($entrys) >0)
                    <tr><td colspan="6" class="h5p-noresult">{{ trans('laravel-h5p::laravel-h5p.common.no-result') }}</td></tr>
                    @endunless

                    @foreach($entrys as $entry)
                    <tr>
                        <td class="text-left">
                            <p class="form-control-static">
                                <a href="{{ route('laravel-h5p.library.show', ['id'=>$entry->id]) }}">{{ $entry->title }} ({{ $entry->major_version.'.'.$entry->minor_version.'.'.$entry->patch_version }})</a>
                            </p>
                        </td>

                        <td class="text-center">
                            <label class="checkbox-inline">
                                <input type="checkbox" value="{{ $entry->restricted }}" 
                                       @if($entry->restricted == '1')
                                       checked=""
                                       @endif
                                       class="laravel-h5p-restricted" data-id="{{ $entry->id }}">
                            </label>
                        </td>

                        <td class="text-center">
                            <p class="form-control-static">{{ number_format($entry->numContent()) }}</p>
                        </td>
                        <td class="text-center">
                            <p class="form-control-static">{{ number_format($entry->getCountContentDependencies()) }}</p>
                        </td>
                        <td class="text-center">
                            <p class="form-control-static">{{ number_format($entry->getCountLibraryDependencies()) }}</p>
                        </td>

                        <td class="text-center">
                            <button class="btn btn-danger laravel-h5p-destory" data-id="{{ $entry->id }}">삭제</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

</div>

@endsection









@push( 'header-script' )
    {{--    core styles       --}}
    @foreach($required_files['styles'] as $style)
    {{ Html::style($style) }}
    @endforeach
@endpush

@push( 'footer-script' )
    <script type="text/javascript">
        H5PAdminIntegration = {!! json_encode($settings) !!};
    </script>

    {{--    core script       --}}
    @foreach($required_files['scripts'] as $script)
    {{ Html::script($script) }}
    @endforeach

    <script type="text/javascript">
        (function ($) {

            $(document).ready(function () {

                $(document).on("click", ".laravel-h5p-restricted", function (e) {

                    var $this = $(this);

                    $.ajax({
                        url: "{{ route('laravel-h5p.library.restrict') }}",
                        data: {id: $this.data('id'), selected: $this.is(':checked')},
                        success: function (response) {
                            alert('변경되었습니다');
                        }
                    });

                });

                $(document).on("click", ".laravel-h5p-destory", function (e) {

                    var $this = $(this);
                    if (confirm("해당 라이브러리를 삭제하시겠습니까?")) {
                        $.ajax({
                            url: "{{ route('laravel-h5p.library.destory') }}",
                            data: {id: $this.data('id')},
                            success: function (response) {
    //                        alert('삭제되었습니다');
                                if (response.msg) {
                                    alert(response.msg);
                                }
                            }
                        });

                    }

                });




            });

        })(H5P.jQuery);
    </script>
@endpush