<x-ui.modal id="quickViewModal" size="lg" centered="true">
    <div class="row g-4" id="quickViewContent">
        <div class="col-md-6">
            <div class="quick-view-image rounded-4 bg-slate-50 p-4 h-100 d-flex align-items-center justify-content-center">
                <img src="" alt="" class="img-fluid" id="qv-image">
            </div>
        </div>
        <div class="col-md-6">
            <div class="quick-view-info">
                <span class="badge bg-slate-100 text-slate-400 text-uppercase fw-bold mb-2" id="qv-category"></span>
                <h2 class="fw-800 mb-3" id="qv-name"></h2>
                <div class="fs-3 fw-800 text-primary mb-4" id="qv-price"></div>
                <p class="text-slate-500 mb-5" id="qv-description"></p>
                
                <div class="d-flex gap-3">
                    <x-ui.button variant="primary" size="lg" class="flex-grow-1" id="qv-add-btn">
                        Add to Cart
                    </x-ui.button>
                    <a href="" class="btn btn-luxe-outline btn-luxe px-4" id="qv-details-link">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="quickViewLoader" class="d-none py-5 text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</x-ui.modal>

<script>
function openQuickView(productId) {
    const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
    const content = document.getElementById('quickViewContent');
    const loader = document.getElementById('quickViewLoader');
    
    content.classList.add('d-none');
    loader.classList.remove('d-none');
    modal.show();
    
    let url = "{{ route('client.products.quickView', ['id' => ':id']) }}";
    url = url.replace(':id', productId);

    fetch(url)
        .then(res => res.json())
        .then(data => {
            document.getElementById('qv-name').innerText = data.name;
            document.getElementById('qv-price').innerText = data.price;
            document.getElementById('qv-description').innerText = data.description;
            document.getElementById('qv-category').innerText = data.category;
            document.getElementById('qv-image').src = data.image;
            document.getElementById('qv-details-link').href = data.url;
            
            const addBtn = document.getElementById('qv-add-btn');
            addBtn.onclick = () => {
                addToCart(data.id, 1);
                modal.hide();
            };
            
            loader.classList.add('d-none');
            content.classList.remove('d-none');
            content.classList.add('animate-fade-up');
        })
        .catch(err => {
            console.error(err);
            modal.hide();
            showToast('error', 'Could not load product details.');
        });
}
</script>
