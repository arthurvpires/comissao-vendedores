<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Seller $seller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->seller = Seller::factory()->create();
    }

    public function test_should_create_sale_successfully(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/create/sale', [
                'seller_id' => $this->seller->id,
                'value' => 1000.00,
                'date' => now()->format('Y-m-d')
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'seller_id',
                'value',
                'commission',
                'date',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('sales', [
            'seller_id' => $this->seller->id,
            'value' => 100000, // value in cents
            'commission' => 8500 // 8.5% of 1000.00
        ]);
    }

    public function test_should_list_all_sales(): void
    {
        $this->actingAs($this->user)
            ->postJson('/api/create/sale', [
                'sellerId' => $this->seller->id,
                'value' => 1000.00,
                'date' => now()->format('Y-m-d')
            ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/sales');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'seller_id',
                    'value',
                    'commission',
                    'date',
                    'created_at',
                    'updated_at',
                    'seller' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function test_should_not_create_sale_without_authentication(): void
    {
        $response = $this->postJson('/api/create/sale', [
            'sellerId' => $this->seller->id,
            'value' => 1000.00,
            'date' => now()->format('Y-m-d')
        ]);

        $response->assertStatus(401);
    }

    public function test_should_not_list_sales_without_authentication(): void
    {
        $response = $this->getJson('/api/sales');
        $response->assertStatus(401);
    }
}
