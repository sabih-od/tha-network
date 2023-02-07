@extends('layouts.app')

@section('content')
    <span id='fadfasf'></span>
    <script src='https://www.paypalobjects.com/js/external/api.js'></script>
    <script>
        paypal.use(['login'], function (login) {
            login.render({
                "appid": "AcKwbyi3-LtcW9orYwnWecAHjTaU6SDpJ6JiVW6FIP3lO-9yY-DjWoPNoo6vTbfEW2Xitkmkiiz5O1le",
                "authend": "sandbox",
                "scopes": "email",
                "containerid": "fadfasf",
                "responseType": "code id_Token",
                "locale": "en-us",
                "buttonType": "CWP",
                "buttonShape": "pill",
                "buttonSize": "lg",
                "fullPage": "true",
                "returnurl": "https://testv23.demowebsitelinks.com/tha-network/public"
            });
        });
    </script>
@endsection
