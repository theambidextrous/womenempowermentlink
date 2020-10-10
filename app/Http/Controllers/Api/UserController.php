<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function signin()
    {
        return response([
            'status' => 0,
            'message' => 'login successful',
            'payload' => [
                'user' => []
            ] 
        ], 200);
    }
    public function signup()
    {
        return response([
            'status' => 0,
            'message' => 'registration successful',
            'payload' => [
                'user' => []
            ] 
        ], 200);
    }
    public function info()
    {
        return response([
            'status' => 0,
            'message' => 'fetch successful',
            'payload' => [
                'user' => []
            ] 
        ], 200);
    }
    public function is_active()
    {
        return response([
            'status' => 0,
            'message' => 'check successful',
            'payload' => [
                'user' => []
            ] 
        ], 200);
    }
}

