<?php

namespace App\Http\Controllers;

use App\Models\OfficeModel;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use App\Models\Charts;
use DB;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware(['user.status'])->except('index');
    }

    public function searched(Request $request)
    {
        $users = User::where('id', '<>', Auth::user()->id)->whereNull('deleted_at');
        if ($request->input('name') != null) {
            $users->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }
        if ($request->input('surname') != null) {
            $users->where('surname', 'LIKE', '%' . $request->input('surname') . '%');
        }
        if ($request->input('status') != null) {
            $users->where('status', $request->input('status'));
        }
        if ($request->input('type') != null) {
            $users->where('type', $request->input('type'));
        }
        if ($request->input('office_id') != null) {
            $users->where('office_id', $request->input('office_id'));
        }
        $users = $users->Owner()->paginate(10, ['id', 'prefix_id', 'name', 'email', 'surname', 'status', 'office_id', 'profile']);
        $offices = OfficeModel::whereNull('deleted_at')->get(['id', 'name']);
        $requests = $request->except('_token');
        return view('users', compact('users', 'offices', 'requests'));
    }

    public function searchedChart(Request $request)
    {
        $users = Charts::whereNull('deleted_at');
        if ($request->input('search') != null) {
            $users->orWhere('name', 'LIKE', '%' . $request->input('search') . '%')
                ->orWhere('surname', 'LIKE', '%' . $request->input('search') . '%')
                ->orWhere('hn', 'LIKE', '%' . $request->input('search') . '%')
                ->orWhere('idcard', 'LIKE', '%' . $request->input('search') . '%')
                ->orWhere('phone', 'LIKE', '%' . $request->input('search') . '%');
        }
        $users = $users->Charts();
        $requests = $request->except('_token');
        return view('chart_users', compact('users', 'requests'));
    }
}
