<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;
class Category extends Model
{
    protected $fillable = ["name", "category_id"];

    public function fullRoute(){
	    $res = $this->addFathers($this->id, $this->name);
	    return $res;
	}

	public function addFathers($id, $name){
		$cat = Category::where('id', $id)->first();
		$father = Category::where('id', $cat->category_id)->first();
		$fullname = $name;
		if($father !== null){
			$fullname = $father->name."/".$name;
	 		$fullname = $father->addFathers($father->id, $fullname);
		}
	    return $fullname;
	}

	public function childrens(){
        $res = new \stdClass();
        $res->cat = $this;

        $cats = $this->where('category_id', $this->id)->get();
        $childs = [];
        foreach ($cats as $key => $value) {
            $res2 = new \stdClass();
            $res2->category = $value;
            $res2->childrens = $value->childrens();
            if($value->enterprise_id == $this->enterprise_id){
                array_push($childs, $res2);
            }
            
        }
        $res->children = $childs;

        return $childs;
    }

    public function fullMap() {
        $categories = $this::get();
        return $this->buildTreeFromObjects($categories);
    }

    //Tomado de: https://gist.github.com/vyspiansky/6552875
    function buildTreeFromObjects($items)
    {
        $childs = [];
        foreach ($items as $item) {
            $childs[$item->category_id ?? 0][] = $item;
        }
        foreach ($items as $item) {
            if (isset($childs[$item->id])) {
                $item->childs = $childs[$item->id];
            }
        }
        return $childs[0] ?? [];
    }

	public function possibleFathers()
    {
        $res = new \stdClass();
        $res->cat = $this;
        $res->childs = $this->childrens();
        $cantBeFathers = [$this];
        $childs = $res->childs;
        $cantBeFathersCount = 0;
        while ($childs!=null){

            foreach ($childs as $key => $value) {
                array_push($cantBeFathers, $value->category);
            }
            $cantBeFathersCount++;
            $childs = $cantBeFathers[$cantBeFathersCount];
            $childs = $childs->childrens();
            
        }

        $cats = Category::where('enterprise_id', '<>',$this->enterprise_id)->get();
        foreach ($cats as $key => $cat) {
            array_push($cantBeFathers, $cat);
        }

        
        $res->cantBeFathers = $cantBeFathers;
        return $res;
    }

    

}

