@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('device', $device->deviceId))
@section('content')
    <div class="container">
        <button class="btn-primary btn" onclick="window.history.back()">Indietro</button>
        <div class="row">
            <h2>Dispositivo #{{$device->deviceId}}</h2>
        </div>
        @foreach($device->sensorsList as $sensore)
            <div class="row">
                <h3>Sensore #{{$sensore['sensorId']}}</h3>
            </div>
            <chart-management
                v-bind:user='{!! json_encode($user) !!}'
                v-bind:device='{!! json_encode($device) !!}'
                v-bind:sensor='{!! json_encode($sensore) !!}'
            ></chart-management>
        @endforeach
    </div>
@endsection
