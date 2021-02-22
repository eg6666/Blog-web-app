<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->char('initials',3)->unique();
            $table->string('name',50);
        });

        // Shtimi i departamenteve
        $values=[
            'DII'=>'Departamenti i Inxhinierise Informatike',
            'DET'=>'Departamenti i Elektronikes dhe Telekomunikacionit',
            'DBI'=>'Departamenti i Bazave te Informatikes'
        ];
        foreach($values as $initials=>$name){
            DB::table('departments')->insert(     
                [
                'initials'=>$initials,
                'name' => $name
                ]
            );
        }
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
