<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Variance Report – Stock Count #<?php echo e($stockCount->id); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background: #f9fafb;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .btn {
            border-radius: 8px;
        }
        table th {
            font-weight: 600;
            white-space: nowrap;
        }
        table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container py-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            Variance Report – Stock Count #<?php echo e($stockCount->id); ?>

            <small class="text-muted fs-6">
                (<?php echo e(\Illuminate\Support\Carbon::parse($stockCount->count_date)->toDateString()); ?>)
            </small>
        </h2>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('stock_count.show', $stockCount->id)); ?>" class="btn btn-outline-secondary btn-sm">Back to Scan</a>
            <a href="<?php echo e(route('stock_count.variance.export', $stockCount->id)); ?>" class="btn btn-outline-primary btn-sm">Export CSV</a>
        </div>
    </div>

    
    <form method="GET" action="<?php echo e(route('stock_count.variance', $stockCount->id)); ?>" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="code" value="<?php echo e(request('code')); ?>" class="form-control" placeholder="Filter by code">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Status: All</option>
                    <option value="match" <?php echo e(request('status')=='match'?'selected':''); ?>>Match</option>
                    <option value="over"  <?php echo e(request('status')=='over' ?'selected':''); ?>>Over</option>
                    <option value="short" <?php echo e(request('status')=='short'?'selected':''); ?>>Short</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Apply</button>
            </div>
        </div>
    </form>

    
    <div class="card mb-4">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="fw-semibold text-muted">Total Current</div>
                    <div class="h4 mb-0"><?php echo e((int)($totals->sum_current ?? 0)); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold text-muted">Total Inventoried</div>
                    <div class="h4 mb-0"><?php echo e((int)($totals->sum_inventoried ?? 0)); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold text-muted">Total Variance</div>
                    <?php
                        $sumVar = (int)($totals->sum_variance ?? 0);
                        $badge = $sumVar === 0 ? 'bg-secondary' : ($sumVar > 0 ? 'bg-success' : 'bg-danger');
                    ?>
                    <span class="badge <?php echo e($badge); ?> fs-5 px-3 py-2"><?php echo e($sumVar); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Category</th>
                        <th>UOM</th>
                        <th class="text-end">Current</th>
                        <th class="text-end">Inventoried</th>
                        <th class="text-end">Variance</th>
                        <th>Status</th>
                        <th>Last Scan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $lots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $variance = (int)$p->inventoried_stock - (int)$p->balance;
                            $status = $variance === 0 ? 'Match' : ($variance > 0 ? 'Over' : 'Short');
                            $cls = $variance === 0 ? 'text-muted' : ($variance > 0 ? 'text-success fw-semibold' : 'text-danger fw-semibold');
                            $badge = $variance === 0 ? 'bg-secondary' : ($variance > 0 ? 'bg-success' : 'bg-danger');
                        ?>
                        <tr>
                            <td><?php echo e($p->code); ?></td>
                            <td><?php echo e($p->category_id); ?></td>
                            <td><?php echo e($p->uom_id); ?></td>
                            <td class="text-end"><?php echo e($p->balance); ?></td>
                            <td class="text-end"><?php echo e($p->inventoried_stock); ?></td>
                            <td class="text-end <?php echo e($cls); ?>"><?php echo e($variance); ?></td>
                            <td><span class="badge <?php echo e($badge); ?>"><?php echo e($status); ?></span></td>
                            <td><?php echo e($p->scanned_at); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-3">
            <?php echo e($lots->links()); ?>

        </div>
    </div>

</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/stock_count/variance.blade.php ENDPATH**/ ?>