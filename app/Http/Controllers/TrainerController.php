<?php

namespace App\Http\Controllers;

use App\Http\Requests\trainer\AddTableRequest;
use App\Models\EatTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LocalResponse;

class TrainerController extends Controller
{
    public function addTable(AddTableRequest $request)
    {
        $table = EatTable::create([
            'name' => $request->name,
            'trainer_id' => Auth::user()->id,
        ]);
        return LocalResponse::returnData('table', $table, 'Eating Table Created Successfully.');
    }
}
