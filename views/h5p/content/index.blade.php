@extends( config('laravel-h5p.layout') )

@section( 'h5p' )
<div class="container-fluid">

    <div class="row">

        <div class="col-md-12">
            <div class="form-group">
                <p class="form-control-static">
                    {{ trans('laravel-h5p.content.search-result', ['count' => number_format($entrys->total())]) }}

                    <a href="{{ route("h5p.create") }}" class="btn btn-primary pull-right">{{ trans('laravel-h5p.content.create') }}</a>
                </p>
            </div>

        </div>

    </div>
    
    <div class="row">

        <div class="col-md-12">


            <table class="table text-middle text-center h5p-lists">
                <colgroup>
                    <col width="10%">
                    <col width="20%">
                    <col width="*">
                    <col width="10%">
                    <col width="10%">
                </colgroup>

                <thead>
                    <tr class="active">
                        <th class="text-center">#</th>
                        <th class="text-center">{{ trans('laravel-h5p.content.creator') }}</th>
                        <th class="text-left">{{ trans('laravel-h5p.content.title') }}</th>
                        <th class="text-center">{{ trans('laravel-h5p.content.created_at') }}</th>
                        <th class="text-center">{{ trans('laravel-h5p.content.action') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @unless(count($entrys) >0)
                    <tr><td colspan="5" class="h5p-noresult">{{ trans('laravel-h5p.common.no-result') }}</td></tr>
                    @endunless

                    @foreach($entrys as $n => $entry)
                    <tr>

                        <td class="">
                            {{ $entry->id }}
                        </td>

                        <td class="">
                            {{ $entry->get_user()->name }}
                        </td>

                        <td class="text-left">
                            <a href="{{ route('h5p.show', $entry->id) }}">{{ $entry->title }}</a>
                        </td>

                        <td class="">
                            {{ $entry->updated_at->format('Y.m.d') }}
                        </td>

                        <td>
                            <a href="{{ route('h5p.edit', $entry->id) }}" class="btn btn-default"  data-tooltip="{pos:'top'}" title="{{ trans('laravel-h5p.content.edit') }}">{{ trans('laravel-h5p.content.edit') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>


    <div class="row">

        <div class="col-md-12 text-center" style='margin-top:20px;'>
            {!! $entrys->render() !!}
        </div>

    </div>

</div>

@endsection


@push( 'h5p-header-script' )
@endpush

@push( 'h5p-footer-script' )
    <script type="text/javascript">
    </script>
@endpush