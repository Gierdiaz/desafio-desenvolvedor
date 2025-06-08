<?php

namespace Tests\Feature;

use App\Models\{Content, Upload, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $upload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name'     => 'Gierdiaz',
            'email'    => 'gierdiaz@hotmail.com',
            'password' => bcrypt('password'),
        ]);

        Sanctum::actingAs($this->user);

        $this->upload = Upload::factory()->create();
    }

    public function test_can_return_all_contents()
    {
        Content::factory()->count(3)->create(['upload_id' => $this->upload->id]);

        $response = $this->getJson('api/v1/contents');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_can_filter_contents_by_TckrSymb_and_RptDt()
    {
        Content::factory()->create([
            'upload_id' => $this->upload->id,
            'TckrSymb'  => 'AAPL',
            'RptDt'     => '2023-01-01',
        ]);

        Content::factory()->create([
            'upload_id' => $this->upload->id,
            'TckrSymb'  => 'GOOG',
            'RptDt'     => '2023-01-02',
        ]);

        $response = $this->getJson('/api/v1/contents?TckrSymb=AAPL');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['TckrSymb' => 'AAPL']);

        $response = $this->getJson('/api/v1/contents?RptDt=2023-01');
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');

        $response = $this->getJson('/api/v1/contents?TckrSymb=GOOG&RptDt=2023-01-02');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['TckrSymb' => 'GOOG', 'RptDt' => '2023-01-02']);
    }

}
