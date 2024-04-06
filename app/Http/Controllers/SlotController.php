<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    /**
     * Display a listing of available slots for a given facility.
     *
     * @param  int  $facilityId
     * @return \Illuminate\Http\Response
     */
     public function index($facilityId)
    {
        $slots = Slot::where('facility_id', $facilityId)
                     ->where('is_available', true)
                     ->get();

        return response()->json($slots);
    }

 public function show($id)
    {
        $sloot = Slot::find($id);

        if ($sloot) {
            return response()->json($sloot);
        } else {
            return response()->json(['message' => 'Slot not found'], 404);
        }
    }


}
