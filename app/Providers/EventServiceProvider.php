<?php

namespace App\Providers;

use App\Events\EventoAfiliado;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\logAlteracao;
use App\Listeners\gravarlog;
use App\Listeners\CadastroIntegrado;
use App\Events\EventoCadastroIntegrado;
use App\Events\notificaUserOrder;
use App\Listeners\notificaUserCadastrado;
use App\Notifications\notificaUser;
use App\Events\EventoNavegacao;
use App\Listeners\contadorAfiliado;
use App\Listeners\ContadorNavegacao;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        logAlteracao::class =>[
            gravarlog::class,
        ],
        EventoCadastroIntegrado::class => [
            CadastroIntegrado::class
        ],
        notificaUserCadastrado::class => [
            notificaUser::class,
        ],
        notificaUserOrder::class => [
            notificaUserOrder::class,
        ],
        EventoNavegacao::class => [
            ContadorNavegacao::class
        ],
        EventoAfiliado::class => [
            contadorAfiliado::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
