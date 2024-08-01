<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Charter;
use App\Models\CharterPhoto;
use App\Models\Destination;
use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\Type;
use Illuminate\Http\Request;
use DataTables;

class CharterController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $data = Charter::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    $column =  $row->title;
                    return $column;
                })
                ->addColumn('action', function ($row) {

                    $updateBtn = '    <a class="btn btn-primary btn-xs text-white" href="' . route('admin.charter.edit', $row->id) . '">Edit
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

        return view('backend.pages.charter.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $destination = Destination::where('status', 1)->get();
        $type = Type::where('status', 1)->get();
        $feature_category = FeatureCategory::with(['getFeature'])->get();

        return view('backend.pages.charter.create', [
            'feature_category' => $feature_category,
            'destination' => $destination,
            'type' => $type,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $charter = new Charter();

        $charter->fill([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
        ]);
        $charter->save();


        foreach ($request->feature as $key => $value) {

            $charter->getFeature()->create([
                'charter' => $charter->id,
                'feature' => $key,
                'value' => $value,

            ]);
        }

        $prices['charter'] = $charter->id;
        foreach ($request->price as $key => $value) {
            $prices[$key] = $value;
        }

        $charter->getPrice()->create($prices);



        if ($request->hasFile('photos')) {

            foreach ($request->photos as $photo) {

                $file_name = uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('data/uploads/charter/'), $file_name);

                $charter->getPhotos()->create([
                    'path' =>  'data/uploads/charter/' . $file_name
                ]);
            }
        }





        session()->flash('success', 'Charter has been created.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Charter $charter)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Charter $charter)
    {
        $charter = Charter::with(['getPrice', 'getPhotos', 'getFeature'])->where('id', $charter->id)->first();

        $destination = Destination::where('status', 1)->get();
        $type = Type::where('status', 1)->get();
        $feature_category = FeatureCategory::with(['getFeature'])->get();

        return view('backend.pages.charter.edit', [
            'charter' => $charter,  'feature_category' => $feature_category,
            'destination' => $destination,
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Charter $charter)
    {

        $charter = Charter::with(['getPrice', 'getPhotos', 'getFeature'])->where('id', $charter->id)->first();

        $charter->fill([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
        ]);
        $charter->save();


        foreach ($request->feature as $key => $value) {

            $charter->getFeature()->updateOrCreate(
                [
                    'charter' => $charter->id,
                    'feature' => $key,
                ],
                [
                    'charter' => $charter->id,
                    'feature' => $key,
                    'value' => $value,
                ]
            );
        }

        $prices = [];
        foreach ($request->price as $key => $value) {

            $prices[$key] = $value;
        }

        $charter->getPrice()->updateOrCreate(['charter' => $charter->id], $prices);



        if ($request->hasFile('photos')) {

            foreach ($request->photos as $photo) {

                $file_name = uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('data/uploads/charter/'), $file_name);

                $charter->getPhotos()->create([
                    'path' =>  'data/uploads/charter/' . $file_name
                ]);
            }
        }


        session()->flash('success', 'Charter has been updated.');
        return redirect()->back();
    }


    public function delete($id)
    {


        $charter = Charter::findOrFail($id);
        $charter->delete();
        session()->flash('success', 'Charter has been deleted.');

        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Charter $charter)
    {
        //
    }


    public function photoDelete(Request $request)
    {
        $model = CharterPhoto::find($request->id);

        if ($model->forceDelete()) {
            @unlink(public_path($model->path));
            return json_encode([
                'status' => 'success'
            ]);
        } else {
            return json_encode([
                'status' => 'error'
            ]);
        }
    }
}
