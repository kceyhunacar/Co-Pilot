<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Feature;
use App\Models\FeatureCategory;
use Illuminate\Http\Request;
use DataTables;

class FeatureController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $data = Feature::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    $column =  $row->title;
                    return $column;
                })
                ->addColumn('action', function ($row) {

                    $updateBtn = '    <a class="btn btn-primary btn-xs text-white" href="' . route('admin.feature.edit', $row->id) . '">Edit
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

        return view('backend.pages.feature.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $feature_category = FeatureCategory::all();
        return view('backend.pages.feature.create',[
            'feature_category'=>$feature_category
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $feature = new Feature();


        
        $feature->fill([
            'title' => $request->title,
            'type' => $request->type,
            'category' => $request->category,
        ]);
        $feature->save();

        session()->flash('success', 'Feature has been created.');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Feature $feature)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feature $feature)
    {
        $feature_category = FeatureCategory::all();

        return view('backend.pages.feature.edit', ['feature' => $feature,'feature_category'=>$feature_category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feature $feature)
    {

     
        $feature->fill([
            'title' => $request->title,
            'type' => $request->type,
            'category' => $request->category,
        ]);
        $feature->save();

        session()->flash('success', 'Feature has been updated.');
        return back();
    }


    public function delete($id)
    {


        $feature = Feature::findOrFail($id);
        $feature->delete();
        session()->flash('success', 'Feature has been deleted.');

        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $feature)
    {
        //
    }
}
