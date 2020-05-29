<?php

use App\Models\Basket;
use App\Models\SavedBasket;
use Tests\Setup\BasketFactory;
use Tests\Setup\UserFactory;

beforeEach(function () {
    $this->user = (new UserFactory())->withCustomer()->create();

    actingAs($this->user);

    $this->products = (new BasketFactory())->create();
});

test('returns an ok response', function () {
    $this->get(route('saved-baskets'))->assertStatus(200);
});

test('can be created', function () {
    $this->post(route('saved-baskets.store', ['reference' => 'test-basket']));

    foreach ($this->products as $product) {
        $this->assertDatabaseHas('saved_baskets', [
            'customer_code' => $this->user->customer->code,
            'user_id' => $this->user->id,
            'product' => $product->code,
            'reference' => 'test-basket',
        ]);
    }
});

test('can be deleted', function () {
    $this->post(route('saved-baskets.store', ['reference' => 'test-basket']));

    $this->get(route('saved-baskets.destroy', ['reference' => 'test-basket']));

    $this->assertDatabaseMissing('saved_baskets', [
        'reference' => 'test-basket',
    ]);
});

test('cannot be deleted by someone else', function () {
    $this->post(route('saved-baskets.store', [
        'reference' => 'test-basket',
    ]));

    SavedBasket::where('reference', 'test-basket')->update([
        'customer_code' => 'DIFFCUSTOMER',
    ]);

    $this->get(route('saved-baskets.destroy', ['reference' => 'test-basket']))->assertStatus(302);

    $this->assertDatabaseHas('saved_baskets', [
        'reference' => 'test-basket',
    ]);
});

test('can be seen in list', function () {
    $this->post(route('saved-baskets.store', ['reference' => 'test-basket']));

    $this->get(route('saved-baskets'))->assertStatus(200)->assertSee('test-basket');
});

test('can be viewed', function () {
    $this->post(route('saved-baskets.store', ['reference' => 'test-basket']));

    $this->get(route('saved-baskets.show', ['reference' => 'test-basket']))->assertStatus(200)->assertSee('test-basket')
        ->assertSee($this->products->first()->code);
});

test('cannot be viewed by someone else', function () {
    $this->post(route('saved-baskets.store', [
        'reference' => 'test-basket',
    ]));

    SavedBasket::where('reference', 'test-basket')->update([
        'customer_code' => 'DIFFCUSTOMER',
    ]);

    $this->get(route('saved-baskets.show'))->assertStatus(404);
});

test('can be added to the basket', function () {
    $this->post(route('saved-baskets.store', ['reference' => 'test-basket']));

    $this->get(route('saved-baskets.copy', ['reference' => 'test-basket']))->assertStatus(302);

    $this->assertDatabaseHas('basket', [
        'product' => $this->products->first()->code,
    ]);
});

test('cannot be added to the basket by someone else', function () {
    $this->post(route('saved-baskets.store', [
        'reference' => 'test-basket',
    ]));

    SavedBasket::where('reference', 'test-basket')->update([
        'customer_code' => 'DIFFCUSTOMER',
    ]);

    Basket::clear();

    $this->get(route('saved-baskets.copy', ['reference' => 'test-basket']))->assertStatus(302);

    $this->assertDatabaseMissing('basket', [
        'product' => $this->products->first()->code,
    ]);
});
