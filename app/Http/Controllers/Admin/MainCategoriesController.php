<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Config;


class MainCategoriesController extends Controller
{

    public function index(){
         $default_lang = get_default_lang();
       $categories =  MainCategory::where("translation_lang",$default_lang)->selection()->get();
        return view("admin.maincategories.index" , compact("categories"));
    }


    public function create(){
        return view("admin.maincategories.create");

    }

    public function store(MainCategoryRequest $request){

        try {



        $main_category = collect($request -> category);
       $filter =  $main_category ->filter(function ($value , $key){
            return $value['abbr'] == get_default_lang() ;
        });

       $default_category=  array_values($filter -> all() )[0];
        $filePath = '';
       if ($request ->has('photo')){
           $filePath = uploadImage('maincategories',$request ->photo);

       }
       DB::beginTransaction(); //he put all the insert method in a same pakage and when any one lose we will stop all the end of this method is commit

       $default_category_id = MainCategory:: insertGetId([
           'translation_lang' => $default_category['abbr'],
           'translation_of' => 0,
           'name' => $default_category['name'],
           'slug' => $default_category['name'],
           'photo' => $filePath,

       ]);

        $categories =  $main_category ->filter(function ($value , $key){
            return $value['abbr'] != get_default_lang() ;
        });
       if (isset($categories) && $categories ->count() > 0 ){
           $categories_arr = [];
            foreach ($categories as $category){
                $categories_arr [] = [
                    'translation_lang' => $category['abbr'],
                    'translation_of' => $default_category_id,
                    'name' => $category['name'],
                    'slug' => $category['name'],
                    'photo' => $filePath
                ];
            }
            MainCategory::insert($categories_arr);
       }
       DB::commit();
       return redirect() ->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect() ->route('admin.maincategories')->with(['error' => 'حدث خطاء ما الرجاء المحاولة لاحقا']);
        }
    }


    public function edit($id){
      $mainCategory=  MainCategory::with('categories') ->selection()->find($id);

      if (!$mainCategory){
          return redirect() ->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);
      }
          return  view('admin.maincategories.edit', compact('mainCategory'));

    }


    public function update($id , MainCategoryRequest $request){

        try {


      $main_category =  MainCategory::find($id);
        if (!$main_category){
            return redirect() ->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);
        }
      $category=  array_values($request -> category )[0];
        if (!$request ->has('category.0.active')){
            $request->request->add(['active' =>0]);
        }else{
            $request->request->add(['active' =>1]);

        }
            MainCategory::where('id' , $id)->update([
                'name' => $category['name'],
                'active'=> $request ->active,



            ]);
        //save image

        if ($request ->has('photo')){
                $filePath = uploadImage('maincategories',$request ->photo);
            MainCategory::where('id' , $id)->update([

                'photo' => $filePath,


            ]);
            }




        return redirect() ->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);
        }catch (\Exception $ex){

            return redirect() ->route('admin.maincategories')->with(['error' => 'حدث خطاء ما الرجاء المحاولة لاحقا']);

        }



    }
    public function destroy($id){
        try {
            $maincategory= MainCategory::find($id);
            if (!$maincategory){
                return redirect()->route('admin.maincategories.edit' , $id)->with(["error" => "هذا القسم غير موجودو"]);
            }
            $maincategory ->delete();



            return redirect()->route('admin.maincategories')->with(["success" => "تم حذف القسم بنجاح"]);
        }catch (\Exception $ex){
            return redirect()->route('admin.maincategories.edit')->with(["error" => "هناك خطاء ما يرجا المحاولة فيما بعد"]);

        }
    }
}
