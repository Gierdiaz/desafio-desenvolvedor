<?php

namespace Tests\Feature;

use App\Models\{Upload, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name'     => 'Gierdiaz',
            'email'    => 'gierdiaz@hotmail.com',
            'password' => bcrypt('password'),
        ]);

        Sanctum::actingAs($this->user);

        Storage::fake('public');
    }
    public function test_can_upload_file(): void
    {
        // CSV de exemplo
        $csvContent = <<<CSV
        RptDt;TckrSymb;MktNm;SctyCtgyNm;ISIN;CrpnNm
        2025-06-07;ABC;Market;Category;ISIN123;CompanyName
        CSV;

        $file = UploadedFile::fake()
            ->createWithContent('InstrumentsConsolidatedFile.csv', $csvContent)
            ->mimeType('text/csv');

        $filePath = storage_path('app/public/files/' . $file->getClientOriginalName());

        file_put_contents($filePath, $csvContent);

        $response = $this->postJson('api/v1/uploads', [
            'file' => $file,
        ]);

        if (!$response->isSuccessful()) {
            dump($response->json());
        }

        $response->assertStatus(201);

        Storage::disk('public')->assertExists('files/' . $file->getClientOriginalName());

        $this->assertDatabaseHas('uploads', [
            'file_name' => $file->getClientOriginalName(),
        ]);
    }

    public function test_can_return_upload_history(): void
    {
        Upload::factory()->count(2)->create();

        $response = $this->getJson('api/v1/upload-history');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_returns_error_if_file_already_exists(): void
    {
        Upload::factory()->create(['file_name' => 'InstrumentsConsolidatedFile.csv']);

        $file = UploadedFile::fake()->createWithContent('InstrumentsConsolidatedFile.csv', 'dummy');

        $response = $this->postJson('api/v1/uploads', ['file' => $file]);

        $response->assertStatus(422)
            ->assertJsonFragment(['message' => 'File already exists']);
    }
}
