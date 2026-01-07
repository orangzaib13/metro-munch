<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\OrderItem;

class FoodItemsPage extends Component
{
    public $foodItems;
    public $selectedItem = null;
    public $selectedVariation = null;
    public $selectedExtras = [];
    public $cart = [];
    public $quantity = 1;
    public $showVariationModal = false;

    public function mount()
    {
        $this->loadFoodItems();
        $this->cart = session()->get('cart', []);
    }

    public function loadFoodItems()
    {
        $this->foodItems = FoodItem::where('is_available', true)
            ->with(['variations', 'extras', 'sideOrders'])
            ->get();
    }

    public function selectItem($itemId)
    {
        $this->selectedItem = FoodItem::find($itemId);
        
        // Check if item has variations or extras
        if ($this->selectedItem->variations->count() > 0 || $this->selectedItem->extras->count() > 0) {
            // Show modal for selection
            $this->showVariationModal = true;
            $this->selectedVariation = $this->selectedItem->variations->first();
        } else {
            // Add directly to cart
            $this->addToCart();
        }
    }

    public function addToCart()
    {
        if (!$this->selectedItem) return;

        $itemKey = $this->selectedItem->id . '_' . ($this->selectedVariation?->id ?? 0);
        
        $cartItem = [
            'id' => $this->selectedItem->id,
            'name' => $this->selectedItem->name,
            'price' => $this->selectedVariation?->price ?? $this->selectedItem->price,
            'variation_id' => $this->selectedVariation?->id,
            'variation_name' => $this->selectedVariation?->name ?? null,
            'extras' => $this->selectedExtras,
            'quantity' => $this->quantity,
            'image' => $this->selectedItem->image,
        ];

        $this->cart[$itemKey] = $cartItem;
        session()->put('cart', $this->cart);

        // Reset
        $this->showVariationModal = false;
        $this->selectedItem = null;
        $this->selectedVariation = null;
        $this->selectedExtras = [];
        $this->quantity = 1;

        $this->dispatch('notify', message: 'Item added to cart!', type: 'success');
    }

    public function removeFromCart($itemKey)
    {
        unset($this->cart[$itemKey]);
        session()->put('cart', $this->cart);
        $this->dispatch('notify', message: 'Item removed from cart!', type: 'success');
    }

    public function toggleExtra($extraId)
    {
        if (in_array($extraId, $this->selectedExtras)) {
            $this->selectedExtras = array_diff($this->selectedExtras, [$extraId]);
        } else {
            $this->selectedExtras[] = $extraId;
        }
    }

    public function render()
    {
        return view('livewire.admin.food-items-page', [
            'foodItems' => $this->foodItems,
            'cart' => $this->cart,
        ]);
    }
}
