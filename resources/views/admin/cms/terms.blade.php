@extends('admin.layouts.app')
@section('title', 'CMS')
@section('page_css')
    {{--quill--}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection
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
            transform: translate(-50%,-50%);
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
            transform: translate(-50%,-50%);                                    
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
                        <h1>Terms Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Terms</a></li>
                            <li class="breadcrumb-item active">Terms</li>
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
                                <h3 class="card-title">Terms Form</h3>
                            </div>
                            <form class="category-form" method="post" action="{{route('admin.cms.terms')}}" enctype="multipart/form-data">
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
                                        <div class="form-group col-md-12">
                                            <label for="name">Circle Title</label>
                                            <input type="text" class="form-control" name="banner_circle_title"
                                                   value="{{$data->banner_circle_title}}" required>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="name">Terms Content</label>
                                            <textarea id="editor" class="form-control" name="terms_content" cols="30" rows="10">
                                                {!! $data->terms_content !!}
                                            </textarea>
{{--                                            <div id="editor">--}}

{{--                                            </div>--}}
                                        </div>
                                        <div class="card-footer float-right">
                                            <button type="submit" onclick="validateinputs()"
                                                    class="btn btn-primary">Submit
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
    {{--quill--}}
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        window.onload = function () {
            CKEDITOR.replace('description', {
                {{--filebrowserUploadUrl: '{{ route('project.document-image-upload',['_token' => csrf_token() ]) }}',--}}
                {{--filebrowserUploadMethod: 'form'--}}
            });
        }
        $(document).ready(function () {
            //init quilljs
            var quill = new Quill('#editor', {
                theme: 'snow'
            });

            // get the content from the textarea
            var content = '{!! $data->terms_content !!}';

            // parse the HTML into a Quill Delta object
            var delta = quill.clipboard.convert(content);

            // set the contents of the Quill editor using the delta object
            quill.setContents(delta);

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

