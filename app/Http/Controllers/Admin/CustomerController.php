<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })
            ->latest()
            ->paginate(10);

        return inertia('Admin/Customers/Index', ['customers' => $customers]);
    }
}
