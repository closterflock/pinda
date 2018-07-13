@extends('layouts.app-old')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">New Link</div>

                    <form method="post" action="/link/{{$link->id}}/save">
                        {{ csrf_field() }}
                        <input name="title" placeholder="Title" value="{{$link->title}}">
                        <input name="description" placeholder="Description" value="{{$link->description}}">
                        <input type="url" name="url" placeholder="URL*" value="{{$link->url}}">
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
