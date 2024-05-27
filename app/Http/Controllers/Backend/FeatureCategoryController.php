<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\FeatureCategory;
use Illuminate\Http\Request;
use DataTables;

class FeatureCategoryController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $data = FeatureCategory::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    $column =  $row->title;
                    return $column;
                })
                ->addColumn('action', function ($row) {

                    $updateBtn = '    <a class="btn btn-primary btn-xs text-white" href="' . route('admin.featurecategory.edit', $row->id) . '">Edit
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

        return view('backend.pages.featurecategory.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.featurecategory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $featurecategory = new FeatureCategory();


        
        $featurecategory->fill([
            'title' => $request->title
        ]);
        $featurecategory->save();

        session()->flash('success', 'FeatureCategory has been created.');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(FeatureCategory $featurecategory)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeatureCategory $featurecategory)
    {

        // dd($featurecategory->title);
        // dd($featurecategory->translations['title']['en']);
        // dd($featurecategory['tr']->title);
        return view('backend.pages.featurecategory.edit', ['featurecategory' => $featurecategory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeatureCategory $featurecategory)
    {

        $featurecategory->fill([
            'title' => $request->title
        ]);
        $featurecategory->save();

        session()->flash('success', 'FeatureCategory has been updated.');
        return back();
    }


    public function delete($id)
    {


        $featurecategory = FeatureCategory::findOrFail($id);
        $featurecategory->delete();
        session()->flash('success', 'FeatureCategory has been deleted.');

        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeatureCategory $featurecategory)
    {
        //
    }
}
