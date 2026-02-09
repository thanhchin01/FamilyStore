{{-- Toast partial using Toastify (CDN) - include this before closing body --}}
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            Toastify({
                text: {!! json_encode(session('success')) !!},
                duration: 4000,
                gravity: 'top',
                position: 'right',
                close: true,
                style: { background: 'linear-gradient(to right, #00b09b, #96c93d)', color: '#fff' }
            }).showToast();
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            Toastify({
                text: {!! json_encode(session('error')) !!},
                duration: 5000,
                gravity: 'top',
                position: 'right',
                close: true,
                style: { background: 'linear-gradient(to right, #e52d27, #b31217)', color: '#fff' }
            }).showToast();
        });
    </script>
@endif

@if(session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            Toastify({
                text: {!! json_encode(session('info')) !!},
                duration: 4000,
                gravity: 'top',
                position: 'right',
                close: true,
                style: { background: 'linear-gradient(to right, #2193b0, #6dd5ed)', color: '#fff' }
            }).showToast();
        });
    </script>
@endif


{{-- <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

@if(session()->has('success') || session()->has('error') || session()->has('info'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    let toastData = @json([
        'success' => session('success'),
        'error'   => session('error'),
        'info'    => session('info'),
    ]);

    let config = {
        success: {
            background: 'linear-gradient(to right, #00b09b, #96c93d)',
            duration: 4000
        },
        error: {
            background: 'linear-gradient(to right, #e52d27, #b31217)',
            duration: 5000
        },
        info: {
            background: 'linear-gradient(to right, #2193b0, #6dd5ed)',
            duration: 4000
        }
    };

    Object.keys(toastData).forEach(type => {
        if (toastData[type]) {
            Toastify({
                text: toastData[type],
                duration: config[type].duration,
                gravity: 'top',
                position: 'right',
                close: true,
                style: {
                    background: config[type].background,
                    color: '#fff'
                }
            }).showToast();
        }
    });
});
</script>
@endif --}}
