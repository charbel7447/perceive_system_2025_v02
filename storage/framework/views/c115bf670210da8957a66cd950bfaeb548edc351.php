        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr class="fw-bold">
                    <th>Class Code</th>
                    <th>Class</th>
                    <th>Class ar</th>
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
                        if ($value > 0) return 'text-success';  // green
                        if ($value < 0) return 'text-danger';   // red
                        return 'text-secondary';                 // gray
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
            <tfoot>
                <tr class="fw-bold">
                    <th colspan="5" class="text-end">Total</th>
                    <th class="text-end <?php echo e(colorClass($totalDebit)); ?>"><?php echo e(number_format($totalDebit, 3)); ?></th>
                    <th class="text-end <?php echo e(colorClass($totalCredit)); ?>"><?php echo e(number_format($totalCredit, 3)); ?></th>
                    <th class="text-end <?php echo e(colorClass($totalDebit - $totalCredit)); ?>"><?php echo e(number_format($totalDebit - $totalCredit, 3)); ?></th>
                </tr>
            </tfoot>
        </table>
 <?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/reports/trial_balance_excel.blade.php ENDPATH**/ ?>