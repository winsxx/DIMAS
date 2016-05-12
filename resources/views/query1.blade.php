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
    <form class="form-horizontal" action="/query1" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="col-sm-offset-2 col-sm-8">
            <h1 class="text-center add-margin-top-bottom">Data Query 1</h1>
        </div>

        <div class="form-group">
            <label for="adm_level" class="col-sm-3 control-label">Adm Level</label>

            <div class="col-sm-6">
                <input class="form-control" name="adm_level" id="adm_level">
            </div>

            <label for="location_name" class="col-sm-3 control-label">Location Name</label>

            <div class="col-sm-6">
                <input class="form-control" name="location_name" id="location_name">
            </div>

            <label for="disaster_type" class="col-sm-3 control-label">Disaster Type</label>

            <div class="col-sm-6">
                <input class="form-control" name="disaster_type" id="disaster_type">
            </div>

            <label for="start_date" class="col-sm-3 control-label">Start Date</label>

            <div class="col-sm-6">
                <input class="form-control" name="start_date" id="start_date">
            </div>

            <label for="end_date" class="col-sm-3 control-label">End Date</label>

            <div class="col-sm-6">
                <input class="form-control" name="end_date" id="end_date">
            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8 add-margin-top-bottom">
                <button type="submit" class="btn btn-default center-block">Selesai</button>
            </div>
        </div>

    </form>
@endsection