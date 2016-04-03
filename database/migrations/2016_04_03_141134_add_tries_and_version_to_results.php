<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddTriesAndVersionToResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('results', function(Blueprint $table)
        {
            $table->integer('tries')->default(1);
            $table->string('api_version')->default('v1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('results', function(Blueprint $table)
        {

            if(Schema::hasColumns('results', ['tries', 'api_version']))
            {
                $table->dropColumn('tries');
                $table->dropColumn('api_version');
            }

        });
    }
}
