@extends('admin.layout.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Update Category</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{route("category.index")}}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <div class="card">
                    <form id="category-form" method="POST" id="post-data" name="category-form">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Name" value="{{$category->name}}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            placeholder="Slug" @readonly(true)  value="{{$category->slug}}" >
                                        <p></p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Image</label>
                                        {{-- <input type="text" name="status" id="status" class="form-control" placeholder="Status"> --}}
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">
                                                <br>Drop files here or click to upload.<br><br>
                                            </div>
                                        </div>
                                        <input type="hidden" name="image_id" id="image_id" class="form-control"
                                         @readonly(true)>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Status</label>
                                        {{-- <input type="text" name="status" id="status" class="form-control" placeholder="Status"> --}}

                                        <select name="status" id="status" class="form-control" placeholder="Status">
                                            <option  {{$category->status==1?"selected":""}} value="1">Active</option>
                                            <option {{$category->status==0 ?"selected":""}} value="0">Block</option>
                                        </select>
                                    </div>

                                </div>
                                @isset($category->image)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                       <img src="{{ asset('/uploads/categories/thumbs') . '/' . $category->image}}"/>

                                    </div>
                                </div>
                                @endisset

                            </div>
                        </div>
                        <div class="pb-5 pt-3">
                            <button class="btn btn-primary">Update</button>
                            <a href="{{route("category.index")}}" class="btn btn-outline-dark ml-3">Cancel</a>
                        </div>
                    </form>
                </div>

            </div>
        </section>
    </div>
    @endsection

@section('script')
    <script>
        console.log("formData");

        $("#category-form").submit(function(event) {

            event.preventDefault()
            let formData = $(this)

            $.ajax({
                type: "put",
                url: "{{ route('category.update',$category->id) }}",
                data: formData.serializeArray(),
                dataType: "json",
                success: function(data) {
                    if (data?.status) {
                       window.location.href= "{{route('category.index')}}"
                        $("#slug").removeClass("is-invalid").siblings("p").removeClass(
                            "invalid-feedback").html('')
                        $("#name").removeClass("is-invalid").siblings("p").removeClass(
                            "invalid-feedback").html('')
                    } else {
                        if(data?.isNotFound){
                            window.location.href = "{{ route('category.index') }}"
                        }
                        let errors = data.errors;
                        if (errors["name"]) {
                            $("#name").addClass("is-invalid").siblings("p").addClass("invalid-feedback")
                                .html(errors["name"])
                        } else {
                            $("#name").removeClass("is-invalid").siblings("p").removeClass(
                                "invalid-feedback").html('')

                        }
                        if (errors["slug"]) {
                            $("#slug").addClass("is-invalid").siblings("p").addClass("invalid-feedback")
                                .html(errors["slug"])

                        } else {
                            $("#slug").removeClass("is-invalid").siblings("p").removeClass(
                                "invalid-feedback").html('')

                        }
                    }
                },
                error: function(jrXHR, exception) {
                    console.log("Something Went Wrong")
                }
            });

        });

        $("#name").change(function() {
$.ajax({
    type: "post",
    url: "{{route("admin.category-slug")}}",
    data: {"name":$("#name").val()},
    dataType: "json",
    success: function (data) {
        $("#slug").val(data.slug)
    }
});
        })


        Dropzone.autoDiscover = false;
const dropzone = $("#image").dropzone({
    init: function() {
        this.on('addedfile', function(file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
        });
    },
    url:  "{{ route('temp-images.create') }}",
    maxFiles: 1,
    paramName: 'image',
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }, success: function(file, response){
        $("#image_id").val(response.image_id);
        //console.log(response)
    }
});
    </script>
@endsection
