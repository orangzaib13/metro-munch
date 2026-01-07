<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SystemSetting;
use App\Models\Branch;

class OrderOptions extends Component
{
    public $branches = [];
    public $branch_id = null;

    public $settings = [
        'delivery_enabled' => true,
        'pickup_enabled' => false,
        'delivery_message' => 'Get your food delivered',
        'pickup_message' => 'Pick up your order',
        'default_branch_id' => null,
        'default_option' => 'none',
    ];

    public function mount()
    {
        $this->branches = Branch::all();

        // Load global settings first (branch_id null)
        $setting = SystemSetting::where('setting_key', 'order_options')
            ->where('branch_id', null)
            ->first();

        if ($setting) {
            $data = json_decode($setting->setting_value, true);
            if (is_array($data)) {
                $this->settings = array_merge($this->settings, $data);
            }
            // Keep branch_id null initially
            $this->branch_id = null;
        }
    }

    public function saveSettings()
    {
        // Ensure branch_id is taken from dropdown
        SystemSetting::updateOrCreate(
            ['setting_key' => 'order_options', 'branch_id' => $this->branch_id ?: null],
            ['setting_value' => json_encode($this->settings)]
        );

        $this->dispatch('notify', message: 'Settings saved successfully.');
    }



    public function render()
    {
        return view('livewire.admin.order-options');
    }
}
