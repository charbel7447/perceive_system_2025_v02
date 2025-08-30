<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Count #<?php echo e($stockCount->id); ?></title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Lato', sans-serif; background: #f8f9fa; }
        h2 { font-weight: 700; }
        .card { border-radius: 12px; }
        .btn { border-radius: 8px; }
        table thead th { white-space: nowrap; }
        .variance.text-success { font-weight: 600; }
        .variance.text-danger { font-weight: 600; }

        /* Row flash highlight */
        @keyframes  flashHighlight {
            0% { background-color: #d4edda; }
            50% { background-color: #a5d6a7; }
            100% { background-color: transparent; }
        }
        .flash-highlight { animation: flashHighlight 1.5s ease-out; }

        /* Slide & fade scanned card */
        #scannedProductCard {
            opacity: 0; transform: translateY(-20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        #scannedProductCard.show-card {
            opacity: 1; transform: translateY(0);
        }

        /* Bounce animation for confirmed card */
        @keyframes  bounceCard {
            0% { transform: translateY(0); }
            25% { transform: translateY(-10px); }
            50% { transform: translateY(0); }
            75% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }
        .bounce { animation: bounceCard 0.4s ease; }
    </style>
</head>
<body>
<div class="container py-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            Stock Count #<?php echo e($stockCount->id); ?>

            <span class="text-muted fs-6">(<?php echo e($stockCount->count_date); ?>)</span>
        </h2>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('stock_count.index')); ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-arrow-left"></i> Go Back
            </a>
            <a href="<?php echo e(route('stock_count.variance', $stockCount->id)); ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-bar-chart"></i> Variance Report
            </a>
            <?php if(empty($stockCount->submitted_at)): ?>
            <button id="submitCountBtn" class="btn btn-success btn-sm">
                <i class="bi bi-check2-circle"></i> Submit Stock Count
            </button>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between text-center">
            <div><strong>Total Scanned</strong><br><span id="totalScanned">0</span></div>
            <div><strong>Total Variance</strong><br><span id="totalVariance">0</span></div>
            <div><strong>Remaining Products</strong><br><span id="remainingProducts"><?php echo e($stockCount->products->count()); ?></span></div>
        </div>
    </div>

    
    <div class="mb-4">
        <label for="barcodeInput" class="form-label fw-bold">Scan Barcode:</label>
        <input type="text" id="barcodeInput" class="form-control form-control-lg" placeholder="Scan product barcode..." autofocus>
    </div>

    
    <div id="scannedProductCard" class="card p-3 mb-4 border-primary shadow-sm d-none">
        <h5 class="card-title mb-3">Scanned Product</h5>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Code:</strong> <span id="prodCode"></span></div>
            <div class="col-md-3"><strong>Category:</strong> <span id="prodCat"></span></div>
            <div class="col-md-3"><strong>Sub Category:</strong> <span id="prodSub"></span></div>
            <div class="col-md-3"><strong>Sub Sub Category:</strong> <span id="prodSubSub"></span></div>
        </div>
        <div class="mb-3">
            <label for="qtyInput" class="form-label fw-bold">Inventoried Qty:</label>
            <input type="number" id="qtyInput" class="form-control form-control-sm">
        </div>
        <button id="confirmBtn" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Confirm</button>
    </div>

    
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Sub Sub Category</th>
                        <th>Current Stock</th>
                        <th>Inventoried Stock</th>
                        <th>Variance</th>
                        <th>Scanned At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $stockCount->lots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr id="row-<?php echo e($p->id); ?>">
                            <td><?php echo e($p->code); ?></td>
                            <td><?php echo e($p->category ? "({$p->category->id}) - {$p->category->name}" : $p->category_id); ?></td>
                            <td><?php echo e($p->subCategory ? "({$p->subCategory->id}) - {$p->subCategory->name}" : $p->sub_category_id); ?></td>
                            <td><?php echo e($p->subSubCategory ? "({$p->subSubCategory->id}) - {$p->subSubCategory->name}" : $p->sub_sub_category_id); ?></td>
                            <td class="currentStock"><?php echo e($p->current_stock); ?></td>
                            <td class="invStock"><?php echo e($p->inventoried_stock); ?></td>
                            <td class="variance <?php echo e($p->variance == 0 ? 'text-muted' : ($p->variance > 0 ? 'text-success' : 'text-danger')); ?>">
                                <?php echo e($p->variance); ?>

                            </td>
                            <td class="scannedAt"><?php echo e($p->scanned_at); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<audio id="successSound" src="<?php echo e(asset('sounds/success.mp3')); ?>" preload="auto"></audio>
<audio id="errorSound" src="<?php echo e(asset('sounds/error.mp3')); ?>" preload="auto"></audio>


<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
    <div id="toastMessage" class="toast text-white bg-success border-0">
        <div class="d-flex">
            <div class="toast-body" id="toastText">✅ Product Updated Successfully</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


 



<style>
/* Row flash highlight */
@keyframes  flashHighlight {
    0%   { background-color: #d4edda; }
    50%  { background-color: #a5d6a7; }
    100% { background-color: transparent; }
}
.flash-highlight {
    animation: flashHighlight 1.5s ease-out;
}

/* Slide & fade scanned card */
#scannedProductCard {
    opacity: 0;
    transform: translateY(-20px);
    transition: opacity 0.3s ease, transform 0.3s ease;
}
#scannedProductCard.show-card {
    opacity: 1;
    transform: translateY(0);
}

/* Bounce animation for confirmed card */
@keyframes  bounceCard {
    0% { transform: translateY(0); }
    25% { transform: translateY(-10px); }
    50% { transform: translateY(0); }
    75% { transform: translateY(-5px); }
    100% { transform: translateY(0); }
}
.bounce {
    animation: bounceCard 0.4s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcodeInput');
    const qtyInput = document.getElementById('qtyInput');
    const confirmBtn = document.getElementById('confirmBtn');
    const submitCountBtn = document.getElementById('submitCountBtn');
    const toastEl = document.getElementById('toastMessage');
    const toastText = document.getElementById('toastText');
    const totalScannedEl = document.getElementById('totalScanned');
    const totalVarianceEl = document.getElementById('totalVariance');
    const remainingProductsEl = document.getElementById('remainingProducts');

    if (!barcodeInput || !qtyInput || !confirmBtn || !toastEl) return;

    const productsTableRows = document.querySelectorAll('tbody tr');
    const successSound = document.getElementById('successSound');
    const errorSound = document.getElementById('errorSound');
    const toast = new bootstrap.Toast(toastEl, { delay: 2000 });
    let scannedProductId = null;

    /* Focus Management */
    function keepFocus() {
        const cardVisible = !document.getElementById('scannedProductCard').classList.contains('d-none');
        const active = document.activeElement;
        if (!cardVisible && active !== qtyInput) {
            setTimeout(() => barcodeInput.focus(), 200);
        }
    }

    /* Toast Utility */
    function showToast(message, success = true) {
        toastText.innerText = message;
        toastEl.classList.remove('bg-success', 'bg-danger');
        toastEl.classList.add(success ? 'bg-success' : 'bg-danger');
        toast.show();
    }

    /* Summary Update */
    function updateSummary() {
        let totalScanned = 0, totalVariance = 0, remaining = 0;
        productsTableRows.forEach(row => {
            const inv = parseFloat(row.querySelector('.invStock')?.innerText || 0);
            const curr = parseFloat(row.querySelector('.currentStock')?.innerText || 0);
            if (inv > 0) totalScanned++;
            totalVariance += (inv - curr);
            if (inv === 0) remaining++;
        });
        totalScannedEl.innerText = totalScanned;
        totalVarianceEl.innerText = totalVariance;
        remainingProductsEl.innerText = remaining;
    }

    /* Show/Hide Scanned Card */
    function showScannedCard() {
        const card = document.getElementById('scannedProductCard');
        if (!card) return;
        card.classList.remove('d-none');
        setTimeout(() => card.classList.add('show-card'), 10);
    }
    function hideScannedCard() {
        const card = document.getElementById('scannedProductCard');
        if (!card) return;
        card.classList.remove('show-card');
        setTimeout(() => card.classList.add('d-none'), 300);
    }

    /* Bounce Card */
    function bounceCard() {
        const card = document.getElementById('scannedProductCard');
        if (!card) return;
        card.classList.remove('bounce');
        void card.offsetWidth;
        card.classList.add('bounce');
    }

    /* Confirm Scan */
    function confirmScan() {
        if (!scannedProductId) return;
        const qty = qtyInput.value || 0;

        fetch(`<?php echo e(url('/stock-count/'.$stockCount->id)); ?>/confirm-ajax/${scannedProductId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
            },
            body: JSON.stringify({ inventoried_stock: qty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                errorSound?.play();
                showToast(data.error, false);
                keepFocus();
                return;
            }

            const row = document.getElementById('row-' + data.id);
            if (row) {
                row.querySelector('.invStock').innerText = data.inventoried_stock;
                row.querySelector('.scannedAt').innerText = data.scanned_at;
                const varianceCell = row.querySelector('.variance');
                varianceCell.innerText = data.variance;
                varianceCell.className = "variance " + (data.variance == 0 ? "text-muted" : (data.variance > 0 ? "text-success" : "text-danger"));
                row.classList.remove('flash-highlight');
                void row.offsetWidth;
                row.classList.add('flash-highlight');
            }

            successSound?.play();
            showToast("✅ Product Updated Successfully");
            bounceCard();
            setTimeout(() => { hideScannedCard(); scannedProductId=null; keepFocus(); }, 1500);
            updateSummary();
        });
    }

    /* Barcode Scan */
    barcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const barcode = this.value.trim();
            if (!barcode) return;

            fetch("<?php echo e(route('stock_count.scan.ajax', $stockCount->id)); ?>", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" },
                body: JSON.stringify({ barcode })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) { errorSound?.play(); showToast(data.error, false); keepFocus(); return; }
                successSound?.play();
                scannedProductId = data.id;
                document.getElementById('prodCode').innerText = data.code || '';
                document.getElementById('prodCat').innerText = data.category_id && data.category_name 
    ? `(${data.category_id}) - ${data.category_name}` 
    : data.category_id || '';

document.getElementById('prodSub').innerText = data.sub_category_id && data.sub_category_name 
    ? `(${data.sub_category_id}) - ${data.sub_category_name}` 
    : data.sub_category_id || '';

document.getElementById('prodSubSub').innerText = data.sub_sub_category_id && data.sub_sub_category_name 
    ? `(${data.sub_sub_category_id}) - ${data.sub_sub_category_name}` 
    : data.sub_sub_category_id || '';
                qtyInput.value = data.current_stock || 0;
                showScannedCard();
                qtyInput.focus();
            });
            this.value = '';
        }
    });

    confirmBtn.addEventListener('click', confirmScan);
    qtyInput.addEventListener('keypress', function(e) { if (e.key === 'Enter') { e.preventDefault(); confirmScan(); } });

    /* Submit Stock Count */
    submitCountBtn.addEventListener('click', function() {
        if (!confirm('Are you sure to submit this stock count and update inventory?')) return;

        fetch(`<?php echo e(route('stock_count.submit', $stockCount->id)); ?>`, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" }
        }).then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast("✅ Stock Count submitted successfully!");
                setTimeout(() => { window.location.href="<?php echo e(route('stock_count.index')); ?>"; }, 1500);
            } else {
                showToast(data.error || "❌ Failed to submit stock count", false);
            }
        });
    });

    updateSummary();
    keepFocus();
    window.addEventListener('focus', keepFocus);
    document.addEventListener('click', keepFocus);
});
</script>
</body>
</html><?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/stock_count/show.blade.php ENDPATH**/ ?>