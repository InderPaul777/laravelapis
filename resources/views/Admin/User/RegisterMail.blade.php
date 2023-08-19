<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            height: 750px;
            margin: 2px;
            padding: 2px;
            font-family: Helvetica, Arial, sans-serif;
        }

        .button-container{
            margin:40px 0;
        }

        #box{
            width: 850px;
            margin: 0 auto;
            height:100%;
        }

        #header{
            height: 200px;
            width: 100%;
            position: relative;
            display: block;
            border-bottom: 1px solid #504597;
        }

        .button{
            background-color: #d60e0e;
            border: none;
            color: white !important;
            padding: 10px 25px;
            text-align: center;
            text-decoration: none;
            margin:auto;
            font-size: 22px;
            cursor: pointer;
            border-radius: 10px;
        }

        #image {
            width: 150px;
            height: auto;
            margin-top: 16px;
        }

        #rightbar {
            width: 100%;
            height: 560px;
            padding: 0px;
        }

        .text-div{
            font-size: 18px;
            margin-bottom: 3px;
        }

        #footer {
            clear: both;
            height: 40px;
            text-align: center;
            background-color: #2d0f80;
            margin: 0px;
            padding: 0px;
            color: white;
        }

        p, pre
        {
            font-size: 18px;
            line-height: 1.4;
        }

        .heading{
            color: #504597;
            font-size: 24px;
        }

    </style>
</head>
<body>
<div id="box">
    <div id="header">
        <img id="image" src="{{ $logo }}">
    </div>
    <div class="spacing"></div>
    <div id ="rightbar">
        <h1 class="heading"></h1>
        <p >Hi {{$name}} ,</p>
        <p>Thank you for registered with us.</p>
        <p>Please login for more details. Your password is ({{$password}}).</p>
    
        <div class="text-div">Thanks,</div>
        <div class="text-div">Ditstek.</div>
    </div>
</div>
</body>
</html>