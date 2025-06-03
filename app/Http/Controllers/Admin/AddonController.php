<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;

class AddonController extends Controller
{

    public function index()
    {
        $addons = Addon::latest()->paginate(10);
        return inertia('Admin/Addons/Index', ['addons' => $addons]);
    }

    public function create()
    {
        return inertia('Admin/Addons/Create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:driver,fuel,insurance,additional_service',
        ]);

        Addon::create($validatedData);

        return redirect()->route('admin.addons.index');
    }

    public function edit(Addon $addon)
    {
        return inertia('Admin/Addons/Edit', ['addon' => $addon]);
    }

    public function update(Request $request, Addon $addon)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:driver,fuel,insurance,additional_service',
        ]);

        $addon->update($validatedData);

        return redirect()->route('admin.addons.index');
    }

    public function destroy(Addon $addon)
    {
        $addon->delete();

        return redirect()->route('admin.addons.index');
    }
}
