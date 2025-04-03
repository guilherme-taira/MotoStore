<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conta Integrada</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('https://wallpapercave.com/wp/wp2757874.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
        }

        .logo {
            width: 250px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            color: #00ffcc;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        <h1>Conta integrada com sucesso!</h1>
    </div>


    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        };


        var code = getUrlParameter('code');
        var id = getUrlParameter('state');

        if (code && id) {
            $.ajax({
                url: "/api/v1/codeUpMaximo",
                type: "POST",
                data: {
                    code: code,
                    id: id,
                },
                success: function(response) {
                    if (response) {
                        console.log(response);
                        if (response.dados.status == 400) {
                            alert('Error ao integrar! contate o suporte.');
                        // //  showToast('errorToast'); // Mostra o toast de erro
                        } else {
                            alert('Conta integrada com sucesso!');
                            // showToast('successToast'); // Mostra o toast de sucesso
                        }
                    }
                },
                error: function(error) {
                    showToast('errorToast'); // Mostra o toast de erro
                }
            });
        } else {
            console.warn("Code ou ID está vazio. Integração não será enviada.");
        }
    </script>
</body>

</html>
