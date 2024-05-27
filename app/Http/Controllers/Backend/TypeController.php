<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Type;
use Illuminate\Http\Request;
use DataTables;

class TypeController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $data = Type::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    $column =  $row->title;
                    return $column;
                })
                ->addColumn('action', function ($row) {

                    $updateBtn = '    <a class="btn btn-primary btn-xs text-white" href="' . route('admin.type.edit', $row->id) . '">Edit
                                                            </a>';

                    $deleteBtn = ' <button class="btn btn-danger btn-xs text-white delete-button" data-toggle="modal" data-target="#deleteModal"
                                                            data-id="' . $row->id . '">
                                                            Delete
                                                        </button>';

                    $btn = $updateBtn . $deleteBtn;

                    return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.pages.type.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = new Type();


        
        $type->fill([
            'title' => $request->title
        ]);
        $type->save();

        session()->flash('success', 'Type has been created.');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {

        // dd($type->title);
        // dd($type->translations['title']['en']);
        // dd($type['tr']->title);
        return view('backend.pages.type.edit', ['type' => $type]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {

        $type->fill([
            'title' => $request->title
        ]);
        $type->save();

        session()->flash('success', 'Type has been updated.');
        return back();
    }


    public function delete($id)
    {


        $type = Type::findOrFail($id);
        $type->delete();
        session()->flash('success', 'Type has been deleted.');

        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        //
    }
}
