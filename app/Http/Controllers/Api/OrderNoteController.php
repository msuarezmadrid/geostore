<?php

namespace App\Http\Controllers\Api;

use Log;
use Validator;
use App\Seller;
use App\OrderNote;
use App\OrderNoteDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ApiController;

class OrderNoteController extends ApiController
{
    private const SELL_VALIDATION_ERROR = 'Error de validación';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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


        $datas = new OrderNote();
        
        $data->recordsTotal = $datas->count();
        if($start !== null && $length !== null) {
            
            if ($filters !== null) 
            {
               
                $data->recordsTotal = $datas->count();
                if($filters['order_id'] !== null) {
                    
                    $datas = $datas->where('id','LIKE',"%{$filters['order_id']}%");
                }

                if($filters['seller_id'] !== null) {
                    $datas = $datas->where('seller_id','=',$filters['seller_id']);
                }
                
            }

            if ($order !== null) 
            {
                $datas = $datas->orderBy($field, $dir);
            }
           

            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            $datas = $datas->offset($start)->limit($length);
        }

        
        $data->orderNotes = $datas->get();
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
        
        foreach ($data->orderNotes as $key => $value) {
        
           $data->orderNotes[$key]->seller = Seller::find($value->seller_id);
        }
        
        
        return $this->json($request, $data, 200);
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
        try {
            $validator = Validator::make($request->all(), [
                'client_id'     => 'sometimes|integer',
                'seller'        => 'integer|required',
                'type'          => ['required', Rule::in(['ticket', 'bill'])],
                'cart'          => 'json|required'
            ]);
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                throw new \Exception(self::SELL_VALIDATION_ERROR);
            }
            $details          = json_decode($request->input('cart'));
            $onote            = new OrderNote();
            $onote->seller_id = $request->input('seller');
            $onote->type      = $request->input('type') == 'ticket' ? 1 : 2;
            if ($request->has('client_id')) {
                $onote->client_id = $request->input('client_id');
            }
            $onote->save();
            foreach ($details as $detail) {
                $ond                = new OrderNoteDetail();
                if($detail->item_id != 0) {
                    $ond->item_id       = $detail->item_id;
                } else {
                    $ond->name = $detail->name;
                }
                $ond->qty           = $detail->qty;
                $ond->price         = $detail->price;
                $ond->withdraw      = $detail->withdraw;
                $ond->order_note_id = $onote->id;
                $ond->save();
            }
            return $this->json($request, $onote, 200);

        } catch (\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, [
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $onote = OrderNote::find($id);
        if ($onote == null) {
            return $this->json($request, null, 404);
        }
        $info  = $onote->getResumeInfo();

        if ($info->quote_type == 1){
            
            return $this->json($request, null, 404);
        }
        return $this->json($request, $info, 200);
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
