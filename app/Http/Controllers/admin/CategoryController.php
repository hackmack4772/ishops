<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use File;
use Illuminate\Http\Request;
use Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd();
        $categories = Category::latest();
        if ($request->keyword) {
            $categories = Category::where("name", "like", "%" . $request->keyword . "%");
        }
        $categories = $categories->paginate(10);
        // dd($categories);
        return view("admin.categories.categories", compact("categories"));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("admin.categories.create_category");
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

        // $category = Category::create($request->only(["name", "slug", "status"]));
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();

        if (isset($request->image_id)) {
            $tempImage = TempImage::find($request->image_id);
            if ($tempImage) {
                $imageExt = explode(".", $tempImage->image);
                $ext = last($imageExt);
                $newImageName = $category->id . "." . $ext;
                $sPath = public_path('temp') . '/' . $tempImage->image;
                $dPath = public_path('uploads/categories/') . '/' . $newImageName;
                // dd($dPath);
                File::copy($sPath, $dPath);
                $img=Image::make($sPath);
                $dPath = public_path('uploads/categories/thumbs') . '/' . $newImageName;

                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);
                $category->image = $newImageName;
                $category->save();


            }
        }

        $request->session()->flash("success", "Category Created Successfully");
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
    public function edit(string $id,Request $request)
    {
        $category = Category::find($id);
        if (!$category) {
            return to_route("category.index")->with("error","Category Not Found");
        }


        return view("admin.categories.update_category", compact("category"));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(["status"=> false,"isNotFound"=>true,"message"=> "Category Not Found"]);
        }
        $validate = \Validator::make($request->all(), ["name" => "required", "slug" => "required|unique:categories,slug,".$id.',id']);
        if ($validate->fails()) {
            return response()->json(["status" => false, "errors" => $validate->errors()]);
        }

        // $category = new Category();
        $old_image= $request->file("image");
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();

        if (isset($request->image_id)) {
            $tempImage = TempImage::find($request->image_id);
            if ($tempImage) {
                $imageExt = explode(".", $tempImage->image);
                $ext = last($imageExt);
                $newImageName = $category->id . "." . $ext;
                $sPath = public_path('temp') . '/' . $tempImage->image;
                $dPath = public_path('uploads/categories/') . '/' . $newImageName;
                // dd($dPath);
                File::copy($sPath, $dPath);
                $img=Image::make($sPath);
                $dPath = public_path('uploads/categories/thumbs') . '/' . $newImageName;

                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);
                $category->image = $newImageName;
                $category->save();
                File::delete(public_path('uploads/categories/thumbs') . '/' . $old_image);
                File::delete(public_path('uploads/categories') . '/' . $old_image);


            }
        }

        $request->session()->flash("success", "Category Updated Successfully");
        return response()->json(["status" => true, "message" => "Updated Successfully"]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,Request $request)
    {
        $category = Category::find($id);
        if (!$category) {
            return to_route("category.index")->with("error","Category Not Found");
        }
        File::delete(public_path('uploads/categories/thumbs') . '/' . $category->image);
        File::delete(public_path('uploads/categories/thumbs') . '/' . $category->image);

    $category->delete();
    $request->session()->flash("success", "Category Deleted Successfully");
        return response()->json(["status" => true, "message" => "Deleted Successfully"]);
    }
    public function getSlug(Request $request)
    {
        $slug = "";

        if (isset($request->name)) {
            $slug = \Str::slug($request->name);
        }

        return response()->json([
            "status" => true,
            "slug" => $slug
        ]);
    }
}
