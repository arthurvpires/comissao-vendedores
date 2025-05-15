<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Seller;
use App\Models\Sale;
use App\Mail\DailySalesReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_can_resend_daily_report_email()
    {
        Mail::fake();

        $seller = Seller::factory()->create([
            'email' => 'test@example.com'
        ]);

        Sale::factory()->count(3)->create([
            'seller_id' => $seller->id,
            'created_at' => now()
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/seller/resend-daily-report-email', [
            'seller_id' => $seller->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'E-mail reenviado com sucesso.']);

        Mail::assertSent(DailySalesReportMail::class, function ($mail) use ($seller) {
            return $mail->hasTo($seller->email);
        });
    }

    public function test_cannot_resend_email_with_invalid_seller_id()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/seller/resend-daily-report-email', [
            'seller_id' => 999
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['seller_id']);
    }

    public function test_cannot_resend_email_without_seller_id()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/seller/resend-daily-report-email', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['seller_id']);
    }

    public function test_cannot_access_without_authentication()
    {
        $response = $this->postJson('/api/seller/resend-daily-report-email', [
            'seller_id' => 1
        ]);

        $response->assertStatus(401);
    }
}
