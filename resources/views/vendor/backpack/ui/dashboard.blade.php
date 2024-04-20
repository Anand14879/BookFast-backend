@extends(backpack_view('blank'))

@section('content')
    @if (isset($widgets['before_content']))
        @include('backpack::inc.widgets', ['widgets' => $widgets['before_content']])
    @endif
@endsection
