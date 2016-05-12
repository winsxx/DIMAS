@extends('master')

@section('content')

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <nav class="navbar navbar-default top-nav">
        <div class="container">
        </div>
    </nav>
    <form class="form-horizontal" action="/query2" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="col-sm-offset-2 col-sm-8">
            <h1 class="text-center add-margin-top-bottom">Data Query 2</h1>
        </div>

        <div class="form-group">
            <label for="disaster_event" class="col-sm-3 control-label">Disaster Event</label>

            <div class="col-sm-6">
                <input class="form-control" name="disaster_event" id="disaster_event">
            </div>

            <label for="disaster_type" class="col-sm-3 control-label">Disaster Type</label>

            <div class="col-sm-6">
                <input class="form-control" name="disaster_type" id="disaster_type">
            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8 add-margin-top-bottom">
                <button type="submit" class="btn btn-default center-block">Selesai</button>
            </div>
        </div>

    </form>
@endsection