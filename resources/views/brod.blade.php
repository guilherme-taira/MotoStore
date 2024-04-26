<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.css" rel="stylesheet" />
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
    <h1>Gui</h1>

    <div id="publico">
        <h5>Canal Publico</h5>
    </div>

    <script>

        var publico = document.getElementById("publico");
        Echo.channel('channel-produto')
        .listen('.App\\Events\\sendProduct', (e) => {

            publico.innerHTML += "<div class='alert alert-success'>"+ e.data +"</div>";
        });

    </script>
</body>
</html>
