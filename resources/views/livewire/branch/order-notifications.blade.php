<div>
    <!-- Audio element -->
    <audio id="orderSound" src="{{ asset('sounds/notification.mp3') }}" preload="auto"></audio>

    <script>
        document.addEventListener('livewire:load', function () {
            const branchId = @json(auth()->user()->branch_id);
            const sound = document.getElementById('orderSound');

            window.Echo.private(`branch.${branchId}`)
                .listen('.order.placed', (e) => {
                    // Play sound
                    sound.play().catch(err => console.log('Sound play failed:', err));

                    // Emit to Livewire
                    Livewire.emit('newOrder', e);
                });
        });
    </script>
</div>
