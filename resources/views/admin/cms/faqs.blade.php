@extends('admin.layouts.app')
@section('title', 'CMS | FAQs')
@section('page_css')
    {{--quill--}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection
@section('section')
    <style>
        .img-upload #image-preview {
            width: 240px;
            height: 240px;
            border: 1px dashed #000;
            color: #ecf0f1;
            position: relative;
            background-repeat: no-repeat !important;
            background-position: center !important;
        }

        .img-upload #image-preview input {
            width: 120px;
            height: 40px;
            z-index: 10;
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translateX(-50%);
            margin-left: 0px;
            cursor: pointer;
            opacity: 0;
        }

        .img-upload #image-preview label {
            padding: 0px;
            z-index: 5;
            width: 130px;
            height: 40px;
            background-color: #ffffff;
            color: #143250;
            font-size: 14px;
            line-height: 40px;
            top: 60%;
            left: 50%;
            transform: translateX(-50%);
            right: 0;
            margin-left: 0px;
            bottom: 0px;
            margin-bottom: 0px;
            text-align: center;
            position: absolute;
            cursor: pointer;
            -webkit-transition: all 0.3s ease-in;
            -o-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
            /* box-shadow: 0px 0px 15px #eaeaea; */
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
{{--                    <div class="col-sm-6">--}}
{{--                        <h1>Privacy Form</h1>--}}
{{--                    </div>--}}
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">CMS</a></li>
                            <li class="breadcrumb-item active">FAQs</li>
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
                                <h3 class="card-title">Frequently Asked Questions</h3>
                            </div>
                            <form class="category-form" method="post" action="{{route('admin.cms.faqs')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row row_main">
{{--                                        <div class="col-md-12">--}}
{{--                                            <button>asd</button>--}}
{{--                                        </div>--}}
{{--                                        @foreach($data->faqs as $key => $faq)--}}
{{--                                            <div class="form-group col-md-5 mt-4" id="wrapper-question-{{$key}}">--}}
{{--                                                <label>Q{{$key + 1}}:</label>--}}
{{--                                                <textarea class="form-control" name="questions[]" cols="30" rows="2" required>{{$faq->question}}</textarea>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group col-md-5 mt-4" id="wrapper-answer-{{$key}}">--}}
{{--                                                <label>A{{$key + 1}}:</label>--}}
{{--                                                <textarea class="form-control" name="answers[]" cols="30" rows="2" required>{{$faq->answer}}</textarea>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group col-md-2 btn_remove_faq" data-id="{{$key}}">--}}
{{--                                                <button type="button">--}}
{{--                                                    x--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}

                                    </div>

                                    <div class="row">
                                        <div class="card-footer float-right">
                                            <button type="button" class="btn btn-success btn_add_faq">Add</button>
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
            let faqs = JSON.parse('{{$faqs->content}}'.replaceAll('&quot;', '"')).faqs;
            render_faqs(faqs);

            $('.btn_remove_faq').on('click', function () {
                faqs = faqs.filter((faq) => {
                    return faq.question !== $('#wrapper-question-' + $(this).data('id')).find('textarea').val();
                });

                render_faqs(faqs);
            });

            $('.btn_add_faq').on('click', function () {
                faqs.push({
                    question: '',
                    answer: '',
                });

                render_faqs(faqs);
            });

            function toSingleDigit(num) {
                return num < 9 ? (((num - 1) % 9) + 1) : num;
            }

            function render_faqs(faqs) {
                $('.row_main').html('');

                let key = 0;
                for (const faq of faqs) {
                    let string = `<div class="form-group col-md-5 mt-4" id="wrapper-question-`+toSingleDigit(key)+`">
                                        <label>Q`+toSingleDigit(key + 1)+`:</label>
                                        <textarea class="form-control" name="questions[]" cols="30" rows="2" required>`+faq.question+`</textarea>
                                    </div>
                                    <div class="form-group col-md-5 mt-4" id="wrapper-answer-`+toSingleDigit(key)+`">
                                        <label>A`+toSingleDigit(key + 1)+`:</label>
                                        <textarea class="form-control" name="answers[]" cols="30" rows="2" required>`+faq.answer+`</textarea>
                                    </div>
                                    <div class="form-group col-md-2 btn_remove_faq" data-id="`+toSingleDigit(key)+`">
                                        <button type="button">
                                            x
                                        </button>
                                    </div>`;

                    $('.row_main').append(string);
                    key += 1;
                }
            }
        });
    </script>
@endsection

