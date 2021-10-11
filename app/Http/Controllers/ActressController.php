<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActressRequest;
use App\Models\Actress;
use App\Models\Film;
use Illuminate\Http\Request;

class ActressController extends Controller
{
    private $storageFileFolder='actresses';
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
                            'country' => $actress->country,
                            'films' => $actress->films->map(function ($film){
                                return [
                                    'name'=>$film->name,
                                    'year'=>$film->year,
                                    'country'=>$film->country
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
    public function store(ActressRequest $request) //POST, PUT
    {
        $data= $request->prepareData();

        if ($request->hasFile('file')) {
            $file = $request->file('file')->store($this->storageFileFolder);
        }

        $actress= Actress::updateOrCreate(
            ['name'=>$data['name']],
            $data
        );

        if (!isset($data['films'])) {
            return response()->json(
                [
                    "success"=>false,
                    "message"=>"запись создана или обновлена, но фильмов нет",
                    "errorCode" => 12
                ]
            );
        }
        $films = Film::find($data['films']);
        $actress->films()->sync($films);

        $actress->notifyTelegram($file);


        return response()->json(
            [
                "success"=>true,
                "message"=>"запись создана или обновлена"
            ]
        );

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
    public function destroy(Actress $actress) //DELETE
    {
        $actress->films()->detach(); //relation
        $actress->delete();
        return response()->json(
            [
                "success"=>true,
                "message"=>"Актриса успешно удалена"
            ]
        );

    }
}
