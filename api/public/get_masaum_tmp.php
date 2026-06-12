<?php
header('Content-Type: application/json');
require '/home/tele/uberhealth/api/vendor/autoload.php';
$app = require_once '/home/tele/uberhealth/api/bootstrap/app.php';
$app->boot();

$user = \App\Models\User::where('username','masaum')->first();
if (!$user) { echo json_encode(['error'=>'not found']); exit; }

$token = auth('api')->login($user);
echo json_encode(['token' => $token, 'user_id' => $user->id, 'username' => $user->username]);
