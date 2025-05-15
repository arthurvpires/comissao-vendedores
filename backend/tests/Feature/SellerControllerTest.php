<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\Sale;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SellerControllerTest extends TestCase
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

    public function test_can_create_seller()
    {
        $sellerData = [
            'name' => 'João Silva',
            'email' => 'joao@example.com'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/create/seller', $sellerData);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'João Silva',
                'email' => 'joao@example.com'
            ]);

        $this->assertDatabaseHas('sellers', $sellerData);
    }

    public function test_cannot_create_seller_without_authentication()
    {
        $sellerData = [
            'name' => 'João Silva',
            'email' => 'joao@example.com'
        ];

        $response = $this->postJson('/api/create/seller', $sellerData);
        $response->assertStatus(401);
    }

    public function test_can_list_all_sellers()
    {
        Seller::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->getJson('/api/sellers');

        $response->assertStatus(200)
            ->assertJsonCount(4)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'email', 'created_at']
            ]);
    }

    public function test_cannot_list_sellers_without_authentication()
    {
        $response = $this->getJson('/api/sellers');
        $response->assertStatus(401);
    }

    public function test_can_list_seller_sales()
    {
        Sale::factory()->count(3)->create([
            'seller_id' => $this->seller->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/seller/{$this->seller->id}/sales");

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'seller_id', 'value', 'created_at', 'updated_at']
            ]);
    }

    public function test_returns_404_when_seller_not_found()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/seller/999/sales');

        $response->assertStatus(404);
    }
}
