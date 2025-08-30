<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trial Balance Report</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.6.2/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
            color: #2e2e2e;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        }
        .table thead {
            background: #3c78d8;
            color: #fff;
        }
        td.text-end {
            font-family: monospace;
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
        .select2-container--bootstrap-5 .select2-selection--single {
            height: 38px;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div class="container-fluid p-4">
    <div class="card p-4">
        <h2 class="fw-bold mb-4"><i class="fa-solid fa-balance-scale"></i> Trial Balance Report</h2>

        <!-- Filter Form -->
        <form method="GET" action="<?php echo e(route('reports.trial_balance')); ?>" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Account (optional)</label>
                    <select name="account_id" class="form-control select2">
                        <option value="">-- All Accounts --</option>
                        <?php $__currentLoopData = \App\ChartOfAccount::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($account->id); ?>" <?php echo e(request('account_id') == $account->id ? 'selected' : ''); ?>>
                                <?php echo e($account->code); ?> - <?php echo e($account->name_en); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="mb-3">
            <a href="<?php echo e(route('reports.trial_balance.export', request()->all())); ?>" class="btn btn-success fw-bold">
                <i class="fa-solid fa-file-excel"></i> Export to Excel
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead>
                    <tr class="fw-bold">
                        <th>Class Code</th>
                        <th>Class</th>
                        <th>Class (AR)</th>
                        <th>Account</th>
                        <th>Account Name</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $totalDebit = 0;
                        $totalCredit = 0;
                        function colorClass($value) {
                            if ($value > 0) return 'text-success';
                            if ($value < 0) return 'text-danger';
                            return 'text-secondary';
                        }
                    ?>
                    <?php $__currentLoopData = $trialBalances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $totalDebit += $row->total_debit;
                            $totalCredit += $row->total_credit;
                        ?>
                        <tr>
                            <td><?php echo e($row->account_id); ?></td>
                            <td><?php echo e($row->class_name_en); ?></td>
                            <td><?php echo e($row->class_name_ar); ?></td>
                            <td><?php echo e($row->account_code); ?></td>
                            <td><?php echo e($row->account_name_en); ?></td>
                            <td class="text-end <?php echo e(colorClass($row->total_debit)); ?>"><?php echo e(number_format($row->total_debit, 3)); ?></td>
                            <td class="text-end <?php echo e(colorClass($row->total_credit)); ?>"><?php echo e(number_format($row->total_credit, 3)); ?></td>
                            <td class="text-end <?php echo e(colorClass($row->balance)); ?>"><?php echo e(number_format($row->balance, 3)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <th colspan="5" class="text-end">Total</th>
                        <th class="text-end <?php echo e(colorClass($totalDebit)); ?>"><?php echo e(number_format($totalDebit, 3)); ?></th>
                        <th class="text-end <?php echo e(colorClass($totalCredit)); ?>"><?php echo e(number_format($totalCredit, 3)); ?></th>
                        <th class="text-end <?php echo e(colorClass($totalDebit - $totalCredit)); ?>"><?php echo e(number_format($totalDebit - $totalCredit, 3)); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Balance check -->
        <?php if($totalDebit != $totalCredit): ?>
            <div class="alert alert-warning mt-3 text-center fw-bold">
                ⚠️ Trial Balance is not balanced!
            </div>
        <?php else: ?>
            <div class="alert alert-success mt-3 text-center fw-bold">
                ✅ Trial Balance is balanced.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "Select Account",
            allowClear: true
        });
    });
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/reports/trial_balance.blade.php ENDPATH**/ ?>