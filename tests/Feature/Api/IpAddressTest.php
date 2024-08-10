<?php

use App\Http\Resources\IpAddressResource;
use App\Models\AuditLog;
use App\Models\IpAddress;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\json;
use function PHPUnit\Framework\assertNotEquals;

it('will validate required data', function ($key, $value) {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    json('POST', route('api.ip_address.store'), [$key => $value])
        ->assertStatus(422)
        ->assertJsonValidationErrors($key);
})->with([
    ['ip_address', 'not-valid-ip-address'],
    ['label', ''],
]);

it('can not access ip address api without logged in user', function () {

    json('POST', route('api.ip_address.store'), ['ip_address' => '127.0.0.1', 'label' => 'Default'])
        ->assertStatus(401)
        ->assertJsonFragment(['message' => 'Unauthenticated.']);

    assertDatabaseCount(IpAddress::class, 0);
});

it("can not save same ip address if it's already existing", function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $ip = IpAddress::factory()->create();

    json('POST', route('api.ip_address.store'), ['ip_address' => $ip->ip_address, 'label' => 'Default'])
        ->assertStatus(422)
        ->assertJsonFragment(['message' => 'The ip address has already been taken.']);

    assertDatabaseCount(IpAddress::class, 1);
});

it('can successfully create ip address', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $data = IpAddress::factory()->make()->toArray();

    json('POST', route('api.ip_address.store'), $data)
        ->assertStatus(201)
        ->assertJsonFragment(['message' => 'The IP address has been saved successfully.']);

    assertDatabaseCount(IpAddress::class, 1);
    assertDatabaseCount(AuditLog::class, 1);
    assertDatabaseHas('ip_addresses', $data);
});

it('can not change ip address label with null value', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $ip = IpAddress::factory()->create();

    $data = ['label' => null];

    json('PATCH', route('api.ip_address.update', $ip->id), $data)
        ->assertStatus(422)
        ->assertJsonFragment(['message' => 'The label field is required.']);

    assertNotEquals($ip->label, $data['label']);
});

it('can change ip address label', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $ip = IpAddress::factory()->create();

    $data = ['label' => fake()->name()];

    json('PATCH', route('api.ip_address.update', $ip->id), $data)
        ->assertStatus(201)
        ->assertJsonFragment(['message' => 'The IP address label has been updated.']);

    assertDatabaseHas('ip_addresses', $data);
    assertDatabaseCount(AuditLog::class, 2);
});

it('can view lists of ip addresses', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    IpAddress::factory()->create();

    $data = IpAddressResource::collection(IpAddress::latest('id')->paginate(20, ['*'], 'ip_addresses'))->toArray(request());

    json('GET', route('api.ip_address.index'))
        ->assertStatus(200)
        ->assertJsonFragment($data);
});