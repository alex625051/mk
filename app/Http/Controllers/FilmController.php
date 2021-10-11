<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Actress;
use App\Http\Requests\FilmRequest;


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
                            'country' => $film->country,
                            'actresses' => $film->actresses->map(function ($actress){
                                return [
                                    'name'=>$actress->name,
                                    'age'=>$actress->age,
                                    'country'=>$actress->country
                                ];
                            })   # так как есть релейшн метод
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
    public function store(FilmRequest $request)
    {
        $data= $request->prepareData();
        $film= Film::updateOrCreate(
            ['name'=>$data['name']],
            $data
        );
        if (!isset($data['actresses'])) {
            return response()->json(
                [
                    "success"=>false,
                    "message"=>"запись c фильмом создана или обновлена, но актрис у фильма нет",
                    "errorCode" => 14
                ]
            );
        }
        $actresses = Actress::find($data['actresses']);
        $film->actresses()->sync($actresses);

        return response()->json(
            [
                "success"=>true,
                "message"=>"запись с фильмом \"{$film->name}\" создана или обновлена"
            ]
        );

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

    public function destroy(Film $film)
    {
        $film->actresses()->detach(); //relation
        $film->delete();
        return response()->json(
            [
                "success"=>true,
                "message"=>"Фильм \"{$film->name}\" успешно удален"
            ]
        );

    }
}
