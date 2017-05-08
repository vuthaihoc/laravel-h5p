@extends( config('laravel-h5p.layout') )

@section( 'content' )
<div class="container-fluid">
    <div class="row">

        <div class="col-md-12">




            <h3>{{ $library->title }}</h3>

            Version
            {{ $settings['libraryInfo']['info']['Version'] }}

            <hr>

            Fullscreen
            {{ $settings['libraryInfo']['info']['Fullscreen'] }}

            <hr>

            Content library
            {{ $settings['libraryInfo']['info']['Content library'] }}


            <hr>

            Used by
            {{ $settings['libraryInfo']['info']['Content library'] }}

        </div>

    </div>

</div>

@endsection



@section( 'header-script' )

{{--    core styles       --}}
@foreach($header_files as $script)
{{ Html::script('vendor/h5p/h5p-core/'.$script) }}
@endforeach


{{ Html::style('vendor/h5p/h5p-core/styles/h5p.css') }}
{{ Html::style('vendor/h5p/h5p-core/styles/h5p-admin.css') }}
{{ Html::script('vendor/h5p/h5p-core/js/h5p-library-details.js') }}

@endsection



@section( 'footer-script' )
<script type="text/javascript">
    H5PAdminIntegration = {!! json_encode($settings) !!};
</script>

<script type="text/javascript">

</script>
@endsection
