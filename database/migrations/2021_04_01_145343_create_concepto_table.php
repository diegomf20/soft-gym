<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepto', function (Blueprint $table) {
            $table->string('id',3)->primary();
            $table->string('descripcion',25);
            $table->enum('tipo',['I','E']);
            $table->timestamps();
        });
        DB::table('concepto')->insert([
            ['id'=>'IXC','descripcion'=>'Ingreso por Compra','tipo'=>'I'],
            ['id'=>'IXS','descripcion'=>'Ingreso por Servicio','tipo'=>'I'],
            ['id'=>'IXP','descripcion'=>'Ingreso por Producto','tipo'=>'I'],
            ['id'=>'EXC','descripcion'=>'Egreso por Compra Insumos','tipo'=>'E'],
            ['id'=>'ITR','descripcion'=>'Ingreso por Transferencia','tipo'=>'I'],
            ['id'=>'ETR','descripcion'=>'Egreso por Transferencia','tipo'=>'E']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concepto');
    }
}
