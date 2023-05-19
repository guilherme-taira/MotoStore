@extends('layouts.layout')
@section('title', $viewData['title'])
@section('conteudo')

    @if (session()->get('msg_warning'))
        <div class="alert alert-danger text-center" role="alert">
            {{ session()->get('msg_warning') }}
        </div>
    @endif

    <div class="container">
        <div class="row align-items-start">
            <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
                <h1 class="display-4">Planos Afilidrop</h1>
                <p class="lead">Conheça nossos planos e começe hoje a lucrar mais.</p>
            </div>

            <div class="container">
                <div class="row align-items-start py-2">
                    <div class="col text-center border border-3 py-2">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Free</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title">R$0 <small class="text-muted">/ Mês</small></h1>
                            <h2 class="text-danger">Limitado</h2>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li class="text-start"><i class="bi bi-x-circle-fill" style="font-size: 1rem; color: rgb(255, 0, 119);"></i> Vendas Ilimitadas</li>
                                <li class="text-start"><i class="bi bi-x-circle-fill" style="font-size: 1rem; color: rgb(255, 0, 119);"></i> Produtos para se Afiliar Ilimitado</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Integração com Mercado Livre e Shopee</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Email support</li>
                                <li class="text-start"><i class="bi bi-x-circle-fill" style="font-size: 1rem; color: rgb(255, 0, 119);"></i> Whatsapp support</li>
                                <li class="text-start"><i class="bi bi-x-circle-fill" style="font-size: 1rem; color: rgb(255, 0, 119);"></i> Comunidade Telegram <i class="bi bi-telegram"></i></li>
                                <li class="text-start"><i class="bi bi-x-circle-fill" style="font-size: 1rem; color: rgb(255, 0, 119);"></i> Comunidade Discord <i class="bi bi-discord"></i></li>
                                <li class="text-start"><i class="bi bi-x-circle-fill" style="font-size: 1rem; color: rgb(255, 0, 119);"></i> Bônus - </li>
                                <li><button type="button" class="btn btn-primary mt-2">Saiba Mais <i class="bi bi-patch-question-fill"></i></button></li>
                            </ul>
                            <button type="button" class="btn btn-lg btn-block" style="font-size: 1.5rem; background-color: rgb(255, 124, 30);">Liberar
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-rocket-takeoff-fill" viewBox="0 0 16 16">
                                    <path d="M12.17 9.53c2.307-2.592 3.278-4.684 3.641-6.218.21-.887.214-1.58.16-2.065a3.578 3.578 0 0 0-.108-.563 2.22 2.22 0 0 0-.078-.23V.453c-.073-.164-.168-.234-.352-.295a2.35 2.35 0 0 0-.16-.045 3.797 3.797 0 0 0-.57-.093c-.49-.044-1.19-.03-2.08.188-1.536.374-3.618 1.343-6.161 3.604l-2.4.238h-.006a2.552 2.552 0 0 0-1.524.734L.15 7.17a.512.512 0 0 0 .433.868l1.896-.271c.28-.04.592.013.955.132.232.076.437.16.655.248l.203.083c.196.816.66 1.58 1.275 2.195.613.614 1.376 1.08 2.191 1.277l.082.202c.089.218.173.424.249.657.118.363.172.676.132.956l-.271 1.9a.512.512 0 0 0 .867.433l2.382-2.386c.41-.41.668-.949.732-1.526l.24-2.408Zm.11-3.699c-.797.8-1.93.961-2.528.362-.598-.6-.436-1.733.361-2.532.798-.799 1.93-.96 2.528-.361.599.599.437 1.732-.36 2.531Z"/>
                                    <path d="M5.205 10.787a7.632 7.632 0 0 0 1.804 1.352c-1.118 1.007-4.929 2.028-5.054 1.903-.126-.127.737-4.189 1.839-5.18.346.69.837 1.35 1.411 1.925Z"/>
                                  </svg>
                            </button></div>
                    </div>
                    <div class="col text-center border border-3 py-2">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Comum</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title">R$297,00 <small class="text-muted">/
                                    Trimestral</small></h1>
                            <h2 class="text-danger">Ilimitado</h2>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i>Vendas Ilimitadas</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Produtos para se Afiliar Ilimitado</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Integração com Mercado Livre e Shopee</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Email support</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Whatsapp support</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Comunidade Telegram <i class="bi bi-telegram"></i></li>
                                <li class="text-start"><i class="bi bi-x-circle-fill" style="font-size: 1rem; color: rgb(255, 0, 119);"></i> Comunidade Discord <i class="bi bi-discord"></i></li>
                                <li class="text-start"><i class="bi bi-x-circle-fill" style="font-size: 1rem; color: rgb(255, 0, 119);"></i> Bônus - </li>
                                <li><button type="button" class="btn btn-primary mt-2">Saiba Mais <i class="bi bi-patch-question-fill"></i></button></li>
                            </ul>
                            <button type="button" class="btn btn-lg btn-block" style="font-size: 1.5rem; background-color: rgb(255, 124, 30);">Liberar
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-rocket-takeoff-fill" viewBox="0 0 16 16">
                                <path d="M12.17 9.53c2.307-2.592 3.278-4.684 3.641-6.218.21-.887.214-1.58.16-2.065a3.578 3.578 0 0 0-.108-.563 2.22 2.22 0 0 0-.078-.23V.453c-.073-.164-.168-.234-.352-.295a2.35 2.35 0 0 0-.16-.045 3.797 3.797 0 0 0-.57-.093c-.49-.044-1.19-.03-2.08.188-1.536.374-3.618 1.343-6.161 3.604l-2.4.238h-.006a2.552 2.552 0 0 0-1.524.734L.15 7.17a.512.512 0 0 0 .433.868l1.896-.271c.28-.04.592.013.955.132.232.076.437.16.655.248l.203.083c.196.816.66 1.58 1.275 2.195.613.614 1.376 1.08 2.191 1.277l.082.202c.089.218.173.424.249.657.118.363.172.676.132.956l-.271 1.9a.512.512 0 0 0 .867.433l2.382-2.386c.41-.41.668-.949.732-1.526l.24-2.408Zm.11-3.699c-.797.8-1.93.961-2.528.362-.598-.6-.436-1.733.361-2.532.798-.799 1.93-.96 2.528-.361.599.599.437 1.732-.36 2.531Z"/>
                                <path d="M5.205 10.787a7.632 7.632 0 0 0 1.804 1.352c-1.118 1.007-4.929 2.028-5.054 1.903-.126-.127.737-4.189 1.839-5.18.346.69.837 1.35 1.411 1.925Z"/>
                              </svg>
                            </button> </div>
                    </div>
                    <div class="col text-center border border-3 py-2">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Premium</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title">R$327,00 <small class="text-muted">/
                                    Trimestral</small></h1>
                            <h2 class="text-danger">+ 2 NFT (Permanente)</h2>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Vendas Ilimitadas</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Produtos para se Afiliar Ilimitado</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Integração com Mercado Livre e Shopee</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Email support</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Whatsapp support</li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Comunidade Telegram <i class="bi bi-telegram"></i></li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Comunidade Discord <i class="bi bi-discord"></i></li>
                                <li class="text-start"><i class="bi bi-check-circle-fill" style="font-size: 1rem; color: rgb(70, 173, 5);"></i> Bônus + 2 NFTs</li>
                                <li><button type="button" class="btn btn-primary mt-2">Saiba Mais <i class="bi bi-patch-question-fill"></i></button> </li>
                            </ul>
                            <button type="button" class="btn btn-lg btn-block" style="font-size: 1.5rem; background-color: rgb(255, 124, 30);">Liberar
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-rocket-takeoff-fill" viewBox="0 0 16 16">
                                    <path d="M12.17 9.53c2.307-2.592 3.278-4.684 3.641-6.218.21-.887.214-1.58.16-2.065a3.578 3.578 0 0 0-.108-.563 2.22 2.22 0 0 0-.078-.23V.453c-.073-.164-.168-.234-.352-.295a2.35 2.35 0 0 0-.16-.045 3.797 3.797 0 0 0-.57-.093c-.49-.044-1.19-.03-2.08.188-1.536.374-3.618 1.343-6.161 3.604l-2.4.238h-.006a2.552 2.552 0 0 0-1.524.734L.15 7.17a.512.512 0 0 0 .433.868l1.896-.271c.28-.04.592.013.955.132.232.076.437.16.655.248l.203.083c.196.816.66 1.58 1.275 2.195.613.614 1.376 1.08 2.191 1.277l.082.202c.089.218.173.424.249.657.118.363.172.676.132.956l-.271 1.9a.512.512 0 0 0 .867.433l2.382-2.386c.41-.41.668-.949.732-1.526l.24-2.408Zm.11-3.699c-.797.8-1.93.961-2.528.362-.598-.6-.436-1.733.361-2.532.798-.799 1.93-.96 2.528-.361.599.599.437 1.732-.36 2.531Z"/>
                                    <path d="M5.205 10.787a7.632 7.632 0 0 0 1.804 1.352c-1.118 1.007-4.929 2.028-5.054 1.903-.126-.127.737-4.189 1.839-5.18.346.69.837 1.35 1.411 1.925Z"/>
                                  </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="pt-4 my-md-5 pt-md-5 border-top">
            <div class="row">
                <div class="col-12 col-md">
                    <img class="mb-2" src="../../assets/brand/bootstrap-solid.svg" alt="" width="24"
                        height="24">
                    <small class="d-block mb-3 text-muted">&copy; 2017-2018</small>
                </div>
                <div class="col-6 col-md">
                    <h5>Features</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Cool stuff</a></li>
                        <li><a class="text-muted" href="#">Random feature</a></li>
                        <li><a class="text-muted" href="#">Team feature</a></li>
                        <li><a class="text-muted" href="#">Stuff for developers</a></li>
                        <li><a class="text-muted" href="#">Another one</a></li>
                        <li><a class="text-muted" href="#">Last time</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md">
                    <h5>Resources</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Resource</a></li>
                        <li><a class="text-muted" href="#">Resource name</a></li>
                        <li><a class="text-muted" href="#">Another resource</a></li>
                        <li><a class="text-muted" href="#">Final resource</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md">
                    <h5>About</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Team</a></li>
                        <li><a class="text-muted" href="#">Locations</a></li>
                        <li><a class="text-muted" href="#">Privacy</a></li>
                        <li><a class="text-muted" href="#">Terms</a></li>
                    </ul>
                </div>
            </div>
        </footer>


        <!-- Bootstrap core JavaScript
                                                  ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>

    @endsection
