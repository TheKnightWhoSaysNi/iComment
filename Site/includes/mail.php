<?php $message = '

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Confirm your Gametop account!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

        body * {
            display: flex;
            flex-direction: column;
            font-family: Roboto, sans-serif;
            color: rgb(83, 83, 83);
        }

        section{
            width: 400px;
            height: 500px;
            margin: auto;
            text-align: center;
            background-color: #f3f3f3;
            border: solid 1px #8c7ae6;
            border-radius: 4px;
        }
    
        h1{
            font-weight: 300;
        }

        a{
            height: 50px;
            width: 100px;
            background-color: #ccc2ff;
            border: solid 1px #8c7ae6;
            border-radius: 4px;
            text-decoration: none;
            margin: 0 auto;
            transition: .1s;
        }
        a:hover{
            background-color: white;
        }
        a p{
            margin: auto;
        }
        h2{
            font-size: 15px;
        }
        h2 span{
            color: rgb(218, 67, 67);
        }
    
    </style>
</head>
<body>

    <section>

        <h1>You have successfully created your Gametop account!</h1>

        <a href="http://gametop.epizy.com?confirmEmail=' . $token . '"><p>Verify account</p></a>

        <h2>If the button doesn\'t work use this code: <span>' . $token . '</span></h2>

    </section>
    
</body>
</html>

'