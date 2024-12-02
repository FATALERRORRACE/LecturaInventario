<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Bibliotecas;
use App\Models\Master;

Artisan::command('queue:automate', function () {
    $date = new \DateTime();
    $insert = [];
    $jobs = DB::table('jobs')->where("queue", "custom")->get()->toArray();
    DB::table('jobs')->insert([
        'queue' => 'calcel',
        'attempts' => 0,
        'available_at' => 0,
        'created_at' => 0,
        'payload' => json_encode([
            'username' => 'username',
            'tableName' =>'tableName',
        ])
    ]);
    foreach ($jobs as $key => $job) {
        $payload = json_decode($job->payload, true);
        $data = Bibliotecas::where("Nombre", $payload["tableName"])->first();
        Schema::create($data['Tabla'], function (Blueprint $table) {
            $table->id();
            $table->string('C_Barras', 20);
            $table->string('Situacion', 60);
            $table->string('Comentario', 250);
            $table->string('Usuario', 15);
            $table->string('Estado', 1);
            $table->date('Fecha')->nullable();
        });
        $registros = Master::where("Biblioteca", $payload["tableName"])->get()->toArray();
        $sum = 1;
        foreach ($registros as $key => $val) {
            $sum++;
            $insert[] = [
                'C_Barras' => $val['C_Barras'],
                'Situacion' => $val['Proceso'],
                'Comentario' => '',
                'Usuario' => $payload["username"],
                'Estado' => '',
                'Fecha' => $date->format('Y-m-d'),
            ];
            if ($sum % 1000 == 0) {
                DB::table($data['Tabla'])
                    ->insert($insert);
                $insert = [];
            }
        }
        if ($insert)
            DB::table($data['Tabla'])->insert($insert);

        DB::table($data['Tabla'])->where('id', $job->id)->delete();
    }
})->everyFiveMinutes();
