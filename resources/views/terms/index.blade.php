@extends('layouts.app')

@section('content')
    @if(count($terms) > 0)
        <div class="inner cover">
            <h1 class="cover-heading">Terms and conditions</h1>
            @foreach($terms as $term)
                <a href="/terms/{{$term->id}}">Published at {{$term->published_at}}</a><br>
            @endforeach
        {{ $terms->links() }}
        </div>

    @else
        <p>No terms and conditions found.</p>
    @endif
@endsection
