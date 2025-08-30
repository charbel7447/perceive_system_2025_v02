<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Journal Vouchers Movement</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f8f9fa;
        }
        h5 {
            font-weight: 700;
            margin: 0;
        }
        .card {
            border-radius: 12px;
            border: none;
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 1rem 1.25rem;
        }
        .card-header h5 {
            font-weight: 600;
            font-size: 1.2rem;
        }
        .table th {
            background: #212529 !important;
            color: #fff;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        .btn-sm {
            border-radius: 8px;
            font-weight: 500;
        }
        .collapse td {
            padding: 0.75rem;
            background: #fdfdfd;
        }
        .sub-table th {
            background: #f1f3f5 !important;
            color: #333;
        }
        .sub-table td {
            font-size: 0.9rem;
        }
        .badge-status {
            padding: 0.35em 0.65em;
            font-size: 0.75rem;
            border-radius: 8px;
        }
        .badge-added {
            background: #d1e7dd;
            color: #0f5132;
        }
        .badge-deleted {
            background: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>
<div class="containerx py-4">
    <div class="card shadow-sm">
        
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>📊 Journal Vouchers Movement</h5>
            <div class="d-flex gap-2">
                <a target="_top" href="<?php echo e(url('/')); ?>/trial_balance_report" class="btn btn-success btn-sm">
                    📥 Trial Balance
                </a>
                <a href="<?php echo e(url('/')); ?>/system/journal_vouchers_movement/export" class="btn btn-outline-success btn-sm">
                    📤 Export Excel
                </a>
            </div>
        </div>

        
        <div class="card-body table-responsive">
            <table id="movementTable" class="table table-bordered table-hover align-middle">
                <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>JV Number</th>
                    <th>Document</th>
                    <th>Doc Date</th>
                    <th>Currency</th>
                    <th>Exchange Rate</th>
                    <th>Total Debit</th>
                    <th>Total Credit</th>
                    <th>Created By</th>
                    <th>Movement Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $move): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="text-center">
                        <td><?php echo e($loop->iteration); ?></td>
                        <td>
                            <?php if($move->type === 'Added'): ?>
                                <span class="badge-status badge-added">⬆️ <?php echo e($move->type); ?></span>
                            <?php elseif($move->type === 'Deleted'): ?>
                                <span class="badge-status badge-deleted">⬇️ <?php echo e($move->type); ?></span>
                            <?php else: ?>
                                <span class="badge-status bg-secondary text-white"><?php echo e($move->type); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($move->number); ?></td>
                        <td>
                            <strong><?php echo e($move->document_number); ?></strong><br>
                            <small class="text-muted"><?php echo e($move->document_name); ?></small>
                        </td>
                        <td><?php echo e($move->document_date ?? '-'); ?></td>
                        <td>
                            <?php $currency_name = \App\Currency::where('id','=',$move->currency_id)->value('code'); ?>
                            <?php echo e($currency_name ?? '-'); ?>

                        </td>
                        <td><?php echo e(number_format($move->exchange_rate, 3)); ?></td>
                        <td class="text-success fw-bold"><?php echo e(number_format($move->total_debit, 3)); ?></td>
                        <td class="text-danger fw-bold"><?php echo e(number_format($move->total_credit, 3)); ?></td>
                        <td><?php echo e($move->created_by); ?></td>
                        <td><?php echo e($move->movement_date); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="collapse"
                                    data-bs-target="#expandRow<?php echo e($loop->index); ?>" aria-expanded="false"
                                    aria-controls="expandRow<?php echo e($loop->index); ?>">
                                ➕ Details
                            </button>
                        </td>
                    </tr>

                    
                    <tr id="expandRow<?php echo e($loop->index); ?>" class="collapse">
                        <td colspan="12">
                            <?php $items = json_decode($move->items, true); ?>

                            <?php if(is_array($items) && count($items) > 0): ?>
                                <table class="table sub-table table-sm mb-0">
                                    <thead>
                                    <tr>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Account Name AR</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Cost Center</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['account_code']); ?></td>
                                            <td><?php echo e($item['account_name_en']); ?></td>
                                            <td><?php echo e($item['account_name_ar']); ?></td>
                                            <td class="text-success"><?php echo e($item['debit']); ?></td>
                                            <td class="text-danger"><?php echo e($item['credit']); ?></td>
                                            <td>
                                                <?php 
                                                    $cost_center_code = \App\ChartOfAccount::where('code','=',$item['account_code'])->value('class_code');
                                                    $cost_center_name_en = \App\ClassesCode::where('code','=',$cost_center_code)->value('name_en');
                                                    $cost_center_name_ar = \App\ClassesCode::where('code','=',$cost_center_code)->value('name_ar');
                                                ?>
                                                <span><b>(<?php echo e($cost_center_code); ?>)</b></span> - <?php echo e($cost_center_name_en); ?>/<?php echo e($cost_center_name_ar); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted mb-0">No journal items found.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/journal_vouchers_movement.blade.php ENDPATH**/ ?>