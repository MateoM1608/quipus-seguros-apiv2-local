<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Table name
     */
    public $table_name = 'modules';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description')->unique();
            $table->string('name');
            $table->integer('parent')->nullable();
            $table->boolean('title')->default(false);
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('class')->nullable();
            $table->json('badge')->nullable();
            $table->json('wrapper')->nullable();
            $table->string('variant')->nullable();
            $table->json('attributes')->nullable();
            $table->boolean('divider')->nullable();
            $table->integer('method')->nullable();
            $table->string('order')->default(0);
            $table->boolean('show')->default(0);
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->index('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists($this->table_name);
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
