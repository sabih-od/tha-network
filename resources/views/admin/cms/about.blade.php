@extends('admin.layouts.app')
@section('title', 'CMS')
@section('section')
<style>
    .img-upload #image-preview {
        width: 100%;
        height: 400px;
        background-size: cover !important;
        border: 1px dashed #000;
        color: #ecf0f1;
        position: relative;
        background-repeat: no-repeat !important;
        background-position: center !important;
    }

    .img-upload #image-preview input {
        width: 100%;
        height: 100%;
        z-index: 10;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        cursor: pointer;
        opacity: 0;
    }

    .img-upload #image-preview label {
        z-index: 5;
        padding: 0.5em 1.125em;
        background-color: #ffffff;
        color: #143250;
        font-size: 1.125rem;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        cursor: pointer;
        -webkit-transition: all 0.3s ease-in;
        -o-transition: all 0.3s ease-in;
        transition: all 0.3s ease-in;
        box-shadow: 0px 0px 10px rgb(0 0 0 / 10%);
    }

    label {
        display: inline-block;
        margin-bottom: 0.5rem;
    }

</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>About Form</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">About Form</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About Form</h3>
                        </div>
                        <form class="category-form" method="post" action="{{route('admin.cms.aboutUs')}}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h3>Banner</h3>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Banner Image</label>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $data->banner_image }});">
                                                <label for="image-upload" class="img-label"
                                                    id="image-label">{{ __('Upload Image') }}</label>
                                                <input type="file" name="banner_image" class="img-upload"
                                                    id="image-upload">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Circle Title</label>
                                        <input type="text" class="form-control" name="banner_circle_title"
                                            value="{{$data->banner_circle_title}}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Circle Title 2</label>
                                        <input type="text" class="form-control" name="banner_circle_title_2"
                                            value="{{$data->banner_circle_title_2}}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Circle Text</label>
                                        <input type="text" class="form-control" name="banner_circle_text"
                                            value="{{$data->banner_circle_text}}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h3>Section 1</h3>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Image</label>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $data->section_1_image }});">
                                                <label for="image-upload" class="img-label"
                                                    id="image-label">{{ __('Upload Image') }}</label>
                                                <input type="file" name="section_1_image" class="img-upload"
                                                    id="image-upload">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Title</label>
                                        <input type="text" class="form-control" name="section_1_title"
                                            value="{{$data->section_1_title}}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Description</label>
                                        <textarea class="form-control" name="section_1_description" rows="1"
                                            required>{{$data->section_1_description}}</textarea>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Title 2</label>
                                        <input type="text" class="form-control" name="section_1_title_2"
                                            value="{{$data->section_1_title_2}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="name">Description 2</label>
                                        <input type="text" class="form-control" name="section_1_description_2_line_1"
                                            value="{{$data->section_1_description_2_line_1}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_1_description_2_line_2"
                                            value="{{$data->section_1_description_2_line_2}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_1_description_2_line_3"
                                            value="{{$data->section_1_description_2_line_3}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_1_description_2_line_4"
                                            value="{{$data->section_1_description_2_line_4}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_1_description_2_line_5"
                                            value="{{$data->section_1_description_2_line_5}}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h3>Section 2</h3>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{$data->section_2_image}});">
                                                <label for="image-upload" class="img-label"
                                                    id="image-label">{{ __('Upload Image') }}</label>
                                                <input type="file" name="section_2_image" class="img-upload"
                                                    id="image-upload">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Title</label>
                                        <input type="text" class="form-control" name="section_2_title"
                                            value="{{$data->section_2_title}}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Description</label>
                                        <textarea class="form-control" name="section_2_description" rows="1"
                                            required>{{$data->section_2_description}}</textarea>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Title 2</label>
                                        <input type="text" class="form-control" name="section_2_title_2"
                                            value="{{$data->section_2_title_2}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="name">Description 2</label>
                                        <input type="text" class="form-control" name="section_2_description_2_line_1"
                                            value="{{$data->section_2_description_2_line_1}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_2_description_2_line_2"
                                            value="{{$data->section_2_description_2_line_2}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_2_description_2_line_3"
                                            value="{{$data->section_2_description_2_line_3}}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h3>Section 3</h3>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{$data->section_3_image}});">
                                                <label for="image-upload" class="img-label"
                                                    id="image-label">{{ __('Upload Image') }}</label>
                                                <input type="file" name="section_3_image" class="img-upload"
                                                    id="image-upload">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="name">Title</label>
                                        <input type="text" class="form-control" name="section_3_title"
                                            value="{{$data->section_3_title}}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="name">Description</label>
                                        <textarea class="form-control" name="section_3_description" rows="1"
                                            required>{{$data->section_3_description}}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h3>Section 4</h3>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{$data->section_4_image}});">
                                                <label for="image-upload" class="img-label"
                                                    id="image-label">{{ __('Upload Image') }}</label>
                                                <input type="file" name="section_4_image" class="img-upload"
                                                    id="image-upload">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Title</label>
                                        <input type="text" class="form-control" name="section_4_title"
                                            value="{{$data->section_4_title}}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Description</label>
                                        <textarea class="form-control" name="section_4_description" rows="1"
                                            required>{{$data->section_4_description}}</textarea>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="name">Title 2</label>
                                        <input type="text" class="form-control" name="section_4_title_2"
                                            value="{{$data->section_4_title_2}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="name">Description 2</label>
                                        <input type="text" class="form-control" name="section_4_description_2_line_1"
                                            value="{{$data->section_4_description_2_line_1}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_4_description_2_line_2"
                                            value="{{$data->section_4_description_2_line_2}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_4_description_2_line_3"
                                            value="{{$data->section_4_description_2_line_3}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_4_description_2_line_4"
                                            value="{{$data->section_4_description_2_line_4}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_4_description_2_line_5"
                                            value="{{$data->section_4_description_2_line_5}}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h3>Section 5</h3>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{$data->section_5_image}});">
                                                <label for="image-upload" class="img-label"
                                                    id="image-label">{{ __('Upload Image') }}</label>
                                                <input type="file" name="section_5_image" class="img-upload"
                                                    id="image-upload">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="name">Title</label>
                                        <input type="text" class="form-control" name="section_5_title"
                                            value="{{$data->section_5_title}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="name">Description</label>
                                        <input type="text" class="form-control" name="section_5_description_line_1"
                                            value="{{$data->section_5_description_line_1}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_5_description_line_2"
                                            value="{{$data->section_5_description_line_2}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_5_description_line_3"
                                            value="{{$data->section_5_description_line_3}}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" name="section_5_description_line_4"
                                            value="{{$data->section_5_description_line_4}}" required>
                                    </div>



                                    <div class="card-footer float-right">
                                        <button type="submit" onclick="validateinputs()" class="btn btn-primary">Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('script')
<script src="{{URL::asset('admin/custom_js/custom.js')}}"></script>
<script>
    $(document).ready(function () {
        // IMAGE UPLOADING :)
        $(".img-upload").on("change", function () {
            var imgpath = $(this).parent();
            var file = $(this);
            readURL(this, imgpath);

        });

        function readURL(input, imgpath) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    imgpath.css('background', 'url(' + e.target.result + ')');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    });

</script>
@endsection
