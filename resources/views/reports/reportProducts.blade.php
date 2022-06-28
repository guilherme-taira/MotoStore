<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>RELATÓRIO VENDA DE PRODUTOS</title>
</head>
<body>

        <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">Produto</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Data da Venda</th>
                <th scope="col">Total da Venda</th>
                <th scope="col">Forma de Pagamento</th>
                <th scope="col">Cliente</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($data as $dados)
                <tr>
                    <td class="text-center">{{$dados->name}}</td>
                    <td class="text-center">{{$dados->quantidade}}</td>
                    <td class="text-center">{{$dados->datavenda}}</td>
                    <td class="text-center">R$:{{number_format($dados->totalVenda, 2, ',', '.')}}</td>
                    <td class="text-center">{{$dados->pagamento}}</td>
                    <td class="text-center">{{$dados->cliente}}</td>
                </tr>
                @endforeach
            </tbody>
          </table>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
