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
    <form class="form-horizontal" action="/query7" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="col-sm-offset-2 col-sm-8">
            <h1 class="text-center add-margin-top-bottom">Data Query 7</h1>
        </div>

        <div class="form-group">
            <label for="adm_level" class="col-sm-3 control-label">ADM Level</label>

            <div class="col-sm-6">
                <input class="form-control" name="adm_level" id="adm_level">
            </div>

            <label for="location_name" class="col-sm-3 control-label">Location Name</label>

            <div class="col-sm-6">
                <input class="form-control" name="location_name" id="location_name">
            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8 add-margin-top-bottom">
                <button type="submit" class="btn btn-default center-block">Selesai</button>
            </div>
        </div>

    </form>
@endsection