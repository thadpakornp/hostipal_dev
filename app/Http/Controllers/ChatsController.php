<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ChatsResource;
use App\Models\Charts_description;

class ChatsController extends Controller
{
    public function index()
    {
        return response()->json([
            'code' => '200',
            'data' => ChatsResource::collection(Charts_description::Getchats()->where('type_charts',1)->orderBy('created_at', 'asc')->get()),
        ]);
    }

}
