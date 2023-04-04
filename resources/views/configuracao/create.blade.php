@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')


    @if ($errors->any())
        <ul class="alert alert-danger list-unstyled">
            @foreach ($errors->all() as $error)
                <li>-{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <!--- MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->
    @if (session('msg'))
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
        </div>
    @endif
    <!--- FIM MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->


    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-4 pb-5">
                <!-- Account Sidebar-->
                <div class="author-card pb-3">
                    <div class="author-card-cover"
                        style="background-image: url({{ Storage::disk('s3')->url('LogoEmbaleme/' .app(App\Models\logo::class)->getLogo()->getId() .'/' .app(App\Models\logo::class)->getLogo()->getImage()) }});">
                        <a class="btn btn-style-1 btn-white btn-sm" href="#" data-toggle="tooltip" title=""
                            data-original-title="You currently have 290 Reward points to spend"><i
                                class="fa fa-award text-md"></i>&nbsp;290 points</a>
                    </div>
                    <div class="author-card-profile">
                        <div class="author-card-avatar"><img src="https://bootdey.com/img/Content/avatar/avatar6.png"
                                alt="Daniel Adams">
                        </div>
                        <div class="author-card-details">
                            <h5 class="author-card-name text-lg">{{ $viewData['user']->name }}</h5><span
                                class="author-card-position">Desde {{ $viewData['user']->created_at }}</span>
                        </div>
                    </div>
                </div>
                <div class="wizard">
                    <nav class="list-group list-group-flush">
                        <a class="list-group-item" href="{{ route('settings') }}"><i
                                class="fe-icon-user text-muted"></i>Perfil</a>
                        <a class="list-group-item active" href="{{ route('address') }}"><i
                                class="fe-icon-map-pin text-muted"></i>Endereços</a>
                    </nav>
                </div>
            </div>
            <!-- Profile Settings-->
            <div class="col-lg-8 pb-5">

                <div class="alert-danger d-none" id="divErro">
                    <h4 class="text-center">Cep Inválido</h4>
                </div>
                <form class="row" action="{{ route('cadastrarEndereco') }}" method="post">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="account-fn">Cep</label>
                            <input class="form-control" name="cep" id="cep" type="number"
                                pattern="/^-?\d+\.?\d*$/"
                                onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                onKeyPress="if(this.value.length==8) return false;" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account-email">Logradouro: </label>
                            <input class="form-control" name="logradouro" type="text" id="logradouro">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account-email">Bairro: </label>
                            <input class="form-control" name="bairro" type="text" id="bairro">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account-email">Cidade: </label>
                            <input class="form-control" name="cidade" type="text" id="cidade">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account-email">Complemento: </label>
                            <input class="form-control"name="complemento" type="text" id="complemento">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="account-email">Número: </label>
                            <input class="form-control" name="numero" type="text" id="numero">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input class="form-control" name="userid" type="hidden" value="{{ Auth::user()->id }}"
                                id="userid">
                        </div>
                    </div>

                    <div class="col-12">
                        <hr class="mt-2 mb-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <input type="submit" value="Cadastrar" class="btn btn-style-1 btn-success">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>


<script>
    $(document).ready(function() {
        var cep = 0;

        $("input#cep").keypress(function(event) {
            return /\d/.test(String.fromCharCode(event.keyCode));
        });

        $("#cep").keyup(function() {
            if ($("#cep").val().length == 8) {
                cep = $("#cep").val();
                $.ajax({
                    url: "https://viacep.com.br/ws/" + cep + "/json/",
                    type: "GET",
                    success: function(response) {
                        if (response.erro == true) {
                            // Erro div
                            $("#divErro").removeClass('d-none');
                            $("#logradouro").prop("disabled", false);
                            $("#bairro").prop("disabled", false);
                            $("#cidade").prop("disabled", false);
                        } else {
                            if (response) {
                                // CONVERT ARRAY IN JSON FOR EACH FUNCTION
                                var json = response.cep;
                                $("#logradouro").val(response.logradouro);
                                $("#complemento").val(response.complemento);
                                $("#bairro").val(response.bairro);
                                $("#cidade").val(response.localidade);
                            }
                        }

                    },
                });
            } else {
                $("#divErro").addClass('d-none');

            }
        });


    });
</script>
