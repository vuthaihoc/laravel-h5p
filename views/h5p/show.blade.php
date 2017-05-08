@extends( config('laravel-h5p.layout') )

@section( 'content' )
<div class="container-fluid">

    <div class="row">

        <div class="col-md-12">

            {!! $embed_code  !!}

            <br/>
            <p class='text-center'>

                <a href="{{ url()->previous() }}" class="btn btn-default"><i class="fa fa-reply"></i> {{ trans('laravel-h5p::laravel-h5p.content.cancel') }}</a>

            </p>
        </div>

    </div>

</div>
@endsection

@push( 'header-script' )
    {{--    core styles       --}}
    @foreach($settings['core']['styles'] as $style)
    {{ Html::style($style) }}
    @endforeach
@endpush

@push( 'footer-script' )
    <script type="text/javascript">
        H5PIntegration = {!! json_encode($settings) !!};
    </script>

    {{--    core script       --}}
    @foreach($settings['core']['scripts'] as $script)
    {{ Html::script($script) }}
    @endforeach
@endpush