@extends('layouts.app')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])
@section('conteudo')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                {{ $viewData['subtitle'] }}
            </div>
            <div class="card-body">
                <h5 class="card-title">Conta para receber Pagamentos</h5>
                <form class="row g-3 needs-validation" method="POST" action="{{route('bancario.update', ['id' => $viewData['bancario']->id])}}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="col-md-3">
                        <label for="validationCustom04" class="form-label">Banco</label>
                        <select class="form-select" name="bank" id="validationCustom04" required>
                            <option selected value="001">Banco do Brasil S.A</option>
                            <option value="033">Banco Santander (Brasil) S.A.</option>
                            <option value="104">Caixa Econômica Federal</option>
                            <option value="237">Banco Bradesco S.A.</option>
                            <option value="341">Banco Itaú S.A.</option>
                            <option value="389">Banco Mercantil do Brasil S.A.</option>
                            <option value="399">HSBC Bank Brasil S.A. – Banco Múltiplo</option>
                            <option value="422">Banco Safra S.A.</option>
                            <option value="453">Banco Rural S.A.</option>
                            <option value="633">Banco Rendimento S.A.</option>
                            <option value="652">Itaú Unibanco Holding S.A.</option>
                            <option value="745">Banco Citibank S.A.</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid state.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Agência</label>
                        <input type="text" class="form-control" name="agencia" value="{{$viewData['bancario']->agencia}}" id="validationCustom02" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Conta</label>
                        <input type="text" class="form-control" name="acount" value="{{$viewData['bancario']->conta}}" id="validationCustom02" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">Nome</label>
                        <input type="text" class="form-control"  name="name" value="{{$viewData['bancario']->nome}}" id="validationCustom02" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">CPF / CNPJ</label>
                        <input type="text" class="form-control" name="cpnj" value="{{$viewData['bancario']->cpf}}" id="validationCustom02" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input type="hidden" value="{{@Auth::user()->id}}" class="form-control" id="validationCustom02" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
