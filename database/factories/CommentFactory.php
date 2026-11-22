<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'user_id' => User::factory(),
            'body' => fake()->randomElement([
                'Saya melihat barang serupa di area tersebut.',
                'Apakah ada ciri khusus pada barangnya?',
                'Silakan hubungi saya jika barang ini milik Anda.',
                'Informasi lokasi sudah saya tambahkan.',
                'Semoga barangnya segera ditemukan.',
            ]),
        ];
    }
}
