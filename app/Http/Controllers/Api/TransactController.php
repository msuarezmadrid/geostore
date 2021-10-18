<?php

namespace App\Http\Controllers\Api;

use Log;
use Carbon\Carbon;
use App\Transact;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function search(Request $request)
    {
        $data = new \stdClass();

        $start = $request->input('start');
        $length = $request->input('length');
        $draw = $request->input('draw');

        $columns = $request->input('columns');
        $order = $request->input('order');

        $dir = $order[0]['dir'];
        $field = $columns[$order[0]['column']]['data'];

        $filters = $request->input('filters');
        $data->recordsFiltered = 0;

        Log::info($filters);


        $datas = new Transact();
        $datas = $datas->where('object_id', $filters['item_id'])
                        ->where('object_type','items');
        $data->recordsTotal = $datas->count();
        if($start !== null && $length !== null) {

            

            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            $datas = $datas->offset($start)->limit($length);
        }
        if($order !== null) {
            $datas = $datas->orderBy('id', 'desc');
        }

        
        $data->transacts = $datas->get();
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
        
        foreach ($data->transacts as $key => $value) {
            $data->transacts[$key]->users = User::find($value->created_by);
        }

        // Log::info($data);
        
        return $this->json($request, $data, 200);


    }
}
