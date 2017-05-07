@extends( 'laravel-h5p::layouts.base' )

@section( 'content' )
<div class="container">

    <div class="row">

        <div class="col-md-12">

            {!! Form::open(['route' => ['h5p.store'], 'class'=>'form-horizontal', 'enctype'=>"multipart/form-data"]) !!}
            <input type="hidden" name="library" id="laravel-h5p-library" value="{{ $library }}">
            <input type="hidden" name="parameters" id="laravel-h5p-parameters" value="{{ $parameters }}">

            <fieldset>

                <div class="form-item form-type-file form-item-files-h5p" style="display: none;">
                    <label for="edit-h5p">HTML 5 Package </label>

                    {{ Form::file('files[h5p]', [
                        'class' => 'form-file',
                        'id' => 'edit-h5p'
                    ]) }}

                    <div class="description">Select a .h5p file to upload and create interactive content from. You may start with the <a href="http://h5p.org/content-types-and-applications" target="_blank">example files</a> on H5P.org</div>
                </div>

                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="inputTitle" class="control-label col-md-3">{{ trans('laravel-h5p::laravel-h5p.content.title') }}</label>
                    <div class="col-md-9">

                        {{ Form::text('title', old('title'), [
                                'class' => 'form-control',
                                'placeholder' => trans('laravel-h5p::laravel-h5p.content.title'),
                                'id' => 'laravel-h5p-title',
                            ]) }}

                        @if ($errors->has('title'))
                        <span class="help-block">
                            {{ $errors->first('title') }}
                        </span>
                        @endif
                    </div>
                </div>

                <!--
                <div class="form-item form-type-file form-item-files-h5p" style="display: none;">
                <label for="edit-h5p">HTML 5 Package </label>
                <input type="file" id="edit-h5p" name="files[h5p]" size="60" class="form-file">
                <div class="description">Select a .h5p file to upload and create interactive content from. You may start with the <a href="http://h5p.org/content-types-and-applications" target="_blank">example files</a> on H5P.org</div>
                </div>
                -->

                <div class="form-group laravel-h5p-upload-container">
                    <label for="inputContentType" class="control-label col-md-3">{{ trans('laravel-h5p::laravel-h5p.content.upload') }}</label>
                    <div class="col-md-9">
                        <input type="file" name="h5p_file" id="h5p-file" class="laravel-h5p-upload"/>

                        <div class="h5p-disable-file-check">
                            <label><input type="checkbox" name="h5p_disable_file_check" id="h5p-disable-file-check"/> {{ trans('Disable file extension check') }}</label>
                            <div class="h5p-warning">{{ trans("Warning! This may have security implications as it allows for uploading php files. That in turn could make it possible for attackers to execute malicious code on your site. Please make sure you know exactly what you're uploading.") }}</div>
                        </div>

                        @if ($errors->has('content_type'))
                        <span class="help-block">
                            {{ $errors->first('content_type') }}
                        </span>
                        @endif
                    </div>
                </div>

                <div id="laravel-h5p-create" class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                    <label for="inputContent" class="control-label col-md-3">{{ trans('laravel-h5p::laravel-h5p.content.create') }}</label>
                    <div class="col-md-9">
                        <div>
                            <div id="laravel-h5p-editor">{{ trans('Waiting for javascript...') }}</div>
                        </div>

                        @if ($errors->has('content'))
                        <span class="help-block">
                            {{ $errors->first('content') }}
                        </span>
                        @endif
                    </div>
                </div>



                <div class="form-group {{ $errors->has('content_type') ? 'has-error' : '' }}">
                    <label for="inputContentType" class="control-label col-md-3">{{ trans('laravel-h5p::laravel-h5p.content.action') }}</label>
                    <div class="col-md-6">

                        <label class="radio-inline">
                            <input type="radio" name="action" value="upload" class="laravel-h5p-type" >업로드
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="action" value="create" class="laravel-h5p-type" checked="checked"/>생성
                        </label>


                        @if ($errors->has('content_type'))
                        <span class="help-block">
                            {{ $errors->first('content_type') }}
                        </span>
                        @endif
                    </div>
                </div>





                @if (isset($display_options['frame']))
                <div class="form-group h5p-sidebar">
                    <label class="control-label col-md-3">화면설정</label>
                    <div class="col-md-9">
                        <ul class="list-unstyled">

                            <li>
                                <label>
                                    {{ Form::checkbox('frame', true, $display_options[H5PCore::DISPLAY_OPTION_FRAME], [
                                    'class' => 'h5p-visibility-toggler',
                                    'data-h5p-visibility-subject-selector' => ".h5p-action-bar-buttons-settings",
                                    'id' => 'laravel-h5p-title',
                                    'value' => old('title')
                                ]) }}
                                    {{ trans("laravel-h5p::laravel-h5p.content.display_toolbar") }}
                                </label>
                            </li>


                            @if (isset($display_options[H5PCore::DISPLAY_OPTION_DOWNLOAD]) || isset($display_options[H5PCore::DISPLAY_OPTION_EMBED]) || isset($display_options[H5PCore::DISPLAY_OPTION_COPYRIGHT])) 


                            @if(isset($display_options[H5PCore::DISPLAY_OPTION_DOWNLOAD]))
                            <li>
                                <label>
                                    <input name="download" type="checkbox" value="true"
                                           @if($display_options[H5PCore::DISPLAY_OPTION_DOWNLOAD]) 
                                           checked="checked"
                                           @endif
                                           />
                                           {{ trans("laravel-h5p::laravel-h5p.content.display_download_button") }}
                                </label>
                            </li>
                            @endif

                            @if (isset($display_options[H5PCore::DISPLAY_OPTION_EMBED]))
                            <li>
                                <label>
                                    <input name="embed" type="checkbox" value="true"
                                           @if ($display_options[H5PCore::DISPLAY_OPTION_EMBED]) 
                                           checked="checked"
                                           @endif
                                           />
                                           {{ trans("laravel-h5p::laravel-h5p.content.display_embed_button") }}
                                </label>
                            </li>
                            @endif

                            @if  (isset($display_options[H5PCore::DISPLAY_OPTION_COPYRIGHT]))
                            <li>
                                <label>
                                    <input name="copyright" type="checkbox" value="true"
                                           @if ($display_options[H5PCore::DISPLAY_OPTION_COPYRIGHT]) 
                                           checked="checked"
                                           @endif
                                           />
                                           {{ trans("laravel-h5p::laravel-h5p.content.display_copyright_button") }}
                                </label>
                            </li>
                            @endif

                            @endif

                        </ul>
                    </div>

                </div>
                @endif

            </fieldset>


            <div class="form-group">
                <div class="col-md-9 col-md-offset-3">
                    <a href="{{ route('h5p.index') }}" class="btn btn-default"><i class="fa fa-reply"></i> {{ trans('laravel-h5p::laravel-h5p.content.cancel') }}</a>

                    {{ Form::submit(trans('laravel-h5p::laravel-h5p.content.create'), [
                "class"=>"btn btn-primary",
                "data-loading-text" => trans('laravel-h5p::laravel-h5p.content.loading')
                        ]) }}

                </div>

            </div>

            {!! Form::close() !!}

        </div>

    </div>

</div>

@endsection

@section( 'header-script' )

{{--    core styles       --}}
@foreach($settings['core']['styles'] as $style)
{{ Html::style($style) }}
@endforeach

{{--    editor styles     --}}
@foreach($settings['editor']['assets']['css'] as $style)
{{ Html::style($style) }}
@endforeach

{{--    core script       --}}
@foreach($settings['core']['scripts'] as $script)
{{ Html::script($script) }}
@endforeach

{{ Html::script('vendor/h5p/h5p-editor/scripts/h5peditor-editor.js') }}

{{--    editor script       --}}
@foreach($settings['editor']['assets']['js'] as $script)
{{ Html::script($script) }}
@endforeach
@endsection


@section( 'footer-script' )
<script type="text/javascript">
    H5PIntegration = {!! json_encode($settings) !!};
</script>
<script type="text/javascript" src="{{ asset( 'vendor/laravel-h5p/js/laravel-h5p.js' ) }}"></script>
@endsection
