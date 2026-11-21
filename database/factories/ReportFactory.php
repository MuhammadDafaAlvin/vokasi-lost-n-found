<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Enums\Type;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(Type::cases())->value,
            'title' => fake()->randomElement([
                'Dompet kulit',
                'Kartu mahasiswa',
                'Laptop',
                'Payung lipat',
                'Botol minum',
                'Kunci kendaraan',
                'Headphone',
                'Buku catatan',
            ]),
            'category' => fake()->randomElement(config('report.categories')),
            'description' => fake()->paragraph(),
            'color' => fake()->safeColorName(),
            'location' => fake()->randomElement([
                'Gedung Fakultas Vokasi',
                'Perpustakaan',
                'Laboratorium',
                'Area parkir',
                'Kantin',
                'Ruang kelas',
            ]),
            'event_date' => fake()
                ->dateTimeBetween('-60 days', 'now')
                ->format('Y-m-d'),
            'image_path' => null,
            'status' => Status::ACTIVE->value,
            'is_hidden' => false,
        ];
    }

    /**
     * Set the report type to lost.
     */
    public function lost(): static
    {
        return $this->state([
            'type' => Type::LOST->value,
        ]);
    }

    /**
     * Set the report type to found.
     */
    public function found(): static
    {
        return $this->state([
            'type' => Type::FOUND->value,
        ]);
    }

    /**
     * Set the report status to resolved.
     */
    public function resolved(): static
    {
        return $this->state([
            'status' => Status::RESOLVED->value,
        ]);
    }

    /**
     * Hide the report from public listings.
     */
    public function hidden(): static
    {
        return $this->state([
            'is_hidden' => true,
        ]);
    }
}
