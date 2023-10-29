@extends('admin.layout.app')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('subcategory.index') }}" class="btn btn-primary">Back</a>
                    <!-- Use Laravel route() function to generate the URL -->
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <form id="subcategory" method="POST" name="subcategory-form">
                    @csrf <!-- Include CSRF token to protect against CSRF attacks -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary" id="submit-button">Create</button>
                <a href="{{ route('subcategory.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>

        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {

        $("#submit-button").click(function (event) {
            event.preventDefault();
            let formData = $("#subcategory").serializeArray();
// console.log( formData);
            $.ajax({
                type: "POST",
                url: "{{ route('subcategory.store') }}", // Ensure this route is defined in your application
                data: formData,
                dataType: "json",
                success: function (data) {
                    if (data && data.status) {
                        window.location.href = "{{ route('subcategory.index') }}";
                        $("#slug").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html('');
                        $("#name").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html('');
                        $("#category").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html('');

                    } else if (data && data.errors) {
                        let errors = data.errors;
                        if (errors.name) {
                            $("#name").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(errors.name);
                        } else {
                            $("#name").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html('');
                        }
                        if (errors.slug) {
                            $("#slug").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(errors.slug);
                        } else {
                            $("#slug").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html('');
                        }
                        if (errors.category) {
                            $("#category").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(errors.slug);
                        } else {
                            $("#category").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html('');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Something Went Wrong");
                }
            });
        });

        $("#name").change(function () {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.category-slug') }}",
                data: {
                    name: $("#name").val()
                },
                dataType: "json",
                success: function (data) {
                    $("#slug").val(data.slug);
                }
            });
        });
    });
</script>
@endsection
