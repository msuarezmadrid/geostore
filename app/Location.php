<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\StockItem;

class Location extends Model
{
    protected $fillable = [
        'code',
        'name', 
        'description', 
        'location_id',
        'location_type_id',
        'x',
        'y',
        'z',
		'latitude',
        'longitude',
        'address'
    ];

    public function childs(){
        $res = new \stdClass();
        $res->loc = $this;

        $locs = $this->where('location_id', $this->id)->get();
        $childs = [];
        foreach ($locs as $key => $value) {
            $res2 = new \stdClass();
            $res2->id = $value->id;
            $res2->text = '<a href="locations/'.$value->id.'">'.$value->name.'</a>';
            $res2->children = $value->childs();
            array_push($childs, $res2);
        }
        $res->children = $childs;

        return $childs;
    }
    public function level(){
        $res = 0;
        $father = $this;
        while($father!==null){
            $father = Location::find($father->location_id);
            $res++;
        }
        return $res;
    }


    public function orderedTree()
    {
        $res1 = ["id"  => $this->id, 
                "code"  => $this->code,
                "name"  => $this->name,
                "level" => $this->level()];
        $res = [];
        array_push($res, $res1);
        $childs = $this->childrens();
        foreach ($childs as $key => $value) {
            $tree = $value->location->orderedTree();
            foreach ($tree as $key2 => $value2) {
                array_push($res, $value2);
            }
            
        }

        return $res;
    }

    public function possibleFathers()
    {
        $res = new \stdClass();
        $res->loc = $this;
        $res->childs = $this->childrens();
        $cantBeFathers = [$this];
        $childs = $res->childs;
        $cantBeFathersCount = 0;
        while ($childs!=null){

            foreach ($childs as $key => $value) {
                array_push($cantBeFathers, $value->location);
            }
            $cantBeFathersCount++;
            $childs = $cantBeFathers[$cantBeFathersCount];
            $childs = $childs->childrens();
            
        }
        $res->cantBeFathers = $cantBeFathers;

        
        return $res;
    }

    public function allChildIds(){
    
      
        $res = [];
        array_push($res, $this->id);
        $childs = $this->childrens();
        foreach ($childs as $key => $value) {
            $ress = $value->location->allChildIds();
            foreach ($ress as $key2 => $value2) {
                array_push($res, $value2);
            }
            
        }

        return $res;
    }

    public function childrens(){
        $res = new \stdClass();
        $res->loc = $this;

        $locs = $this->where('location_id', $this->id)->get();
        $childs = [];
        foreach ($locs as $key => $value) {
            $res2 = new \stdClass();
            $res2->location = $value;
            $res2->childrens = $value->childrens();
            array_push($childs, $res2);
        }
        $res->children = $childs;

        return $childs;
    }

    public function getItemStock($item, $price, $uom) {
        if ($uom != 1) {
            return StockItem::where('location_id', $this->id)
                        ->where('item_id', $item)
                        ->where('price', $price)
                        ->sum('qty');
        }else{
            return StockItem::where('location_id', $this->id)
                        ->where('item_id', $item)
                        ->where('price', $price)
                        ->count();
            }
    }

}
