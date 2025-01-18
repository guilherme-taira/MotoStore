<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePriceNotFeeDefaultInMercadoLivreHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Alterar o tipo e adicionar um valor padrão no PostgreSQL
        DB::statement('ALTER TABLE mercado_livre_history ALTER COLUMN "priceNotFee" TYPE DOUBLE PRECISION');
        DB::statement('ALTER TABLE mercado_livre_history ALTER COLUMN "priceNotFee" SET DEFAULT 0');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Reverter a alteração
       DB::statement('ALTER TABLE mercado_livre_history ALTER COLUMN "priceNotFee" DROP DEFAULT');
    }
}
