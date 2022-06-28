@extends('layouts.admin')
@section('title')
@section('subtitle')
@section('conteudo')
    <div class="container mt-4">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        Relatórios de Vendas por Cliente
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form class="row g-3 needs-validation" action="{{ route('GeradorRelatorio') }}" method="GET">
                            <div class="col-md-4">
                                <label for="validationCustom01" class="form-label">Cliente</label>
                                <input type="text" class="form-control" name="name" id="validationCustom01"
                                    placeholder="Digite o nome do cliente" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Data Inicial</label>
                                <input type="date" class="form-control" name="dataInicial" id="validationCustom02"
                                    value="Otto" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Data Final</label>
                                <input type="date" class="form-control" name="dataFinal" id="validationCustom02"
                                    value="Otto" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="validationCustom04" class="form-label">Forma de Pagamento</label>
                                <select class="form-select" name="formPayment" id="validationCustom04">
                                    <option selected value="">...</option>
                                    @foreach ($payments as $payment)
                                        <option value="{{$payment->id}}">{{$payment->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select a valid state.
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-success" type="submit">Exportar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Relatórios de Vendas por Produto
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form class="row g-3 needs-validation" action="{{ route('GeradorRelatorioperProduct') }}"
                            method="GET">
                            <div class="col-md-3">
                                <label for="validationCustom04" class="form-label">Produtos</label>
                                <select class="form-select" name="product" id="validationCustom04" required>
                                    <option selected disabled requered>Selecione...</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select a valid state.
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Data Inicial</label>
                                <input type="date" class="form-control" name="dataInicial" id="validationCustom02"
                                    value="Otto" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Data Final</label>
                                <input type="date" class="form-control" name="dataFinal" id="validationCustom02"
                                    value="Otto" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="validationCustom04" class="form-label">Forma de Pagamento</label>
                                <select class="form-select" name="formPayment" id="validationCustom04" >
                                    <option selected value="">...</option>
                                    @foreach ($payments as $payment)
                                        <option value="{{$payment->id}}">{{$payment->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select a valid state.
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-success" type="submit">Exportar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTree" aria-expanded="false" aria-controls="collapseTwo">
                        Relatórios de Vendas
                    </button>
                </h2>
                <div id="collapseTree" class="accordion-collapse collapse" aria-labelledby="headingTree"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form class="row g-3 needs-validation" action="{{ route('GeradorRelatorioperProduct') }}"
                            method="GET">

                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Data Inicial</label>
                                <input type="date" class="form-control" name="dataInicial" id="validationCustom02"
                                    value="Otto" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Data Final</label>
                                <input type="date" class="form-control" name="dataFinal" id="validationCustom02"
                                    value="Otto" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="validationCustom04" class="form-label">Forma de Pagamento</label>
                                <select class="form-select" name="formPayment" id="validationCustom04">
                                    <option selected value="">...</option>
                                    @foreach ($payments as $payment)
                                        <option value="{{$payment->id}}">{{$payment->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select a valid state.
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-success" type="submit">Exportar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
