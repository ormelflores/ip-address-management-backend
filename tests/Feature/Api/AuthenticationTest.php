<?php

use App\Models\AuditLog;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\json;

it('will validate required data', function ($key, $value) {
    json('POST', route('api.user.authenticate'), [$key => $value])
        ->assertStatus(422)
        ->assertJsonValidationErrors($key);
})->with([
    ['email', 'not-valid-email'],
    ['password', ''],
]);

it('can authenticate user successfully', function () {
    $user = User::factory()->create();

    json('POST', route('api.user.authenticate'), ['email' => $user->email, 'password' => 'password'])
        ->assertStatus(201)
        ->assertJsonFragment(['message' => 'Login successful.']);

    assertDatabaseCount(AuditLog::class, 1);
});

it('will not be able to authenticate user with incorrect password', function () {
    $user = User::factory()->create();

    json('POST', route('api.user.authenticate'), ['email' => $user->email, 'password' => 'incorrect-password'])
        ->assertStatus(422)
        ->assertJsonFragment(['message' => 'Invalid login credentials.']);

    assertDatabaseCount(AuditLog::class, 0);
});

it('can logout user successfully', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);
    
    json('POST', route('api.user.logout'))
        ->assertStatus(201)
        ->assertJsonFragment(['message' => 'Logout successful.']);

    assertDatabaseCount(AuditLog::class, 1);
});