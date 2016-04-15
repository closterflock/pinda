@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">New Link</div>

                    <form method="post" action="/link/new">
                        {{ csrf_field() }}
                        <input name="title" placeholder="Title">
                        <input name="description" placeholder="Description">
                        <input type="url" name="url" placeholder="URL*">
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
