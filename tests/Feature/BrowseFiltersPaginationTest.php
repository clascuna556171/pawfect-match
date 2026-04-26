<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowseFiltersPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_browse_json_response_contains_pagination_metadata(): void
    {
        Category::factory()->create(['name' => 'Dog']);
        Pet::factory()->count(8)->create([
            'species' => 'Dog',
            'adoption_status' => 'Available',
        ]);

        $response = $this->getJson(route('pets.index', ['species' => ['Dog']]));

        $response->assertOk();
        $response->assertJsonStructure([
            'html',
            'next_page_url',
            'total',
        ]);
        $response->assertJsonPath('total', 8);
        $this->assertNotNull($response->json('next_page_url'));
    }

    public function test_other_species_filter_includes_rabbit_and_bird_results(): void
    {
        Category::factory()->create(['name' => 'Dog']);
        Category::factory()->create(['name' => 'Rabbit']);
        Category::factory()->create(['name' => 'Bird']);

        Pet::factory()->create([
            'name' => 'Rex',
            'species' => 'Dog',
            'adoption_status' => 'Available',
        ]);

        Pet::factory()->create([
            'name' => 'Flopsy',
            'species' => 'Rabbit',
            'adoption_status' => 'Available',
        ]);

        Pet::factory()->create([
            'name' => 'Kiwi',
            'species' => 'Bird',
            'adoption_status' => 'Available',
        ]);

        $response = $this->getJson(route('pets.index', ['species' => ['Other']]));

        $response->assertOk();
        $html = $response->json('html');

        $this->assertStringContainsString('Flopsy', $html);
        $this->assertStringContainsString('Kiwi', $html);
        $this->assertStringNotContainsString('Rex', $html);
    }
}
