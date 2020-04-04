@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('gateways.create'))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Creazione gateway</h1>
        </div>
        <div class="d-sm-flex mb-4 ml-sm-auto">
            <a href="{{route('gateways.index')}}" class="btn btn-danger btn-icon-split">
                        <span class="icon text-white-50">
                          <span class="fas fa-arrow-circle-left"></span>
                        </span>
                <span class="text">Torna indietro</span>
            </a>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                   <span class="icon text-blue-50">
                          <span class="fas fa-plus-circle"></span>
                   </span>
                    Creazione gateway
                </h6>
            </div>
            @can(['isAdmin'])
                <div id="cardGateway" class="card-body">
                    <p>Puoi creare un nuovo gateway inserendo le informazioni elencate in seguito:</p>
                    <form method="POST" action="{{--route('gateways.store')--}}">
                        @csrf
                        @method('POST')
                        <div class="form-group row">
                            <label for="inputGatewayName" class="col-sm-4 col-form-label"><span class="fas fa-dungeon"></span> Nome gateway</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('gatewayName') is-invalid @enderror" id="inputGatewayName" placeholder="Nome del gateway" value="" name="gatewayName">
                                @error('gatewayName')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                    </form>
                    @endcan
                </div>
        </div>
        <div class="d-sm-flex mb-4 ml-sm-auto">
            <a href="#" id="addGateway" class="btn btn-success btn-icon-split">
                        <span class="icon text-white-50">
                          <span class="fas fa-plus-circle"></span>
                        </span>
                <span class="text">Aggiungi</span>
            </a>
        </div>
    </div>


@endsection