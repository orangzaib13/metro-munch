<nav class="sidebar">
    <div class="sidebar-brand d-flex align-items-center">
        <i class="fas fa-utensils" style="color: white; font-size: 24px;"></i>
        <span class="brand-text">Restaurant Manager</span>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="/branch/dashboard" wire:navigate.hover @class(['active' => request()->routeIs('branch.dashboard')])>
                <i class="fas fa-store"></i>
                <span>Branch Dashboard</span>
            </a>
        </li>

        <li>
            <a href="/branch/order-management" wire:navigate.hover @class(['active' => request()->routeIs('branch.order-management')])>
                <i class="fas fa-clipboard-list"></i>
                <span>Orders Management</span>
            </a>
        </li>

        <li>
            <a href="/branch/orders-history" wire:navigate.hover @class(['active' => request()->routeIs('order-history')])>
                <i class="fas fa-history"></i>
                <span>Order History</span>
            </a>
        </li>

        <li>
            <a href="/branch/customers" wire:navigate.live @class(['active' => request()->routeIs('branch.customers')])>
                <i class="fas fa-users"></i>
                <span>Customers</span>
            </a>
        </li>

        <li>
            <a href="/branch/food-items" wire:navigate.hover @class(['active' => request()->routeIs('branch.food-items')])>
                <i class="fas fa-bars"></i>
                <span>Food Items</span>
            </a>
        </li>

        <li>
            <a href="/branch/categories" wire:navigate.hover @class(['active' => request()->routeIs('branch.categories')])>
                <i class="fas fa-layer-group"></i>
                <span>Categories</span>
            </a>
        </li>
        <li>
            <a href="/branch/sub-categories" wire:navigate.hover @class(['active' => request()->routeIs('branch.subcategories')])>
                <i class="fas fa-shapes"></i>
                <span>Subcategories</span>
            </a>
        </li>
        <li>
            <a href="/branch/analytics" wire:navigate.hover @class(['active' => request()->routeIs('branch.analytics')])>
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
        </li>
    </ul>
</nav>
