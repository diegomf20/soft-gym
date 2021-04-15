<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulo', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 100);
            $table->string('ruta', 150);
            $table->timestamps();
        });
        DB::table('modulo')->insert([
            ['id'=>1,'titulo' => 'Nuevo Ingreso', 'ruta' => '/ingreso'],
            ['id'=>2,'titulo' => 'Listar Ingreso', 'ruta' => '/ingreso/lista'],
            ['id'=>3,'titulo' => 'Nuevo Egreso', 'ruta' => '/egreso'],
            ['id'=>4,'titulo' => 'Listar Egreso', 'ruta' => '/egreso/lista'],
            ['id'=>5,'titulo' => 'Tabla Producto', 'ruta' => '/producto'],
            ['id'=>6,'titulo' => 'Tabla Servicio', 'ruta' => '/servicio'],
            ['id'=>7,'titulo' => 'Tabla Cliente', 'ruta' => '/cliente'],
            ['id'=>8,'titulo' => 'Tabla Cuenta', 'ruta' => '/cuenta'],
            ['id'=>9,'titulo' => 'Tabla Usuario', 'ruta' => '/user'],
            ['id'=>10,'titulo' => 'Tabla Concepto', 'ruta' => '/concepto'],
            ['id'=>11,'titulo' => 'Lista Membresias', 'ruta' => '/membresia'],
            ['id'=>12,'titulo' => 'Reporte Balance', 'ruta' => '/reporte/balance'],
            ['id'=>13,'titulo' => 'Reporte Cliente Recurrentes', 'ruta' => '/reporte/membresia'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modulo');
    }
}
