@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('sensor', $device, $sensor->deviceSensorId))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Sensore #{{$sensor->deviceSensorId}}</h1>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-area"></i> Dati real-time</h6>
            </div>
            <div class="card-body">
                <chart-management
                    v-bind:deviceId='{{ $device }}'
                    v-bind:sensorId='{{$sensor->deviceSensorId}}'
                ></chart-management>
            </div>
        </div>
    </div>
@endsection