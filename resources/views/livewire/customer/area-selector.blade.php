<div>
    <!-- Modal -->
    <div class="modal fade" id="areaSelectorModal" tabindex="-1" aria-labelledby="areaSelectorLabel" aria-hidden="true" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <!-- Header -->
                <div class="modal-header border-0 bg-gradient" style="background: linear-gradient(135deg, #c41e3a 0%, #a01a2e 100%);">
                    <h5 class="modal-title text-white fw-bold" id="areaSelectorLabel">
                        <i class="fas fa-map-marker-alt me-2"></i>Select Your Area
                    </h5>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">
                    <!-- Branch Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-building me-2" style="color: #c41e3a;"></i>Select Branch
                        </label>
                        <select 
                            wire:model.live="selectedBranch" 
                            class="form-select form-select-lg rounded-2 border-2"
                            style="border-color: #e0e0e0; transition: all 0.3s ease;"
                        >
                            <option value="">-- Choose a Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Area Selection (conditional) -->
                    @if($selectedBranch)
                        <div class="mb-4 animate__animated animate__fadeIn">
                            <label class="form-label fw-bold text-dark mb-2">
                                <i class="fas fa-location-dot me-2" style="color: #c41e3a;"></i>Select Delivery Area
                            </label>
                            
                            @if(count($deliveryAreas) > 0)
                                <select 
                                    wire:model.live="selectedArea" 
                                    class="form-select form-select-lg rounded-2 border-2"
                                    style="border-color: #e0e0e0; transition: all 0.3s ease;"
                                >
                                    <option value="">-- Choose Your Area --</option>
                                    @foreach($deliveryAreas as $area)
                                        <option value="{{ $area->id }}">
                                            {{ $area->name }} 
                                            <span class="text-muted">(Rs. {{ $area->delivery_fee }})</span>
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>Delivery fee will be added at checkout
                                </small>
                            @else
                                <div class="alert alert-info rounded-2 mb-0" role="alert">
                                    <i class="fas fa-circle-info me-2"></i>No delivery areas available for this branch
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning rounded-2 mb-0" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>Please select a branch first
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 bg-light rounded-bottom-3 p-4">
                    <button 
                        type="button" 
                        class="btn btn-outline-danger rounded-2 px-4" 
                        wire:click="resetSelection"
                    >
                        <i class="fas fa-redo me-2"></i>Reset
                    </button>
                    <button 
                        type="button" 
                        class="btn btn-lg btn-danger rounded-2 px-5"
                        @disabled(!$selectedBranch || !$selectedArea)
                        wire:click="saveSelection"
                        id="confirmBtn"
                    >
                        <i class="fas fa-check me-2"></i>Confirm & Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Selected Info Display (after selection) -->
    @if(!$showModal && $selectedBranchName && $selectedAreaName)
        <div class="alert alert-danger text-danger rounded-3 mb-0 area-selector-alert" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <button 
                type="button" 
                class="btn btn-sm float-end text-danger"
                wire:click="resetSelection"
            >
                Change Branch
            </button>
        </div>
    @endif
</div>

@script
<script>
    document.addEventListener('livewire:initialized', () => {
    const modal = new bootstrap.Modal(document.getElementById('areaSelectorModal'), {
        backdrop: 'static',
        keyboard: false
    });

    @if($showModal)
        modal.show();
    @endif

    Livewire.on('close-modal', () => {
        modal.hide();
    });

    // Re-open modal when Change button clicked
    Livewire.on('open-modal', () => {
        modal.show();
    });
});

</script>
@endscript
