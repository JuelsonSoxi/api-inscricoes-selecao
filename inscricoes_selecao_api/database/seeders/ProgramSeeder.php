<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Programa de Estágio em Tecnologia',
                'description' => 'Programa voltado para estudantes de tecnologia que desejam adquirir experiência prática em desenvolvimento de software.',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'active',
                'max_candidates' => 50,
            ],
            [
                'name' => 'Bootcamp de Desenvolvimento Web',
                'description' => 'Bootcamp intensivo de 12 semanas focado em desenvolvimento web full-stack.',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(45),
                'status' => 'active',
                'max_candidates' => 30,
            ],
            [
                'name' => 'Programa de Mentoria em Startups',
                'description' => 'Programa de mentoria para empreendedores que estão iniciando suas startups.',
                'start_date' => Carbon::now()->subDays(50),
                'end_date' => Carbon::now()->subDays(20),
                'status' => 'inactive',
                'max_candidates' => 20,
            ],
            [
                'name' => 'Curso de Análise de Dados',
                'description' => 'Curso completo de análise de dados com Python e ferramentas de BI.',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(60),
                'status' => 'active',
                'max_candidates' => 40,
            ]
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
