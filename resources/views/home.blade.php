@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @foreach($links as $link)
                        <div class="link-container">
                            <h3><a href="{{$link->url}}">{{ $link->title !== null ? $link->title : $link->url }}</a></h3>
                            <a href="/link/{{$link->id}}">Edit</a>
                            <a href="/link/{{$link->id}}/delete">Delete</a>
                            @if($link->description !== null)
                                <p>{{$link->description}}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
