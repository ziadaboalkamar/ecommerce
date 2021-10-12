<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;

use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
public function index(){

   $languages = Language::select() -> paginate(PAGINATION_COUNT);

    return view('admin.languages.index',compact('languages'));
}

public function create(){

     return view('admin.languages.create');
}

    public function store(LanguageRequest $request){

        try {
            Language::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with(["success" => "تم حفظ اللغة بنجاح"]);
        }catch (\Exception $ex){
            return redirect()->route('admin.languages')->with(["error" => "هناك خطاء ما يرجا المحاولة فيما بعد"]);
        }

    }


    public function edit($id){
       $languages= Language::select()->find($id);
       if (!$languages){
           return redirect()->route('admin.languages')->with(["error" => "هذه اللغة غير موجودو"]);
       }
       return view("admin.languages.edit",compact("languages"));
    }
    public function update($id ,LanguageRequest $request ){
        try {
            $languages= Language::find($id);
            if (!$languages){
                return redirect()->route('admin.languages.edit' , $id)->with(["error" => "هذه اللغة غير موجودو"]);
            }
            if (!$request ->has('active')){
                $request->request->add(["active" => 0]);
            }
            $languages ->update($request ->except("_token"));
            return redirect()->route('admin.languages')->with(["success" => "تم التحديث اللغة بنجاح"]);
        }catch (\Exception $ex){
            return redirect()->route('admin.languages.edit')->with(["error" => "هناك خطاء ما يرجا المحاولة فيما بعد"]);

        }


    }


    public function destroy($id){
        try {
            $languages= Language::find($id);
            if (!$languages){
                return redirect()->route('admin.languages.edit' , $id)->with(["error" => "هذه اللغة غير موجودو"]);
            }
            $languages ->delete();
            return redirect()->route('admin.languages')->with(["success" => "تم حذف اللغة بنجاح"]);
        }catch (\Exception $ex){
            return redirect()->route('admin.languages.edit')->with(["error" => "هناك خطاء ما يرجا المحاولة فيما بعد"]);

        }
    }
}
