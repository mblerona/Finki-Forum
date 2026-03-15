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
        $subjects = [
            'Introduction to Programming'        => [1, ['CSE', 'CNMP', 'SEIS', 'IS', 'CCT']],
            'Discrete Mathematics'               => [1, ['CSE', 'SEIS', 'IS']],
            'Linear Algebra'                     => [1, ['CSE', 'CNMP', 'IS']],
            'English for Computer Science I'     => [1, ['CSE', 'CNMP', 'SEIS', 'IS', 'CCT']],
            'Object-Oriented Programming'        => [2, ['CSE', 'CNMP', 'SEIS', 'IS', 'CCT']],
            'Calculus'                           => [2, ['CSE', 'IS']],
            'Digital Logic'                      => [2, ['CSE', 'CNMP', 'CCT']],
            'Data Structures and Algorithms'     => [2, ['CSE', 'SEIS', 'IS']],
            'Algorithms and Complexity'          => [3, ['CSE', 'SEIS', 'IS']],
            'Computer Architecture'              => [3, ['CSE', 'CNMP', 'CCT']],
            'Databases'                          => [3, ['CSE', 'CNMP', 'SEIS', 'IS', 'CCT']],
            'Statistics and Probability'         => [3, ['CSE', 'IS']],
            'Operating Systems'                  => [4, ['CSE', 'CNMP', 'SEIS', 'CCT']],
            'Computer Networks'                  => [4, ['CSE', 'CNMP', 'CCT']],
            'Software Engineering'               => [4, ['SEIS', 'CSE']],
            'Web Programming'                    => [4, ['CSE', 'SEIS', 'CNMP']],
            'Artificial Intelligence'            => [5, ['CSE', 'IS']],
            'Mobile Application Development'     => [5, ['CNMP', 'SEIS']],
            'Network Security'                   => [5, ['CNMP', 'CCT']],
            'Human-Computer Interaction'         => [5, ['CSE', 'SEIS']],
            'Machine Learning'                   => [6, ['CSE', 'IS']],
            'Distributed Systems'                => [6, ['CSE', 'CNMP', 'CCT']],
            'Cloud Computing'                    => [6, ['CNMP', 'CCT']],
            'Compiler Design'                    => [6, ['CSE']],
            'Deep Learning'                      => [7, ['IS', 'CSE']],
            'Advanced Databases'                 => [7, ['CSE', 'SEIS']],
            'Information Security'               => [7, ['CNMP', 'CCT', 'CSE']],
            'Project Management'                 => [7, ['SEIS', 'CSE']],
            'Bachelor Thesis'                    => [8, ['CSE', 'CNMP', 'SEIS', 'IS', 'CCT']],
            'Research Methods in Computing'      => [8, ['CSE', 'IS']],
        ];

        foreach ($subjects as $name => [$semesterNumber, $majorCodes]) {
            $semester = Semester::where('name', "Semester $semesterNumber")->first();
            if (! $semester) continue;

            $subject = Subject::firstOrCreate(
                ['name' => $name, 'semester_id' => $semester->id]
            );

            $majorIds = Major::whereIn('code', $majorCodes)->pluck('id');
            $subject->majors()->syncWithoutDetaching($majorIds);
        }
    }
}
