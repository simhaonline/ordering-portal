<?php

namespace Tests\Feature\Http\Controllers\Cms;

use App\Models\Admin;
use App\Models\DeliveryMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Cms\DeliveryMethodsController
 */
class DeliveryMethodsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function destroy_returns_an_ok_response(): void
    {
        $user = factory(Admin::class)->create();

        $delivery_method = factory(DeliveryMethod::class)->create();

        $response = $this->actingAs($user, 'admin')
            ->delete(route('cms.delivery-methods.delete', ['deliveryMethod' => $delivery_method->id]));

        $response->assertOk();
        $this->assertDeleted($delivery_method);
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $user = factory(Admin::class)->create();

        $response = $this->actingAs($user, 'admin')->get(route('cms.delivery-methods'));

        $response->assertOk();
        $response->assertViewIs('delivery-methods.index');
        $response->assertViewHas('delivery_methods');
        $response->assertViewHas('collection_messages');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {
        $user = factory(Admin::class)->create();

        $response = $this->actingAs($user, 'admin')->post(route('cms.delivery-methods.store'), [
            'code' => 'ABC',
            'title' => 'Delivery',
            'identifier' => 'Delivery',
            'price_low' => 0,
            'price_high' => 0,
        ]);

        $response->assertRedirect();
    }

    /**
     * @test
     */
    public function store_collection_message_returns_an_ok_response(): void
    {
        $user = factory(Admin::class)->create();

        $response = $this->actingAs($user, 'admin')->post(route('cms.collection-messages.store'), [
            'times' => [
                ['start' => '00:00:00',
                    'end' => '00:00:00',
                    'message' => 'Collect now',
                    'identifier' => 'Collect now', ],
            ],
        ]);

        $response->assertOk();
    }
}