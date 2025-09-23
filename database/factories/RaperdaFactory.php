<?php

namespace Database\Factories;

use App\Models\Raperda;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RaperdaFactory extends Factory
{
    protected $model = Raperda::class;

    public function definition(): array
    {
        $judul = $this->faker->sentence(4); // contoh: "Pengelolaan Air Bersih"
        $tahun = $this->faker->numberBetween(2018, 2025);
        $status = $this->faker->randomElement(['draf', 'final']);
        $slug = "{$tahun}-" . Str::slug($judul, '-');

        return [
            'judul'     => $judul,
            'tahun'     => $tahun,
            'status'    => $status,
            'ringkasan' => $this->faker->paragraph(2),
            'berkas'    => "raperdas/{$slug}.pdf", // dummy path
            'aktif'     => $this->faker->boolean(80), // 80% aktif
            'slug'      => $slug,
        ];
    }
}
