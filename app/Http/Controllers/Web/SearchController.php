<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $cars = Car::where('brand', 'like', '%' . $query . '%')->get();
        return response()->json(['cars' => $cars]);
    }
}
