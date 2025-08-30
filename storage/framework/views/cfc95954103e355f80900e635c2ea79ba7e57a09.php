<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Counts</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <!-- Lato Font -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f9fafc;
        }
        h2 {
            font-weight: 700;
            color: #2e2e2e;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        }
        .card-header {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .btn-primary {
            background: #3c78d8;
            border: none;
        }
        .btn-primary:hover {
            background: #325fa6;
        }
        .btn-success {
            background: #28a745;
            border: none;
        }
        .btn-success:hover {
            background: #1e7e34;
        }
        .table thead {
            background: #3c78d8;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="fa-solid fa-boxes-stacked"></i> Stock Counts</h2>

    <!-- New Stock Count -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fa-solid fa-plus-circle"></i> Start New Stock Count
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('stock_count.store')); ?>" method="POST" class="row g-2 align-items-end">
                <?php echo csrf_field(); ?>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">All</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-bold">SubCategory</label>
                    <select name="sub_category_id" class="form-select">
                        <option value="">All</option>
                        <?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sub->id); ?>"><?php echo e($sub->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-bold">SubSubCategory</label>
                    <select name="sub_sub_category_id" class="form-select">
                        <option value="">All</option>
                        <?php $__currentLoopData = $subsubcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subsub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subsub->id); ?>"><?php echo e($subsub->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-md-3 text-md-end mt-2 mt-md-0">
                    <button class="btn btn-primary w-100 w-md-auto">
                        <i class="fa-solid fa-circle-check"></i> Create Stock Count
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Stock Counts -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <i class="fa-solid fa-list"></i> All Stock Counts
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Products Count</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Find latest unsubmitted stock count    
                    $latestUnsubmitted = $stockCounts->where('submitted_at', null)
                        ->sortByDesc('id')
                        ->first();
                    $latestSubmitted = $stockCounts->where('submitted_at', '!=', null)
                        ->sortByDesc('id')
                        ->first();
                ?>

                <?php $__currentLoopData = $stockCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($count->id); ?></td>
                    <td><?php echo e($count->count_date); ?></td>
                    <td><?php echo e($count->products->count()); ?></td>
                    <td>
                        <?php if($count->submitted_at): ?>
                            <span class="badge bg-success"><i class="fa-solid fa-check"></i> Submitted</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock"></i> Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('stock_count.show', $count->id)); ?>" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-eye"></i> View
                        </a>

                        
                        <?php if($latestUnsubmitted && $latestSubmitted): ?>
                            <?php if(is_null($count->submitted_at) 
                                && $count->id === $latestUnsubmitted->id 
                                && $latestSubmitted->id < $count->id): ?>
                                <button class="btn btn-sm btn-success submitStockBtn" data-id="<?php echo e($count->id); ?>">
                                    <i class="fa-solid fa-upload"></i> Post
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.submitStockBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        if (!confirm('Are you sure to submit this stock count and reflect to inventory?')) return;

        fetch(`/stock-count/${id}/submit`, {
            method: 'POST',
            headers: { "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" }
        }).then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('✅ Stock count submitted successfully');
                window.location.reload();
            } else {
                alert('❌ Failed to submit stock count');
            }
        });
    });
});
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/stock_count/index.blade.php ENDPATH**/ ?>