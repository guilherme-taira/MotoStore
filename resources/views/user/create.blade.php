@extends('layouts.layout')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container mt-4">

        @if(session()->get('message'))
            <div class="alert alert-success">
                Usuario Cadastrado Com Sucesso!
            </div>
        @endif

        <form class="row g-3 needs-validation" novalidate method="POST" action="{{route('user.store')}}">
            @csrf
            <div class="col-md-6">
                <label for="validationName" class="form-label">Nome</label>
                <input type="text" class="form-control" name="name" id="validationName" placeholder="Digite o nome">
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
            <div class="col-md-6">
                <label for="validationEmail" class="form-label">Email</label>
                <input type="text" class="form-control" name="email" id="validationEmail" placeholder="Digite o Email" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationPassword" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="validationPassword"
                    placeholder="Digite uma Senha" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary" type="submit">Cadastrar <i class="bi bi-person-plus"></i></button>
            </div>
        </form>
    </div>
@endsection

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
