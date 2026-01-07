<div class="customer-home">
        <!-- desktop nav -->
     <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-0 d-none d-md-block">
        <div class="container">
            <a class="navbar-brand py-0" href="/" wire:navigate.hover>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Search Bar -->
                <div class="flex-grow-1 mx-3">
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control border-0 bg-light" 
                            placeholder="Search food items..."
                            wire:model.live="searchQuery"
                        >
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-secondary"></i>
                        </span>
                    </div>
                </div>

                <!-- Cart Button -->
                <div class="d-flex align-items-center gap-2">
                    <button 
                        class="btn btn-outline-danger position-relative"
                        data-bs-toggle="offcanvas" 
                        data-bs-target="#cartOffcanvas"
                    >
                        <i class="fas fa-shopping-cart"></i> Cart
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <!-- mobile nav -->
     <nav class="navbar bg-white sticky-top shadow-sm py-0 d-md-none mobile-nav">
        <div class="container">

            <div class="row w-100 align-items-center">
                <div class="col-3">
                    <a class="py-0" href="/" wire:navigate.hover>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
            </a>
                </div>
                <!-- Search Bar -->
                <div class="col-7">
                    <div class="flex-grow-1">
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control border-0 bg-light" 
                            placeholder="Search food items..."
                            wire:model.live="searchQuery"
                        >
                    </div>
                </div>
                </div>

                <div class="col-2">
                    <!-- Cart Button -->
                <div class="d-flex align-items-center">
                    <button 
                        class="btn position-relative"
                        data-bs-toggle="offcanvas" 
                        data-bs-target="#cartOffcanvas"
                    >
                        <i class="fas fa-shopping-cart"></i>
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </button>
                </div>
                </div>
            </div>

            </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" style="background-image: url('{{ asset('images/banner-image.png') }}')">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-6">
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Metro Munch</li>
                        <li class="breadcrumb-item" aria-current="page">Branch</li>
                        <li class="breadcrumb-item text-warning text-capitalize" aria-current="page">{{ $selected_branch_name ?? '' }}</li>
                    </ol>
                    </nav>
                    <h1 class="fw-bold mb-3">Order Your Favorite Meals</h1>
                    <p class="mb-4">Browse our delicious menu and discover amazing flavors. Fast delivery to your door!</p>
                    <button class="btn btn-lg btn-warning px-lg-4" onclick="document.querySelector('.menu-section').scrollIntoView({behavior: 'smooth'})">
                        <span>Explore Menu</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section class="menu-section py-5">
        <div class="container">
            @if(!$categories->isEmpty() && $categories->count() > 0)
            <div class="mb-5" x-data="{ 
                scrollNext() { $refs.container.scrollBy({ left: 300, behavior: 'smooth' }) },
                scrollPrev() { $refs.container.scrollBy({ left: -300, behavior: 'smooth' }) }
            }">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="sub-heading ps-lg-2 mb-0">Categories</h3>
                    @if($categories->count() > 7)
                    <div class="d-flex gap-2 pe-lg-2">
                        <button @click="scrollPrev" class="btn btn-sm btn-outline-danger"><i class="fas fa-chevron-left"></i></button>
                        <button @click="scrollNext" class="btn btn-sm btn-outline-danger"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    @endif
                </div>

                <div 
                    x-ref="container"
                    class="category-scroll-container d-flex gap-3 overflow-x-auto pb-3"
                    style="scrollbar-width: none; -ms-overflow-style: none; scroll-behavior: smooth;"
                >
                    <div 
                        wire:key="cat-all"
                        wire:click="filterByCategory(null)"
                        class="category-slide-alpine {{ !$selectedCategory ? 'active-category' : '' }}"
                        style="background-image: url('{{ asset('storage/categories/default-category.jpg') }}');"
                    >
                        <span>All Items</span>
                    </div>

                    @foreach($categories as $category)
                        <div 
                            wire:key="cat-{{ $category->id }}"
                            wire:click="filterByCategory({{ $category->id }})"
                            class="category-slide-alpine {{ $selectedCategory == $category->id ? 'active-category' : '' }}"
                            style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('storage/' . ($category->image ?? 'default-category.jpg')) }}')"
                        >
                            <span>{{ $category->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif


            @if($categories->isEmpty())
                <div class="alert alert-info w-100 mt-2">No categories available</div>
            @endif

            <!-- Food Items Grid -->

            @if($foodItems->count() > 0)
                <div class="row g-4">
                    <h3 class="sub-heading ps-lg-2">
                        Menu 
                        @if($selectedCategory)
                        @php
                            $selectedCat = $categories->firstWhere('id', $selectedCategory);
                        @endphp
                        <span class="text-danger text-capitalize">/ {{ $selectedCat ? $selectedCat->name : '' }}</span>
                    @endif
                    </h3>
                    @foreach($foodItems as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="food-card card h-100 shadow-sm overflow-hidden p-lg-3 " style="border: none; border-radius: 20px; transition: all 0.3s ease;">
                                <!-- Discount Badge -->
                                 @if($item->discount_text > 0)
                                    <div class="discount-badge bg-danger">
                                         {{$item->discount_text}} Off
                                    </div>
                                @endif
                                <!-- Image -->
                                <div class="position-relative" style="height: 250px; overflow: hidden;">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                             class="card-img-top h-100 rounded-4" 
                                             style="object-fit: cover; transition: transform 0.3s ease;">
                                    @else
                                        <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image fa-3x text-secondary"></i>
                                        </div>
                                    @endif
                                    
                                </div>

                                <!-- Content -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-dark mb-1">{{ $item->name }}</h5>
                                    
                                    @if($item->description)
                                        <p class="card-text text-muted small flex-grow-1">
                                            {{ Str::limit($item->description, 60) }}
                                        </p>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div>
                                            <span class="h5 text-danger fw-bold mb-0">Rs. {{ number_format($item->price) }}</span>
                                            @if($item->variations->count() > 0)
                                                <small class="text-muted d-block">Multiple sizes</small>
                                            @endif
                                        </div>
                                        <button 
                                            class="btn btn-danger rounded-pill px-3"
                                            wire:click="selectItem({{ $item->id }})">
                                            <span>
                                                <i class="fas fa-plus"></i> Add
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-danger text-center py-5">
                    <i class="fas fa-search fa-3x mb-3 d-block text-muted"></i>
                    <h5>No items found</h5>
                    <p class="text-muted">Try searching for something else</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Variation Modal -->
    @if($showVariationModal && $selectedItem)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="border-radius: 20px; border: none;">
                    <!-- Close Button -->
                    <button type="button" class="btn-close position-absolute" style="top: 15px; right: 15px; z-index: 10;" 
                            wire:click="resetSelection()"></button>

                    <!-- Image -->
                    @if($selectedItem->image)
                        <img src="{{ asset('storage/' . $selectedItem->image) }}" 
                             class="img-fluid" 
                             style="height: 300px; object-fit: cover; border-radius: 20px 20px 0 0;">
                    @endif

                    <div class="modal-body p-4">
                        <!-- Title & Price -->
                        <h3 class="fw-bold text-dark mb-2">{{ $selectedItem->name }}</h3>
                        @if($selectedItem->description)
                            <p class="text-muted mb-4">{{ $selectedItem->description }}</p>
                        @endif

                        <!-- Variations -->
                        @if($selectedItem->variations->count() > 0)
                            <div class="mb-3">
                                <label class="fs-small mb-1 mt-2 d-block">Choose Variation</label>
                                <div class="d-flex flex-column gap-2">
                                    @foreach($selectedItem->variations as $variation)
                                        <label class="p-3 border rounded-3" style="cursor: pointer; 
                                                   border: 2px solid {{ $selectedVariation === $variation->id ? '#c41e3a' : '#e0e0e0' }};
                                                   background: {{ $selectedVariation === $variation->id ? '#fff5f5' : 'white' }};
                                                   transition: all 0.3s ease;">
                                            <div class="form-check mb-0">
                                                <input type="radio" class="form-check-input" 
                                                       wire:model.change="selectedVariation" 
                                                       value="{{ $variation->id }}">
                                                <span class="fw-bold ms-2">{{ $variation->name }}</span>
                                                <span class="float-end text-danger fw-bold">Rs. {{ number_format($variation->price) }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Extras -->
                        @if($selectedItem->extras->count() > 0)
                            <div class="mb-3">
                                <label class="fs-small mb-1 mt-2 d-block">Add Extras (Optional)</label>
                                <div class="d-flex flex-column gap-2">
                                    @foreach($selectedItem->extras as $extra)
                                        <label class="p-3 border rounded-3" style="cursor: pointer; 
                                                   border: 2px solid {{ in_array($extra->id, $selectedExtras) ? '#c41e3a' : '#e0e0e0' }};
                                                   background: {{ in_array($extra->id, $selectedExtras) ? '#fff5f5' : 'white' }};
                                                   transition: all 0.3s ease;">
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input" 
                                                       wire:click="toggleExtra({{ $extra->id }})"
                                                       {{ in_array($extra->id, $selectedExtras) ? 'checked' : '' }}>
                                                <span class="fw-bold ms-2">{{ $extra->name }}</span>
                                                @if($extra->description)
                                                    <small class="text-muted ms-2">{{ $extra->description }}</small>
                                                @endif
                                                <span class="float-end text-danger fw-bold">+Rs. {{ number_format($extra->price) }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="row bg-light d-flex align-item-center justify-content-between p-3 rounded mx-2 mb-2">
                        <!-- Total Price -->
                        <div class="col-md-9">
                        <h4 class="text-danger fw-bold mb-0">
                            Rs. {{ number_format(
                                (
                                    ($selectedVariation 
                                        ? $selectedItem->variations->find($selectedVariation)?->price 
                                        : $selectedItem->price
                                    )
                                    + collect($selectedExtras)->sum(
                                        fn($id) => $selectedItem->extras->find($id)?->price ?? 0
                                    )
                                ) * $quantity
                            ) }}
                        </h4>
                     </div>
                       <!-- Quantity -->
                        <div class="col-md-3 align-items-end ">
                           <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-outline-danger btn-sm" wire:click="decrementQuantity">
                                <i class="fas fa-minus"></i>
                            </button>

                            <span class="fs-small">
                                {{ $quantity }}
                            </span>

                            <button class="btn btn-outline-danger btn-sm" wire:click="incrementQuantity">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        </div>

                    </div>


                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-danger btn-lg" wire:click="addToCart()">
                            <span>
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </span>
                            </button>
                            <button class="btn btn-outline-secondary" wire:click="resetSelection()">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Cart Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" style="width: 420px;" wire:ignore.self>
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold">
                <i class="fas fa-shopping-cart"></i> Your Cart
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body p-0 d-flex flex-column">
            @if($cartCount > 0)
                <div class="flex-grow-1 overflow-y-auto p-3">
                    @foreach($cart as $itemKey => $item)
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex gap-3 mb-2">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" 
                                             alt="{{ $item['name'] }}" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    @endif
                                    
                                    <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">{{ $item['name'] }}</h6>
                                    @if($item['variation_name'])
                                        <small class="text-muted d-block">{{ $item['variation_name'] }}</small>
                                    @endif
                                    @if($item['discounted_price'] < $item['price'])
                                        <div>
                                            <span class="text-muted"><s>Rs. {{ number_format($item['price']) }}</s></span>
                                            <span class="text-danger fw-bold">Rs. {{ number_format($item['discounted_price']) }} (- {{ $item['discount_text'] }})</span>
                                        </div>
                                    @else
                                        <span class="text-danger fw-bold">Rs. {{ number_format($item['price']) }}</span>
                                    @endif
                                </div>


                                    <button class="btn btn-sm text-danger" 
                                            wire:click="removeFromCart('{{ $itemKey }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-sm btn-outline-secondary" 
                                            wire:click="updateQuantity('{{ $itemKey }}', {{ $item['quantity'] - 1 }})"
                                            type="button">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="fw-bold" style="min-width: 30px; text-align: center;">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            wire:click="updateQuantity('{{ $itemKey }}', {{ $item['quantity'] + 1 }})"
                                            type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    </div>
                                    <div>
                                     @if($item['discounted_price'] < $item['price'])
                                            <span class="text-danger fw-bold">Rs. {{ number_format($item['discounted_price']) }}</span>
                                    @else
                                        <span class="text-danger fw-bold">Rs. {{ number_format($item['price'] * $item['quantity']) }}</span>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Footer -->
                <div class="border-top p-3">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Subtotal:</span>
                        <span class="fw-bold">Rs. {{ number_format($cartTotal) }}</span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="/check-out" wire:navigate.hover class="btn btn-danger btn-lg">
                            <span>
                                <i class="fas fa-credit-card"></i> Proceed to Checkout
                            </span>
                        </a>
                        <button class="btn btn-outline-danger" 
                                wire:click="clearCart()"
                                wire:confirm="Clear your entire cart?">
                            <i class="fas fa-trash"></i> Clear Cart
                        </button>
                    </div>
                </div>
            @else
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-center">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">Your cart is empty</h5>
                        <p class="text-muted small">Add items from the menu to get started</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Notification Toast -->
    <x-notification-toast />
</div>


