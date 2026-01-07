<div>
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-chart-bar"></i> Analytics</h2>
        <p class="page-subtitle">Manage your restaurant operations efficiently.</p>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Order Statistics</h5>
            <div class="row g-2">
                <div class="col-md-3">
                    <select class="form-select" wire:model="timeFilter">
                        <option value="last-7-days">Last 7 Days</option>
                        <option value="last-30-days">Last 30 Days</option>
                        <option value="this-month">This Month</option>
                        <option value="all">All Orders</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model="branchFilter">
                        <option value="all">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-danger" wire:click="loadAnalytics">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary Cards -->
    <div class="row">
        @php
            $summaryCards = [
                ['label'=>'Total Orders','value'=>$totalOrders,'color'=>'secondary'],
                ['label'=>'Completed Orders','value'=>$completedOrders,'color'=>'success'],
                ['label'=>'Cancelled Orders','value'=>$cancelledOrders,'color'=>'danger'],
                ['label'=>'Pending Orders','value'=>$pendingOrders,'color'=>'warning'],
            ];
        @endphp

        @foreach($summaryCards as $card)
            <div class="col-md-3 mb-4">
                <div class="stat-card bg-light p-3 rounded shadow-sm text-center">
                    <div class="stat-card-label">{{ $card['label'] }}</div>
                    <div class="stat-card-value text-{{ $card['color'] }}">{{ $card['value'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Financial Cards -->
    <div class="row">
        @php
            $financialCards = [
                ['label'=>'Subtotal','value'=>$subtotal,'color'=>'warning'],
                ['label'=>'Tax','value'=>$tax,'color'=>'info'],
                ['label'=>'Discounts','value'=>$discounts,'color'=>'danger'],
                ['label'=>'Total Revenue','value'=>$totalRevenue,'color'=>'success'],
                ['label'=>'Delivery Fees','value'=>$deliveryFees,'color'=>'info'],
                ['label'=>'Avg Order Value','value'=>$avgOrderValue,'color'=>'danger'],
                ['label'=>'Avg Delivery Fee','value'=>$avgDeliveryFee,'color'=>'warning'],
            ];
        @endphp

        @foreach($financialCards as $card)
            <div class="col-md-3 mb-4">
                <div class="stat-card bg-light p-3 rounded shadow-sm text-center">
                    <div class="stat-card-label">{{ $card['label'] }}</div>
                    <div class="stat-card-value text-{{ $card['color'] }}">Rs. {{ number_format($card['value'],0) }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Order Status Distribution -->
    <div class="card">
        <div class="card-header">Order Status Distribution</div>
        <div class="card-body">
            <div class="row text-center">
                @foreach($orderStatuses as $status => $count)
                    @php
                        $percentage = $totalOrders ? round(($count / $totalOrders) * 100, 0) : 0;
                        $colors = [
                            'pending'=>'#f39c12',
                            'in-process'=>'#3498db',
                            'dispatched'=>'#9b59b6',
                            'completed'=>'#27ae60',
                            'cancelled'=>'#e74c3c',
                        ];
                        $bgColors = [
                            'pending'=>'#fff3cd',
                            'in-process'=>'#d1ecf1',
                            'dispatched'=>'#e2e3f5',
                            'completed'=>'#d4edda',
                            'cancelled'=>'#f8d7da',
                        ];
                        $textColors = [
                            'pending'=>'#856404',
                            'in-process'=>'#0c5460',
                            'dispatched'=>'#6c3483',
                            'completed'=>'#155724',
                            'cancelled'=>'#721c24',
                        ];
                    @endphp
                    <div class="col-md-2 mb-3">
                        <div style="background: {{ $bgColors[$status] }}; border: 2px solid {{ $colors[$status] }}; border-radius: 8px; padding: 20px;">
                            <h5 style="color: {{ $textColors[$status] }};">{{ ucfirst($status) }}</h5>
                            <h3 style="color: {{ $colors[$status] }};">{{ $count }}</h3>
                            <small>{{ $percentage }}% of total</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
