@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
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
