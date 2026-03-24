<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('PRAGMA foreign_keys = OFF;');

        \DB::table('major_subject')->truncate();
        Subject::truncate();

        \DB::statement('PRAGMA foreign_keys = ON;');

        // subject name => [semester number, [major codes]]
        $subjects = [

            // =====================
            // SEMESTER 1 — shared core across all programs
            // =====================
            'Introduction to Computer Science'    => [1, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'Structured Programming'              => [1, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'Mathematics 1'                       => [1, ['KN', 'KI', 'SEIS', 'IMB', 'PIT']],
            'Business and Management'             => [1, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'Professional Skills'                 => [1, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'Sport and Health'                    => [1, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],

            // =====================
            // SEMESTER 2 — shared core
            // =====================
            'Object-Oriented Programming'         => [2, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'Computer Architecture and Organization' => [2, ['KN', 'KI', 'SEIS', 'IMB', 'PIT']],
            'Mathematics 2'                       => [2, ['KN', 'KI', 'SEIS', 'IMB', 'PIT']],
            'Object-Oriented Analysis and Design' => [2, ['SEIS', 'PIT', 'IE']],
            'Digital Circuit Design'              => [2, ['KI', 'IMB']],
            'Discrete Mathematics'                => [2, ['KN', 'IE']],

            // =====================
            // SEMESTER 3
            // =====================
            'Algorithms and Data Structures'      => [3, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'Computer Networks and Security'      => [3, ['KN', 'KI', 'SEIS', 'IMB', 'PIT']],
            'Mathematics 3'                       => [3, ['KN', 'KI', 'SEIS', 'IMB', 'PIT']],
            'Probability and Statistics'          => [3, ['KN', 'IE']],
            'Electronics'                         => [3, ['KI']],

            // =====================
            // SEMESTER 4
            // =====================
            'Operating Systems'                   => [4, ['KN', 'KI', 'SEIS', 'IMB', 'PIT']],
            'Artificial Intelligence'             => [4, ['KN', 'SEIS', 'PIT', 'IE']],
            'Software Requirements Analysis'      => [4, ['SEIS']],
            'Network Protocols and Architecture'  => [4, ['IMB']],
            'Microprocessor Systems'              => [4, ['KI']],
            'Web Technologies'                    => [4, ['PIT', 'IE']],
            'Business Practice'                   => [4, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],

            // =====================
            // SEMESTER 5
            // =====================
            'Databases'                           => [5, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'Software Design and Architecture'    => [5, ['SEIS', 'PIT']],
            'Introduction to Data Science'        => [5, ['KN', 'SEIS', 'PIT']],
            'Advanced Programming'                => [5, ['KN', 'SEIS']],
            'Information Security'                => [5, ['IMB', 'KN']],
            'Cybersecurity'                       => [5, ['IMB']],
            'Embedded Systems'                    => [5, ['KI']],
            'Internet Technologies'               => [5, ['PIT']],
            'Educational Software Design'         => [5, ['IE']],

            // =====================
            // SEMESTER 6
            // =====================
            'Human-Computer Interaction Design'   => [6, ['SEIS', 'PIT', 'IE']],
            'Software Quality and Testing'        => [6, ['SEIS']],
            'Advanced Databases'                  => [6, ['SEIS', 'KN']],
            'System Integration'                  => [6, ['SEIS', 'PIT']],
            'Network Administration'              => [6, ['IMB']],
            'Cryptography'                        => [6, ['IMB', 'KN']],
            'Computer Vision'                     => [6, ['KN', 'KI']],
            'Machine Learning'                    => [6, ['KN']],
            'Digital Systems Design'              => [6, ['KI']],
            'Mobile Applications'                 => [6, ['PIT']],
            'Methods in Teaching Informatics'     => [6, ['IE']],

            // =====================
            // SEMESTER 7
            // =====================
            'Team Project'                        => [7, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'Distributed Systems'                 => [7, ['KN', 'SEIS', 'IMB']],
            'Cloud Computing'                     => [7, ['KN', 'SEIS', 'IMB', 'PIT']],
            'Web Programming'                     => [7, ['SEIS', 'PIT', 'IE']],
            'Network and Mobile Forensics'        => [7, ['IMB']],
            'Internet of Things'                  => [7, ['KI', 'IMB']],
            'Mobile Platforms and Programming'    => [7, ['PIT']],
            'Management Information Systems'      => [7, ['SEIS']],
            'Database Administration'             => [7, ['KN', 'SEIS']],
            'Ethical Hacking'                     => [7, ['IMB']],
            'Deep Learning'                       => [7, ['KN']],
            'Computer-Aided Manufacturing'        => [7, ['KI']],

            // =====================
            // SEMESTER 8
            // =====================
            'Diploma Thesis'                      => [8, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'ICT Projects Management'             => [8, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
            'Entrepreneurship'                    => [8, ['KN', 'KI', 'SEIS', 'IMB', 'PIT', 'IE']],
        ];

        foreach ($subjects as $name => [$semesterNumber, $majorCodes]) {
            $semester = Semester::where('name', "Semester $semesterNumber")->first();
            if (! $semester) continue;

            $subject = Subject::create([
                'name'        => $name,
                'semester_id' => $semester->id,
            ]);

            $majorIds = Major::whereIn('code', $majorCodes)->pluck('id');
            $subject->majors()->attach($majorIds);
        }
    }
}
