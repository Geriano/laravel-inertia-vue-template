<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DataTableController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Examples/DataTable/Index');
    }

    /**
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
            'perPage' => 'nullable|integer|min:1|max:500',
            'sort.key' => 'nullable|string',
            'sort.order' => 'nullable|in:asc,desc',
        ]);

        return User::orderBy($request->input('sort.key'), $request->input('sort.order', 'asc'))
                    ->where(function ($query) use ($request) {
                        $search = '%' . $request->input('search') . '%';

                        $query->where('name', 'like', $search)
                                ->orWhere('username', 'like', $search)
                                ->orWhere('email', 'like', $search)
                                ->orWhere('verified_at', 'like', $search)
                                ->orWhere('created_at', 'like', $search)
                                ->orWhere('deleted_at', 'like', $search);
                    })
                    ->paginate($request->input('perPage', 15));
    }
}
