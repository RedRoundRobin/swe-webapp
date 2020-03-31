<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('dashboard.index'));
});

// Home > Impostazioni
Breadcrumbs::for('settings', function ($trail) {
    $trail->parent('home');
    $trail->push('Impostazioni account', route('settings.edit'));
});
// Dispositivi
Breadcrumbs::for('devices', function ($trail) {
    $trail->parent('home');
    $trail->push('Dispositivi', route('devices.index'));
});
Breadcrumbs::for('device', function ($trail, $deviceId) {
    $trail->parent('devices');
    $trail->push('Dispositivo ' . $deviceId, route('devices.show', ['deviceId' => $deviceId]));
});
// Sensori
Breadcrumbs::for('sensors', function ($trail, $deviceId) {
    $trail->parent('device', $deviceId);
    $trail->push('Sensori', route('sensors.index',  ['deviceId' => $deviceId]));
});
Breadcrumbs::for('sensor', function ($trail, $deviceId, $sensorId) {
    $trail->parent('sensors', $deviceId);
    $trail->push('Sensore ' . $sensorId, route('sensors.index',  ['deviceId' => $deviceId, 'sensorId'=> $sensorId]));
});


// Utenti
Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Gestione Utenti', route('users.index'));
});
Breadcrumbs::for('users.show', function ($trail, $userId) {
    $trail->parent('users.index');
    $trail->push('Utente ' . $userId, route('users.show', ['userId' => $userId]));
});
Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users.index');
    $trail->push('Creazione', route('users.create'));
});
Breadcrumbs::for('users.edit', function ($trail, $userId) {
    $trail->parent('users.show', $userId);
    $trail->push('Modifica', route('users.edit', ['userId' => $userId]));
});
