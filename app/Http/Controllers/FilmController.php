<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $country = request('country');
        $year_lte = request('year_lte');
        $year_gte = request('year_gte');

        return response()->json(
            [
                'films' => Film::query()
                    ->when($country, function ($query) use ($country) {
                        return $query->where('country', $country);
                    })
                    ->when($year_gte, function ($query) use ($year_gte) {
                        return $query->where('year',">=", $year_gte);
                    })
                    ->when($year_lte, function ($query) use ($year_lte) {
                        return $query->where('year',"<=", $year_lte);
                    })
                    ->get()
                    ->mapWithKeys(function ($film) {
                        return [$film->id => [
                            'name' => $film->name,
                            'year' => $film->year,
                            'country' => $film->country
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
