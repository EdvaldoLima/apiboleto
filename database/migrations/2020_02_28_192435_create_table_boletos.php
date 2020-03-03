<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBoletos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->bigIncrements('idBoleto');
            $table->integer('convenio');
            $table->integer('carteira');
            $table->integer('variacaoCarteira');
            $table->date('dtEmissao');
            $table->date('dtVencimento');
            $table->decimal('valorBoleto', 8, 2);
            $table->integer('tipoDesconto');
            $table->string('nomeCliente', 100);
            $table->string('cpfCnpj', 50);

            $table->string('rua', 50);
            $table->string('cidade', 50);
            $table->string('estado', 50);
            $table->string('cep', 50);

            $table->longText('demonstrativo');
            $table->string('arquivo', 300);
            $table->string('nossoNumero', 100);
            $table->string('linhaDigitavel', 100);
            $table->string('codigoBarrasNumerico', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boletos');
    }
}
