<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-brand d-flex align-items-center">
        <i class="fas fa-utensils" style="color: white; font-size: 24px;"></i>
        <span class="brand-text">Restaurant</span>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="/dashboard" wire:navigate.hover @class(['active' => request()->routeIs('admin.dashboard')])>
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/orders/history" wire:navigate.hover @class(['active' => request()->routeIs('order-history')])>
                <i class="fas fa-history"></i>
                <span>Order History</span>
            </a>
        </li>
        <li>
            <a href="/branches" wire:navigate.hover @class(['active' => request()->routeIs('branch-management')])>
                <i class="fas fa-building"></i>
                <span>Branch Management</span>
            </a>
        </li>
        <li>
            <a href="/customers" wire:navigate.hover @class(['active' => request()->routeIs('customers')])>
                <i class="fas fa-users"></i>
                <span>Customers</span>
            </a>
        </li>
        <li>
            <a href="/food-items" wire:navigate.hover @class(['active' => request()->routeIs('food-items')])>
                <i class="fas fa-bars"></i>
                <span>Food Items</span>
            </a>
        </li>
        <li>
            <a href="/categories" wire:navigate.hover @class(['active' => request()->routeIs('categories')])>
                <i class="fas fa-layer-group"></i>
                <span>Categories</span>
            </a>
        </li>
        <li>
            <a href="/sub-categories" wire:navigate.hover @class(['active' => request()->routeIs('subcategories')])>
                <i class="fas fa-shapes"></i>
                <span>Subcategories</span>
            </a>
        </li>
        <li>
            <a href="/discount" wire:navigate.hover @class(['active' => request()->routeIs('discount')])>
                <i class="fas fa-percentage"></i>
                <span>Discount</span>
            </a>
        </li>
        <li>
            <a href="/delivery-areas" wire:navigate.hover @class(['active' => request()->routeIs('delivery-areas')])>
                <i class="fas fa-map-marker-alt"></i>
                <span>Delivery Areas</span>
            </a>
        </li>
        <li>
            <a href="{{ route('order-options') }}" @class(['active' => request()->routeIs('order-options')])>
                <i class="fas fa-shopping-cart"></i>
                <span>Order Options</span>
            </a>
        </li>
        <li>
            <a href="{{ route('analytics') }}" @class(['active' => request()->routeIs('analytics')])>
                <i class="fas fa-chart-bar"></i>
                <span>Analytics</span>
            </a>
        </li>
        <li>
            <a href="{{ route('create-user') }}" @class(['active' => request()->routeIs('create-user')])>
                <i class="fas fa-user-plus"></i>
                <span>Create User</span>
            </a>
        </li>
    </ul>
</nav>
