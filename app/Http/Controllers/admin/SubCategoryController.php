<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $subcategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id');
        if ($request->keyword) {

            $subcategories = $subcategories->where("sub_categories.name", "like", "%" . $request->keyword . "%");
            $subcategories = $subcategories->orWhere("categories.name", "like", "%" . $request->keyword . "%");
        }
        $subcategories = $subcategories->paginate(10);
        // dd($categories);
        return view("admin.subcategories.subcategories", compact("subcategories"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();

        return view("admin.subcategories.create_subcategory", compact("categories"));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $request->validate();
        $validate = \Validator::make($request->all(), ["name" => "required", "slug" => "required|unique:categories", "category" => "required", "status" => "required"]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "errors" => $validate->errors()]);
        }
        $category = new SubCategory();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->category_id = $request->category;
        $category->status = $request->status;
        $category->save();
        $request->session()->flash("success", "Sub Category Created Successfully");
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
        $subcategory = SubCategory::find($id);
        if (!$subcategory) {
            return to_route("category.index")->with("error", "Sub Category Not Found");
        }
        $categories = Category::all();
        return view("admin.subcategories.update_subcategory", compact("categories", "subcategory"));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subcategory = SubCategory::find($id);
        if (!$subcategory) {
            return response()->json(["status" => false, "isNotFound" => true, "message" => "Category Not Found"]);
        }
        $validate = \Validator::make($request->all(), ["name" => "required", "slug" => "required|unique:categories,slug," . $id . ',id', "category" => "required", "status" => "required"]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "errors" => $validate->errors()]);
        }
        // $old_image= $request->file("image");
        $subcategory->name = $request->name;
        $subcategory->slug = $request->slug;
        $subcategory->category_id = $request->category;
        $subcategory->status = $request->status;
        $subcategory->save();
        $request->session()->flash("success", "Sub Category Updated Successfully");
        return response()->json(["status" => true, "message" => "Updated Successfully"]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $category = Category::find($id);
        if (!$category) {
            $request->session()->flash("success", "SubCategory Not found");

            return response()->json(["status" => true, "message" => "SubCategory not  found"]);
        }
        $category->delete();
        $request->session()->flash("success", "Sub Category Deleted Successfully");
        return response()->json(["status" => true, "message" => "Deleted Successfully"]);
    }
}
