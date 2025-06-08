<?php

namespace Database\Factories;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Upload>
 */
class UploadFactory extends Factory
{
    protected $model = Upload::class;

    public function definition(): array
    {
        return [
            'file_name'      => $this->faker->unique()->word . '.csv',
            'file_path'      => 'app/public/' . $this->faker->uuid . '.csv',
            'reference_date' => now()->toDateString(),
        ];
    }
}
