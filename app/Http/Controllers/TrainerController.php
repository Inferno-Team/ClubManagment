<?php

namespace App\Http\Controllers;

use App\Http\Requests\trainer\AddTableIngredientRequest;
use App\Http\Requests\trainer\AddTableRequest;
use App\Http\Requests\trainer\DeleteTableIngredientRequest;
use App\Http\Requests\trainer\DeleteTableRequest;
use App\Http\Requests\trainer\UpdateTableIngredientRequest;
use App\Models\EatTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LocalResponse;
use App\Models\EatTableItem;

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
    public function getTableIngredient(DeleteTableRequest $request)
    {
        $items = EatTableItem::where('eat_table_id', $request->id)->get();
        return LocalResponse::returnData("items", $items);
    }
    public function addTableIngredient(AddTableIngredientRequest $request)
    {
        $item = EatTableItem::create($request->values());
        return LocalResponse::returnData("item", $item, 'Created Successfully.');
    }
    public function deleteTableIngredient(DeleteTableIngredientRequest $request)
    {
        $item = EatTableItem::where('id', $request->id)->first();
        $item->delete();
        return LocalResponse::returnMessage("Ingredient deleted successfully.");
    }
    public function updateTableIngredient(UpdateTableIngredientRequest $request)
    {
        $item = EatTableItem::where('id', $request->id)->first();
        $item->update($request->all());
        return LocalResponse::returnMessage("Ingredient updated successfully.");
    }
}
