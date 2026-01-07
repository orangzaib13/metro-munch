<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\Branch;
use App\Models\Category;
use App\Models\GlobalDiscount;
use App\Models\FoodItemExtra;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Collection;

class CustomerHome extends Component
{
    public $foodItems = [];
    public $globalDiscount = null;
    public $categories = [];
    public $selectedCategory = null;
    public $cart = [];
    public $selectedItem = null;
    public $selectedVariation = null;
    public $selectedExtras = [];
    public $quantity = 1;
    public $showVariationModal = false;
    public $searchQuery = '';
    public $cartCount = 0;
    public $cartTotal = 0;
    public $cartOpen = false;
    public $selected_branch;
    public $selected_branch_name;

    protected $listeners = [
        'updateCart' => 'refreshCart',
        'branch-changed' => 'onBranchChanged'
    ];

    public function mount()
    {
        $this->selected_branch = session('selected_branch');

        if ($this->selected_branch) 
        {
          $this->setSelectedBranchName($this->selected_branch);
        }

        $this->loadCategories();
        $this->refreshCart();
        $this->loadFoodItems();
    }

    public function changeBranch($id)
    {
        session(['selected_branch' => $id]);

        // Notify other components
        $this->dispatch('notify', message: 'branch-changed', id: $id, type: 'success');
    }

    public function onBranchChanged($id)
    {
        $this->selected_branch = $id;
        $this->setSelectedBranchName($id);
        // reload data
        $this->loadCategories();
        $this->loadFoodItems();
        $this->refreshCart(); 
    }

    protected function setSelectedBranchName($id)
    {
        $branch = Branch::find($id);
        $this->selected_branch_name = $branch?->name ?? 'Unknown Branch';
    }

