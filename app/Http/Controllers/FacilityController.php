<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class FacilityController extends Controller
{
    //
    public function index()
    {
        $facilities = Facility::all();
        return response()->json($facilities);

    }

    public function show($id)
    {
        $facility = Facility::find($id);

        if ($facility) {
            return response()->json($facility);
        } else {
            return response()->json(['message' => 'Facility not found'], 404);
        }
    }

}
