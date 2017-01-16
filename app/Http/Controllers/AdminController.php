<?php

namespace App\Http\Controllers;
use App\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;


class AdminController extends Controller
{
    public function index(){
//        $user = Auth::user();
        return view('admin.home');
    }
    public function getWrite(){
        return view('admin.write');
    }
    public function write(Request $request){
        $title = $request->input('title');
        $body = $request->input('body');
        preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $body , $match);
        $dbResult = Content::create(array('title' => $title , 'context' => $body,'image'=>$match[1][0],'user_id'=>Auth::user()["id"],"category_id"=>1));
        $response = array ('status' => 'success','result'=>$dbResult["id"]);
        return response ()->json ($response);
    }
    public function showList(){
        $dbResult = Content::all();
        return view('admin.list')->with('lists',$dbResult);
    }
    public function uploadImg(Request $request){

        if($request->hasFile('image')){
            $filename = str_random(20).'_'.$request->file('image')->getClientOriginalName();
            $image_path = base_path() . '/public/images/upload/article/';
            $request->file('image')->move(
                $image_path, $filename
            );
            echo $filename;
        }
        else{
            echo 'Oh No! Uploading your image has failed.';
        }
    }
}