    public function loadCategories()
    {
        $branchId = $this->selected_branch;

        $categories = Category::whereHas('foodItems', function ($q) use ($branchId) {
                $q->where('is_available', true)
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId));
            })
            ->with(['foodItems' => function ($q) use ($branchId) {
                $q->where('is_available', true)
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId));
            }])
            ->get();

        $this->categories = $categories;

    }


    public function loadFoodItems()
    {
        $branchId = $this->selected_branch;

        // Load food items
        $query = FoodItem::where('is_available', true)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->with(['variations', 'extras', 'category']);

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->searchQuery}%")
                ->orWhere('description', 'like', "%{$this->searchQuery}%")
                ->orWhere('price', 'like', "%{$this->searchQuery}%");
            });
        }

        $this->foodItems = $query->get();

        // Load global discount (assuming only one active discount)
        $this->globalDiscount = GlobalDiscount::where('is_active', 1)
            ->latest('last_updated_at')
            ->first();

        // Apply global discount to each food item
       if ($this->globalDiscount && $this->globalDiscount->discount_percentage > 0) {
        $discountPercent = $this->globalDiscount->discount_percentage;

        $this->foodItems->transform(function ($item) use ($discountPercent) {
            if ($item->allow_discount) {
                $item->discounted_price = round($item->price * (1 - $discountPercent / 100), 2);
                $item->discount_text = intval($discountPercent) . '%'; // <-- cast to integer
            } else {
                $item->discounted_price = $item->price;
                $item->discount_text = null;
            }
            return $item;
        });
    } else {
        $this->foodItems->transform(function ($item) {
            $item->discounted_price = $item->price;
            $item->discount_text = null;
            return $item;
        });
    }

    }


    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId; // null for "All"
        $this->searchQuery = '';
        $this->loadFoodItems();
    }


    public function updatedSearchQuery()
    {
        $this->loadFoodItems();
    }

    public function selectItem($itemId)
    {
        $this->selectedItem = FoodItem::with(['variations','extras'])->find($itemId);

        if (!$this->selectedItem) return;

        $this->quantity = 1; // reset quantity each time
        $this->selectedVariation = $this->selectedItem->variations->first()?->id ?? null;
        $this->selectedExtras = [];
        $this->showVariationModal = $this->selectedItem->variations->count() > 0 || $this->selectedItem->extras->count() > 0;

        if (!$this->showVariationModal) {
            $this->addToCart();
        }
    }

    public function addToCart()
    {
        if (!$this->selectedItem) return;

        $variation = $this->selectedVariation 
        ? $this->selectedItem->variations->find($this->selectedVariation)
        : null;

        $extras = $this->getExtrasData();
        $extrasPrice = collect($extras)->sum('price');

        // Variation price replaces main item price
        $itemPrice = $variation?->price ?? $this->selectedItem->price;
        $basePrice = $itemPrice + $extrasPrice;


        // Use the already loaded global discount if exists
        $globalDiscount = $this->globalDiscount;
        $discountText = $this->selectedItem->allow_discount && $globalDiscount?->discount_percentage > 0
            ? intval($globalDiscount->discount_percentage) . '%'
            : null;


        // Calculate discounted price
        if ($this->selectedItem->allow_discount && $this->globalDiscount?->discount_percentage > 0) {
        $discountPercent = $this->globalDiscount->discount_percentage;
        $discountedPrice = round($basePrice * (1 - $discountPercent / 100), 2);
        } 
        else {
            $discountedPrice = $basePrice;
        }


        // Unique cart key
        $itemKey = $this->selectedItem->id . '_' . ($variation?->id ?? 0) . '_' . implode('_', $this->selectedExtras);

        $cartItem = [
            'key' => $itemKey,
            'id' => $this->selectedItem->id,
            'name' => $this->selectedItem->name,
            'image' => $this->selectedItem->image,
            'price' => $basePrice,               // total original
            'discounted_price' => $discountedPrice, // discounted total
            'discount_text' => $discountText,    // eg 10% off
            'variation_id' => $variation?->id,
            'variation_name' => $variation?->name ?? null,
            'extras' => $extras,
            'quantity' => $this->quantity,
        ];

        $cart = Session::get('cart', []);

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] += $this->quantity;
            $cart[$itemKey]['discounted_price'] = $discountedPrice; // ensure updated
            $cart[$itemKey]['discount_text'] = $discountText;       // ensure updated
        } else {
            $cart[$itemKey] = $cartItem;
        }

        Session::put('cart', $cart);

        // Refresh cart totals using discounted_price
        $this->refreshCart();

        // Reset selection
        $this->resetSelection();
        $this->dispatch('notify', message: 'Item added to cart 🎉', type: 'success');
    }

    public function removeFromCart($itemKey)
    {
        $cart = Session::get('cart', []);
        unset($cart[$itemKey]);
        Session::put('cart', $cart);
        
        $this->refreshCart();
        $this->dispatch('notify', message: 'item-removed 🎉', type: 'success');

    }

    public function updateQuantity($itemKey, $quantity)
    {
        if ($quantity < 1) {
            $this->removeFromCart($itemKey);
            return;
        }

        $cart = Session::get('cart', []);
        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] = $quantity;
            Session::put('cart', $cart);
            $this->cart = $cart;
            $this->cartCount = count($cart);

            // Use discounted_price for total
            $this->cartTotal = collect($cart)->sum(fn($item) => 
                ($item['discounted_price'] ?? $item['price']) * $item['quantity']
            );

            $this->dispatch('notify', message: 'quantity-updated 🎉', type: 'success');
        }
    }


    public function toggleExtra($extraId)
    {
        if (in_array($extraId, $this->selectedExtras)) {
            $this->selectedExtras = array_filter(
                $this->selectedExtras,
                fn($id) => $id !== $extraId
            );
        } else {
            $this->selectedExtras[] = $extraId;
        }
    }

    public function getExtrasData()
    {
        if (empty($this->selectedExtras)) {
            return [];
        }

        return FoodItemExtra::whereIn('id', $this->selectedExtras)
            ->get()
            ->map(fn($extra) => [
                'id' => $extra->id,
                'name' => $extra->name,
                'price' => $extra->price,
            ])
            ->toArray();
    }

    public function refreshCart()
    {
        $this->cart = Session::get('cart', []);
        $this->cartCount = count($this->cart);
        $this->cartTotal = collect($this->cart)->sum(fn($item) => ($item['discounted_price'] ?? $item['price']) * $item['quantity']);
        $this->loadFoodItems();
    }


    public function resetSelection()
    {
        $this->showVariationModal = false;
        $this->selectedItem = null;
        $this->selectedVariation = null;
        $this->selectedExtras = [];
        $this->quantity = 1;
    }

    public function clearCart()
    {
        Session::forget('cart');
        $this->refreshCart();
        $this->dispatch('notify', message: 'Cart cleared!', type: 'success');
    }

    public function toggleCart()
    {
        $this->cartOpen = !$this->cartOpen;
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }
    


    public function render()
    {
        return view('livewire.customer.customer-home', [
            'foodItems' => $this->foodItems,
            'categories' => $this->categories,
            'cart' => $this->cart,
            'cartCount' => $this->cartCount,
            'cartTotal' => $this->cartTotal,
        ]);
    }
}
