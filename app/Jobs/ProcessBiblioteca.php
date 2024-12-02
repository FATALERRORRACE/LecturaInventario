<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Bibliotecas;
use App\Models\Master;
 
class ProcessBiblioteca implements ShouldQueue
{
    use Queueable;

    public $tableName;
    public $user;
    /**
     * Create a new job instance.
     */
    public function __construct($tableName, $user)
    {
        $this->tableName = $tableName;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $date = new \DateTime();
        $insert = [];
        $this->user;
        $this->tableName;
        $data = Bibliotecas::where("Nombre", $this->tableName)->first();
        Schema::create($data['Tabla'], function (Blueprint $table) {
            $table->id();
            $table->string('C_Barras', 20);
            $table->string('Situacion', 60);
            $table->string('Comentario', 250);
            $table->string('Usuario', 15);
            $table->string('Estado', 1);
            $table->date('Fecha')->nullable();
        });
        $registros = Master::where("Biblioteca", $this->tableName)->get()->toArray();
        $sum = 1;
        foreach ($registros as $key => $val) {
            $sum++;
            $insert[] = [
                'C_Barras' => $val['C_Barras'],
                'Situacion' => $val['Proceso'],
                'Comentario' => '',
                'Usuario' => $this->user,
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

        $date = new \DateTime();
        $insert = [];
        $jobs = DB::table('jobs')->get()->toArray();
        var_dump($jobs);die;
        $data = Bibliotecas::where("Nombre", $this->tableName)->first();
        Schema::create($data['Tabla'], function (Blueprint $table) {
            $table->id();
            $table->string('C_Barras', 20);
            $table->string('Situacion', 60);
            $table->string('Comentario', 250);
            $table->string('Usuario', 15);
            $table->string('Estado', 1);
            $table->date('Fecha')->nullable();
        });
        $registros = Master::where("Biblioteca", $this->tableName)->get()->toArray();
        $sum = 1;
        foreach ($registros as $key => $val) {
            $sum++;
            $insert[] = [
                'C_Barras' => $val['C_Barras'],
                'Situacion' => $val['Proceso'],
                'Comentario' => '',
                'Usuario' => $this->user,
                'Estado' => '',
                'Fecha' => $date->format('Y-m-d'),
            ];
            if($sum % 1000 == 0){
                DB::table($data['Tabla'])
                ->insert($insert);
                $insert = [];
            }
        }
        if($insert)
            DB::table($data['Tabla'])->insert($insert);
    }
}
