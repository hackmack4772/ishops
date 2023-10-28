<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd();
$categories=Category::latest();
if($request->keyword){
   $categories=Category::where("name","like","%".$request->keyword."%");
}
$categories=$categories->paginate(10);
// dd($categories);
        return view("admin.categories",compact("categories"));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("admin.create_category");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate();
        $validate = \Validator::make($request->all(), ["name" => "required", "slug" => "required|unique:categories"]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "errors" => $validate->errors()]);
        }

        $category = Category::create($request->only(["name", "slug", "status"]));
        $request->session()->flash("success","Category Created Successfully");
        return response()->json(["status" => true, "message" => "Inserted Successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
   public function getSlug(Request $request){
        $slug="";

        if(isset($request->name)){
            $slug=\Str::slug($request->name);
        }
        return response()->json([
            "status"=>true,
            "slug"=>$slug
        ]);
    }
}
