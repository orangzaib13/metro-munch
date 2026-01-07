<style>
.toast-notification {
    position: fixed;
    right: 15px;
    bottom: 10px;
    z-index: 99999999999;
    padding: 10px 14px;
    border-radius: 6px;
    color: #fff;
}

/* Success */
.toast-notification.success {
    background: #16a34a;
}

/* Error */
.toast-notification.error {
    background: #dc2626;
}
</style>
<div 
    x-data="{ show:false, message:'', type:'success' }"
    x-on:notify.window="
        message = $event.detail.message;
        type = $event.detail.type ?? 'success';
        show = true;
        setTimeout(() => show = false, 3000);
    "
    x-show="show"
    x-transition
    class="toast-notification"
    :class="type"
>
    <span x-text="message"></span>
</div>
