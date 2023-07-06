<?php

namespace App\Http\Controllers;

use App\Http\Requests\trainer\AddTableRequest;
use App\Http\Requests\trainer\DeleteTableRequest;
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
    public function getAllTables()
    {
        $user = Auth::user(); // trainer
        $eatTables = EatTable::where('trainer_id', $user->id)->get()->map->format();
        // select * from eat_tables where trainer_id = ?
        return LocalResponse::returnData('tables', $eatTables);
    }
    public function deleteTable(DeleteTableRequest $request)
    {
        $table = EatTable::where('id', $request->id)->first();
        $table->delete();
        return LocalResponse::returnMessage("Eat Table deleted successfully.");
    }
}
