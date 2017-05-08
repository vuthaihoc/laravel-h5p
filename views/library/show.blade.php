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

@push( 'header-script' )
    @foreach($required_files['styles'] as $style)
    {{ Html::style($style) }}
    @endforeach    
@endpush

@push( 'footer-script' )
    @foreach($required_files['scripts'] as $script)
    {{ Html::script($script) }}
    @endforeach   
@endpush