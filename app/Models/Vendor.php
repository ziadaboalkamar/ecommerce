<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model

{
    use Notifiable;
    protected  $table = 'vendors';
    protected $fillable = [
        'id','name','logo', 'mobile','email', 'category_id','address','active','created_at','updated_at'
    ];
    protected $hidden = ['category_id'];

    public function scopeActive($query){
        return $query ->where('active',1);
    }
    public function getLogoAttribute($val){
        return ($val !== null)? asset('assets/'.$val) : "";
    }

    public function scopeSelection($query){

        return $query->select( 'id','name','logo', 'mobile', 'category_id','active');
    }

    public function category(){

      return  $this ->belongsTo('App\Models\MainCategory','category_id','id');
    }
    public function getActive(){
        return $this ->active == 1 ? "مفعل" : "غير مفعل";
    }

}
