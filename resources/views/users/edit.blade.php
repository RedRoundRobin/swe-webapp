@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('users.edit', $user->userId))
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-edit"></i>
                Modifica informazioni
            </h6>
        </div>
        <div class="card-body">
            @if($user->type<Auth::user()->type)
                <p>Puoi modificare le informazioni dell'account cambiando i campi contenuti di seguito.</p>
                <form method="POST" action="{{route('users.update', $user->userId)}}">
                @csrf
                @method('PUT')
                @canany(['isAdmin', 'isMod'])
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label"><i class="fas fa-user"></i> Nome</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName" placeholder="Nome" value="{{old('name')??$user->name}}" name="name">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputSurname" class="col-sm-4 col-form-label"><i class="fas fa-user"></i> Cognome</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control @error('surname') is-invalid @enderror" id="inputSurname" placeholder="Cognome" value="{{old('surname')??$user->surname}}" name="surname">
                            @error('surname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                @endcanany
                @can('isAdmin')
                    <div class="form-group row">
                        <label for="inputType" class="col-sm-4 col-form-label"><i class="fas fa-user-tag"></i> Ruolo</label>
                        <div class="col-sm-8">
                            <select class="form-control @error('type') is-invalid @enderror" name="type" id="inputType">
                                <option value="0" @if($user->getRole()=='Utente') selected @endif>Utente</option>
                                <option value="1" @if($user->getRole()=='Moderatore') selected @endif>Moderatore</option>
                            </select>
                            @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                @endcan

                <div class="form-group row">
                    <label for="inputEmail" class="col-sm-4 col-form-label"><i class="fas fa-envelope text-gray-500"></i> Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail" placeholder="Email" value="{{old('email')??$user->email}}" name="email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                @can('isAdmin')
                    <div class="form-group row">
                        <label for="inputTelegramName" class="col-sm-4 col-form-label"><i class="fab fa-telegram text-primary"></i> Nome Telegram</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control @error('telegramName') is-invalid @enderror" id="inputTelegramName" placeholder="Nome Telegram" value="{{old('telegramName')??$user->telegramName}}" name="telegramName">
                            @error('telegramName')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputTelegramChat" class="col-sm-4 col-form-label"><i class="fas fa-comment-dots text-primary"></i> Chat Telegram</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control @error('telegramChat') is-invalid @enderror" id="inputTelegramChat" placeholder="Chat Telegram" value="{{old('telegramChat')??$user->telegramChat}}" name="telegramChat">
                            @error('telegramChat')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fas fa-lock text-success"></i> Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="inputPassword" placeholder="Password" name="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                @endcan

                @canany(['isAdmin', 'isMod'])
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <i class="fas fa-user-times text-danger"></i>
                            Disattivazione
                        </div>
                        <div class="col-sm-8">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="deleteCheck" name="deleted" value=true @if($user->deleted || old('deleted')) checked @endif>
                                <label class="custom-control-label" for="deleteCheck">
                                    <i>L'account non verra eliminato dal database</i>
                                </label>
                            </div>
                        </div>
                    </div>
                @endcanany
                @can('isAdmin')
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <i class="fas fa-shield-alt text-info"></i>
                            Sicurezza account
                        </div>
                        <div class="col-sm-8">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="gridCheck" name="tfa" value=true @if($user->tfa || old('tfa')) checked @endif>
                                <label class="custom-control-label" for="gridCheck">
                                    <i>Autenticazione a due fattori con Telegram* </i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="my-2 small"><i class="fas fa-info-circle text-primary"></i>
                        *Per attivare l'<em>autenticazione a due fattori</em> è necessario inserire lo username Telegram
                        e avviare il bot direttamente dall'applicazione, inserendo il comando <code>/start</code> in chat.
                    </p>
                    <hr>
                @endcan
                <div class="form-group row">
                    <label for="inputPassword check" class="col-sm-4 col-form-label"><i class="fas fa-lock-open text-danger"></i> Inserisci la tua password</label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control @error('password_check') is-invalid @enderror" id="inputPassword_check" placeholder="Password" value="" name="password_check">
                        @error('password_check')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                  <i class="fas fa-edit"></i>
                                </span>
                            <span class="text">Modifica</span>
                        </button>
                        <button type="reset" class="btn btn-warning btn-icon-split">
                                <span class="icon text-white-50">
                                  <i class="fas fa-undo-alt"></i>
                                </span>
                            <span class="text">Reset</span>
                        </button>
                        <a href="{{route('users.show', $user->userId)}}" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                  <i class="fas fa-times"></i>
                                </span>
                            <span class="text">Annulla</span>
                        </a>
                    </div>
                </div>
            </form>
            @else
                <p>Non puoi modificare un'utente con il tuo stesso ruolo!</p>
            @endif
        </div>
    </div>
@endsection