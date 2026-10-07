<?php

namespace App\Http\Controllers;

use App\Models\Treatment;

class TreatmentController extends Controller
{
    public function index()
    {
        return view('pages.treatments', [
            'groups' => Treatment::published()->orderBy('sort')->get()->groupBy('group'),
        ]);
    }

    public function show(Treatment $treatment)
    {
        abort_unless($treatment->published, 404);
        return view('pages.treatment', [
            'treatment' => $treatment,
            'others' => Treatment::published()->where('id', '!=', $treatment->id)->orderBy('sort')->get(),
        ]);
    }
}
