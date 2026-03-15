<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        $majors = [
            ['name' => 'Computer Sciences',                          'code' => 'KN'],
            ['name' => 'Computer Engineering',                       'code' => 'KI'],
            ['name' => 'Software Engineering and Information Systems','code' => 'SEIS'],
            ['name' => 'Internet, Networks and Security',            'code' => 'IMB'],
            ['name' => 'Application of Information Technologies',    'code' => 'PIT'],
            ['name' => 'Informatics Education',                      'code' => 'IE'],
        ];

        foreach ($majors as $major) {
            Major::firstOrCreate(['code' => $major['code']], $major);
        }
    }
}
