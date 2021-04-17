<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersModuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_modulo', function (Blueprint $table) {
            $table->id();
            $table->integer('users_id');
            $table->integer('modulo_id');
            $table->timestamps();
        });
        DB::table('users')->insert([
            [   'id'=>1,
                'nombres' => 'Admin',
                'ape_paterno'=> '.',
                'ape_materno'=> '.',
                'usuario'=>'admin',
                'contrasenia' => 'admin'
            ]
        ]);
        for ($i=1; $i < 16; $i++) { 
            DB::table('users_modulo')->insert([
                ['users_id'=>1,'modulo_id'=>$i]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_modulo');
    }
}
