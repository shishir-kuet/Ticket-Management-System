<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSubscriptionAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_see_subscription_link()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/dashboard');
        
        $response->assertOk();
        $response->assertDontSee('Subscription Plans');
    }

    public function test_admin_is_redirected_from_subscription_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/subscription/plans');
        
        $response->assertRedirect('/admin/dashboard');
        $response->assertSessionHas('info', 'Subscription management is not available for admin users.');
    }

    public function test_customer_can_access_subscription_page()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        
        $response = $this->actingAs($customer)->get('/subscription/plans');
        
        $response->assertOk();
        $response->assertSee('Choose Your Plan');
    }

    public function test_agent_can_access_subscription_page()
    {
        $agent = User::factory()->create(['role' => 'agent']);
        
        $response = $this->actingAs($agent)->get('/subscription/plans');
        
        $response->assertOk();
        $response->assertSee('Choose Your Plan');
    }
}