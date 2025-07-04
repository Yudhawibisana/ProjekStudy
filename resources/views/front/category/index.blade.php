@extends('front.layout.template')

@section('title', 'Category ' . $category . ' - StudyGroup')
@section('content')
    <!-- Page content-->
    <div class="container">
        <div class="mb-3">
            <form action="{{ route('search') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input class="form-control" type="text" name="keyword" placeholder="search Categories..." />
                    <button class="btn btn-primary" id="button-search" type="submit">Submit</button>
                </div>
            </form>
        </div>


        <p>Showing Postingans with Category : <b>{{ $category }}</b></p>


        <div class="row">
            @forelse ($postingans as $item)
                <div class="col-4">
                    <div class="card mb-4 shadow">
                        <a href="{{ url('p/' . $item->slug) }}">
                            <img class="card-img-top featured-img" src="{{ asset('storage/back/' . $item->img) }}"
                                alt="..." />
                        </a>
                        <div class="card-body">
                            <div class="small text-muted">{{ $item->created_at->format('d-m-Y') }}</div>
                            <h2 class="card-title">{{ $item->title }}</h2>
                            <p class="card-text">{{ Str::limit(strip_tags($item->desc), 100, '...') }}</p>
                            <a class="btn btn-primary" href="{{ url('p/' . $item->slug) }}">Read more →</a>
                        </div>
                    </div>

                </div>
            @empty
                <h3 class="mb-5">Not Found</h3>
            @endforelse
        </div>
        {{ $postingans->links() }}

    </div>
@endsection
