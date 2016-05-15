@extends('master')

@section('content')

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form id="formQuery8" class="form-horizontal" action="/query8" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="col-sm-offset-2 col-sm-8">
            <h1 class="text-center add-margin-top-bottom">Data Query 8</h1>
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

            <label for="adm_level" class="col-sm-3 control-label">ADM Level</label>

            <div class="col-sm-6">
                <input class="form-control" name="adm_level" id="adm_level">
            </div>

            <label for="location_name" class="col-sm-3 control-label">Location Name</label>

            <div class="col-sm-6">
                <input class="form-control" name="location_name" id="location_name">
            </div>

            <label for="start_date" class="col-sm-3 control-label">Start Date</label>

            <div class="col-sm-6">
                <input class="form-control" name="start_date" id="start_date" placeholder="YYYY-MM-DD">
            </div>

            <label for="end_date" class="col-sm-3 control-label">End Date</label>

            <div class="col-sm-6">
                <input class="form-control" name="end_date" id="end_date" placeholder="YYYY-MM-DD">
            </div>

            <label for="additional_param" class="col-sm-3 control-label">Parameter Query Tambahan</label>

            <div class="col-sm-6">
                <div class="radio">
                    <label>
                        <input type="radio" name="additional_param" id="additional_param_refugee" value="0">
                        Refugee Camp
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="additional_param" id="additional_param_medical" value="1">
                        Medical Facility
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="additional_param" id="additional_param_gender" value="2">
                        Gender
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="additional_param" id="additional_param_age" value="3">
                        Age
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="additional_param" id="additional_param_status" value="4">
                        Status
                    </label>
                </div>
            </div>

        <div class="form-group" id="refugee_camp_part" style='display:none'>
            <label for="refugee_name" class="col-sm-3 control-label">Refugee Name</label>

            <div class="col-sm-6">
                <input class="form-control" name="refugee_name" id="refugee_name">
            </div>

            <label for="refugee_type" class="col-sm-3 control-label">Refugee Type</label>

            <div class="col-sm-6">
                <input class="form-control" name="refugee_type" id="refugee_type">
            </div>
        </div>

        <div class="form-group" id="medical_facility_part" style='display:none'>
           <label for="medical_name" class="col-sm-3 control-label">Medical Name</label>

            <div class="col-sm-6">
                <input class="form-control" name="medical_name" id="medical_name">
            </div>

            <label for="medical_type" class="col-sm-3 control-label">Medical Type</label>

            <div class="col-sm-6">
                <input class="form-control" name="medical_type" id="medical_type">
            </div>
        </div>

        <div class="form-group" id="gender_part" style='display:none'>
            <label for="gender" class="col-sm-3 control-label">Gender</label>

            <div class="col-sm-6">
                <input class="form-control" name="gender" id="gender">
            </div>
        </div>

        <div class="form-group" id="age_part" style='display:none'>
            <label for="age" class="col-sm-3 control-label">Age</label>

            <div class="col-sm-6">
                <input class="form-control" name="age" id="age">
            </div>
        </div>

        <div class="form-group" id="status_part" style='display:none'>
            <label for="status" class="col-sm-3 control-label">Status</label>

            <div class="col-sm-6">
                <input class="form-control" name="status" id="status">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8 add-margin-top-bottom">
                <button type="submit" class="btn btn-default center-block">Selesai</button>
            </div>
        </div>

        <script type="text/javascript">
            var chosen_value;
            jQuery(document).ready(function() {
                $("input[name='additional_param']:radio").on('change', function(){
                    chosen_value = $('input[name="additional_param"]:checked').val();
                    document.getElementById('refugee_camp_part').style.display = "none";
                    document.getElementById('medical_facility_part').style.display = "none";
                    document.getElementById('gender_part').style.display = "none";
                    document.getElementById('age_part').style.display = "none";
                    document.getElementById('status_part').style.display = "none";
                    if (chosen_value == 0){
                        document.getElementById('refugee_camp_part').style.display = "block";
                    }
                    else if (chosen_value == 1){
                        document.getElementById('medical_facility_part').style.display = "block";
                    }
                    else if (chosen_value == 2){
                        document.getElementById('gender_part').style.display = "block";
                    }
                    else if (chosen_value == 3){
                        document.getElementById('age_part').style.display = "block";
                    }
                    else if (chosen_value == 4){
                        document.getElementById('status_part').style.display = "block";
                    }
                });
            });
        </script>

    </form>
@endsection