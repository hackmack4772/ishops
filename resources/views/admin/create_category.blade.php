@extends('admin.layout.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Category</h1>
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
                                            placeholder="Name">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            placeholder="Slug" @readonly(true)>
                                        <p></p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Status</label>
                                        {{-- <input type="text" name="status" id="status" class="form-control" placeholder="Status"> --}}

                                        <select name="status" id="status" class="form-control" placeholder="Status">
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pb-5 pt-3">
                            <button class="btn btn-primary">Create</button>
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
                type: "post",
                url: "{{ route('category.store') }}",
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
    </script>
@endsection
