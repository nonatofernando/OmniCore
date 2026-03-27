<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('sku');
            $table->integer('estoque_minimo')->default(0)->after('descricao');
            $table->integer('estoque_maximo')->nullable()->after('estoque_minimo');
        });


        Schema::table('categorias', function (Blueprint $table) {
            $table->foreignId('usuario_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->foreignId('usuario_id')->nullable(false)->change();
        });

        Schema::table('produtos', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('id');
            $table->dropColumn(['estoque_minimo', 'estoque_maximo']);
        });
    }
};
