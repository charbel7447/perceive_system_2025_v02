

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Universal Journal Flow Mapping</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('journal-flow.store')); ?>">
        <?php echo csrf_field(); ?>

        
        <div class="text-end mb-3">
            <button type="submit" class="btn btn-success">Save Mapping</button>
        </div>

        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $saved = $savedMappings[$section]->mappings ?? [];
                $isFirst = $index === 0;
            ?>

            <div class="card mb-3">
                <div style="padding: 5px 10px;font-size: 18px;font-weight: bold;" class="card-header d-flex justify-content-between align-items-center bg-secondary  text-white">
                    <span><?php echo e(ucfirst(str_replace('_', ' ', $section))); ?> Flow</span>
                    <button
                        class="btn btn-sm btn-light"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-<?php echo e($section); ?>"
                        aria-expanded="<?php echo e($isFirst ? 'true' : 'false'); ?>"
                        aria-controls="collapse-<?php echo e($section); ?>">
                        Expand
                    </button>
                </div>

                <div id="collapse-<?php echo e($section); ?>" class="collapse <?php echo e($isFirst ? 'show' : ''); ?>">
                    <div class="card-body p-0">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 30%">Field</th>
                                    <th>Ledger Account</th>
                                    <th style="width: 15%">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $fieldLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $config = $saved[$key] ?? null;
                                    ?>
                                    <tr>
                                        <td><?php echo e($fieldLabel); ?></td>
                                        <td>
                                            <select name="flows[<?php echo e($section); ?>][<?php echo e($key); ?>][account_id]" class="form-control">
                                                <option value="">-- Select Account --</option>
                                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($account->id); ?>" <?php if($config && $config['account_id'] == $account->id): ?> selected <?php endif; ?>>
                                                        <?php echo e($account->code); ?> - <?php echo e($account->name_en); ?> / <?php echo e($account->name_ar); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="flows[<?php echo e($section); ?>][<?php echo e($key); ?>][type]" class="form-control">
                                                <option value="">--</option>
                                                <option value="debit" <?php if($config && $config['type'] === 'debit'): ?> selected <?php endif; ?>>Debit</option>
                                                <option value="credit" <?php if($config && $config['type'] === 'credit'): ?> selected <?php endif; ?>>Credit</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <div class="text-end mb-4">
            <button type="submit" class="btn btn-success">Save Mapping</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<style>
    .form-control {font-size:16px !important;}
</style>
<!-- Load Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php echo $__env->make('layout.masterCustom2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/journal_vouchers_flow.blade.php ENDPATH**/ ?>