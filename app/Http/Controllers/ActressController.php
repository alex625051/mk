<?php

namespace App\Http\Controllers;

use App\Models\Actress;
use Illuminate\Http\Request;

class ActressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //get
    {
        $country = \request('country');
        $age_lte = \request('age_lte');
        $age_gte = \request('age_gte');
        return response()->json(
            [
                'actresses' => Actress::query()
                    ->when($country, function ($query) use ($country) {
                        return $query->where('country', $country);
                    })
                    ->when($age_gte, function ($query) use ($age_gte) {
                        return $query->where('age',">=", $age_gte);
                    })
                    ->when($age_lte, function ($query) use ($age_lte) {
                        return $query->where('age',"<=", $age_lte);
                    })
                    ->get()
                    ->mapWithKeys(function ($actress) {
                        return [$actress->id => [
                            'name' => $actress->name,
                            'age' => $actress->age,
                            'country' => $actress->country
                        ]];
                    })
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() //get
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) //POST, PUT
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) //GET
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) //GET
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) //POST
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) //DELETE
    {
        //
    }
}
