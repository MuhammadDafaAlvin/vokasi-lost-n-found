<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Enums\Type;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()->count(8)->create();

        $this->createReports($users);
    }

    /**
     * Create reports with comments from sample users.
     *
     * @param  Collection<int, User>  $users
     */
    private function createReports(Collection $users): void
    {
        $samples = [
            [
                'type' => Type::LOST->value,
                'title' => 'Dompet kulit cokelat',
                'category' => 'Aksesori',
                'location' => 'Kantin Fakultas Vokasi',
                'status' => Status::ACTIVE->value,
            ],
            [
                'type' => Type::FOUND->value,
                'title' => 'Kartu mahasiswa',
                'category' => 'Dokumen',
                'location' => 'Perpustakaan',
                'status' => Status::ACTIVE->value,
            ],
            [
                'type' => Type::LOST->value,
                'title' => 'Headphone hitam',
                'category' => 'Elektronik',
                'location' => 'Laboratorium komputer',
                'status' => Status::ACTIVE->value,
            ],
            [
                'type' => Type::FOUND->value,
                'title' => 'Botol minum biru',
                'category' => 'Lainnya',
                'location' => 'Gedung Fakultas Vokasi',
                'status' => Status::RESOLVED->value,
            ],
        ];

        foreach ($samples as $sample) {
            $report = Report::factory()
                ->for($users->random())
                ->create($sample);

            $this->createComments($report, $users);
        }

        Report::factory()
            ->count(8)
            ->create()
            ->each(function (Report $report) use ($users): void {
                $this->createComments($report, $users);
            });
    }

    /**
     * Create comments belonging to the specified report.
     *
     * @param  Collection<int, User>  $users
     */
    private function createComments(Report $report, Collection $users): void
    {
        $commentCount = fake()->numberBetween(1, 4);

        for ($index = 0; $index < $commentCount; $index++) {
            Comment::factory()
                ->for($report)
                ->for($users->random())
                ->create();
        }
    }
}
