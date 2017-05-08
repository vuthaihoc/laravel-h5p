@extends( 'laravel-h5p::layouts.app' )

@section( 'content' )

<div class="container">

    <div class="row">

        <div class="col-md-12">
            <div class="form-group">
                <p class="form-control-static">
                    {{ trans('laravel-h5p::laravel-h5p.content.search-result', ['count' => number_format($entrys->total())]) }}

                    <a href="{{ route("h5p.create") }}" class="btn btn-primary pull-right">{{ trans('laravel-h5p::laravel-h5p.content.create') }}</a>
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
                        <th class="text-center">작성자</th>
                        <th class="text-left">제목</th>
                        <th class="text-center">날짜</th>
                        <th class="text-center">처리</th>
                    </tr>
                </thead>

                <tbody>

                    @unless(count($entrys) >0)
                    <tr><td colspan="5" class="h5p-noresult">{{ trans('laravel-h5p::laravel-h5p.content.no-result') }}</td></tr>
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
                            <a href="{{ route('h5p.edit', $entry->id) }}" class="btn btn-default"  data-tooltip="{pos:'top'}" title="수정">수정</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>


    <div class="row">

        <div class="col-md-12 text-center">
            {!! $entrys->render() !!}
        </div>

    </div>

</div>

@endsection


@section( 'footer-script' )
<script type="text/javascript">

</script>
@endsection
