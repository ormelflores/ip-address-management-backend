<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreIpAddressRequest;
use App\Http\Requests\Api\UpdateIpAddressRequest;
use App\Http\Resources\IpAddressResource;
use App\Models\IpAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IpAddressController extends Controller
{
    // IP Address lists
    public function index(): AnonymousResourceCollection
    {
        return IpAddressResource::collection(IpAddress::latest('id')->paginate(20, ['*'], 'ip_addresses'));
    }

    // Store IP address
    public function store(StoreIpAddressRequest $request): JsonResponse
    {
        IpAddress::create($request->only(['ip_address', 'label']));

        return response()->json([
            'message' => 'The IP address has been saved successfully.',
        ], 201);
    }

    // Update IP address label
    public function update(IpAddress $ipAddress, UpdateIpAddressRequest $request): JsonResponse
    {
        $ipAddress->update($request->only(['label']));

        return response()->json([
            'message' => 'The IP address label has been updated.',
        ], 201);
    }
}
