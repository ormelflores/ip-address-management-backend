<?php

use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use App\Models\User;

use function Pest\Laravel\json;

it('can not access audit log api lists without logged in user', function () {
    json('GET', route('api.audit_logs.index'))
        ->assertStatus(401)
        ->assertJsonFragment(['message' => 'Unauthenticated.']);
});

it('can access audit log api lists successfuly', function () {
    $user = User::factory()->create();

    json('POST', route('api.user.authenticate'), ['email' => $user->email, 'password' => 'password'])
        ->assertStatus(201)
        ->assertJsonFragment(['message' => 'Login successful.']);

    $data = AuditLogResource::collection(AuditLog::latest('id')->paginate(20, ['*'], 'audit_logs'))->toArray(request());

    json('GET', route('api.audit_logs.index'))
        ->assertStatus(200)
        ->assertJsonFragment($data);
});