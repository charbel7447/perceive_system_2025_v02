
<?php $__env->startSection('parentPageTitle', 'Pages'); ?>
<?php $__env->startSection('title', 'SQL Query'); ?>
<?php use Illuminate\Support\Str; ?>

<?php $__env->startSection('content'); ?>
<!-- Load monospace font for code editor -->
<link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro&display=swap" rel="stylesheet" />
<style>
    .code-input {
        background-color: #343a40 !important;
        color: #00ff00 !important;
        font-family: 'Source Code Pro', monospace !important;
        font-size: 1rem !important;
        padding: 10px !important;
        border: 1px solid #222 !important;
        border-radius: 6px !important;
        resize: vertical !important;
        white-space: pre-wrap !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 800px !important;
    }

    #tableSearch {
        max-width: 300px;
        transition: opacity 0.3s ease;
    }

    .table-scroll-wrapper {
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    .table-scroll-top {
        overflow-x: auto;
        overflow-y: hidden;
        height: 20px;
        border-bottom: 1px solid #ddd;
    }

    .table-scroll-top .table-scroll-inner {
        height: 1px;
        /* width set dynamically by JS */
    }

    .table-scroll-body {
        overflow-x: auto;
        width: 100%;
        max-height: 600px; /* optional max height for vertical scrolling */
    }

    table#resultsTable {
        white-space: nowrap;
        width: auto !important;
        min-width: 1000px;
        max-width: none !important;
    }

    table#resultsTable thead th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 1;
    }
</style>

<div class="card py-2" style="padding: 20px;">
    <div class="mb-3 d-flex gap-2">
        <button id="btnSqlHints" style="line-height: 1px;font-size:14px;" class="btn btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#sqlHints" aria-expanded="false" aria-controls="sqlHints">
            💡 Show SQL Hints
        </button>
    &nbsp;&nbsp;
        <button id="btnDbSchema" style="line-height: 1px;font-size:14px;" class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#dbSchema" aria-expanded="false" aria-controls="dbSchema">
            📋 Show All Tables & Columns
        </button>
 &nbsp;&nbsp;
            <button id="toggleSavedQueries" class="btn btn-outline-dark btn-sxm" style="line-height: 1px;font-size: 14px;">
        💾 Show Saved Queries
    </button>
    </div>

    <div class="collapse" id="sqlHints">
        <div class="p-3 bg-dark text-light rounded smallx" style="font-family: monospace; white-space: pre; overflow-x: auto;">
-- Basic SELECT
SELECT * FROM users;

-- SELECT with WHERE
SELECT * FROM users WHERE status = 'active';

-- SELECT with AND
SELECT * FROM users WHERE status = 'active' AND role = 'admin';

-- SELECT with OR
SELECT * FROM users WHERE email LIKE '%@gmail.com' OR status = 'inactive';

-- SELECT with JOIN
SELECT orders.id, users.name
FROM orders
JOIN users ON orders.user_id = users.id;

-- SELECT with WHERE and JOIN
SELECT orders.id, users.name
FROM orders
JOIN users ON orders.user_id = users.id
WHERE orders.status = 'paid';

-- SELECT TOP N or LIMIT
SELECT TOP 10 * FROM users;     -- SQL Server
SELECT * FROM users LIMIT 10;   -- MySQL/PostgreSQL

-- SELECT with ORDER BY
SELECT * FROM products ORDER BY created_at DESC;

-- Filter by date
SELECT * FROM logbooks WHERE created_at >= '2024-01-01';
        </div>
    </div>

    <div class="collapse" id="dbSchema">
        <div class="mb-3">
            <input type="text" id="tableSearch" class="form-control" placeholder="Search tables..." style="display:none;">
        </div>

        <div class="accordion" id="accordionSchema" style="column-count: 5;">
            <?php $__currentLoopData = $tableList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="accordion-item" data-table-name="<?php echo e(strtolower($table)); ?>">
                    <h2 class="accordion-header" id="heading-<?php echo e($index); ?>">
                        <button style="border:0;font-size: 11px;background: transparent;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo e($index); ?>" aria-expanded="false" aria-controls="collapse-<?php echo e($index); ?>">
                            🗂️ <?php echo e($table); ?>

                        </button>
                    </h2>
                    <div id="collapse-<?php echo e($index); ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo e($index); ?>" data-bs-parent="#accordionSchema">
                        <div class="accordion-body">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Column</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $tableColumns[$table]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($col); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="mb-3">


    <div id="savedQueriesSection" class="mt-3" style="display:none;">
        <?php $__empty_1 = true; $__currentLoopData = $saved_queries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $query): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border rounded p-2 mb-2 bg-light">
                <div class="row d-flexx justify-content-between xalign-items-center">
                    <div class="col-md-9" style="width:70%x;">
                        <small>(<?php echo e($query->id); ?>)</small>
                        <strong><?php echo e($query->body ?? 'Untitled Query'); ?></strong><br>
                        <code style="font-size:13px;"><?php echo e(Str::limit($query->body, 80)); ?></code>
                    </div>
                    <div class="col-md-3" style="text-align:right;top: 10px;">
                    <button style="background: #e83e8c;border-radius: 5px;border: 0px transparent;" 
                    class="btn btn-sm btn-primary use-query-btn" data-query="<?php echo e($query->body); ?>">
                        Use
                    </button>

                    <a class="btn btn-sm btn-primary use-query-btn" href="<?php echo e(url('/')); ?>/system/custom_query_delete/<?php echo e($query->id); ?>">Delete</a>

                    </div>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="alert alert-info">No saved queries available.</div>
        <?php endif; ?>
    </div>
</div>

    <hr>

    <div class="d-flex gap-2 align-items-end mb-3">
   <!-- SQL Query Form Group -->

    <!-- Run Query Form -->
    <form method="POST" action="<?php echo e(route('custom.query')); ?>" style="flex-grow:1;" id="runForm">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="sql" class="form-label" style="color: darkblue; padding: 2px 5px; border-bottom: 1px solid darkblue; margin-bottom: 5px;">
                Enter SELECT Query
            </label>
            <textarea 
                class="form-control code-input" 
                name="sql" 
                id="sql" 
                style="min-height:165px;font-size:14px !important;"
                placeholder="e.g. SELECT jv.*, jvi.*
FROM journal_vouchers jv
JOIN journal_voucher_items jvi
    ON jvi.journal_voucher_id = jv.id
WHERE jv.number = '10652'
LIMIT 100;
" 
                required 
                rows="12"
            ><?php echo e(old('sql')); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="font-size:14px; line-height: 1px;">Run Query</button>
    </form>

    <!-- Save & Run Query Form -->
    <form method="POST" action="<?php echo e(route('save.query')); ?>" id="saveForm" style="height: fit-content;">
        <?php echo csrf_field(); ?>
        <!-- Hidden field will be synced by JS -->
        <input type="hidden" name="sql" id="sqlHidden">
        <button type="submit" class="btn btn-warning" style="font-size:14px; white-space: nowrap;">💾 Save & Run</button>
    </form>

        <!-- Export Form -->
        <?php if(session('results') && old('sql')): ?>
            <form method="POST" action="<?php echo e(route('custom.query.export')); ?>" style="height: fit-content;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="sql" value="<?php echo e(old('sql')); ?>">
                <button type="submit" style="font-size:14px; white-space: nowrap;" class="btn btn-success">Export Result</button>
            </form>
        <?php endif; ?>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-danger mt-3">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('results')): ?>
        <hr>
        <div class="table-scroll-wrapper mb-4">

            <!-- Top horizontal scrollbar -->
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div> 
            </div>

            <!-- Scrollable table container -->
            <div class="table-scroll-body" id="tableScrollBody">
                <table class="table table-bordered table-striped" id="resultsTable" style="width:auto;">
                    <thead>
                        <tr>
                            <?php $__currentLoopData = array_keys(session('results')[0] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th><?php echo e($col); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = session('results'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td><?php echo e($cell); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainTextarea = document.getElementById('sql');
        const sqlHiddenInput = document.getElementById('sqlHidden');

        const saveForm = document.getElementById('saveForm');

        saveForm.addEventListener('submit', function () {
            sqlHiddenInput.value = mainTextarea.value;
        });
    });
</script>


<script>
    $(document).ready(function () {
        const $top = $('#tableScrollTop');
        const $body = $('#tableScrollBody');
        const $inner = $('.table-scroll-inner');

        function updateScrollWidth() {
            const tableWidth = $('#resultsTable').outerWidth() || 0;
            $inner.width(tableWidth);
        }

        updateScrollWidth();
        $(window).on('resize', updateScrollWidth);

        $top.on('scroll', function () {
            $body.scrollLeft($top.scrollLeft());
        });

        $body.on('scroll', function () {
            $top.scrollLeft($body.scrollLeft());
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const btnSqlHints = document.getElementById('btnSqlHints');
        const sqlHintsCollapse = document.getElementById('sqlHints');

        const btnDbSchema = document.getElementById('btnDbSchema');
        const dbSchemaCollapse = document.getElementById('dbSchema');

        const searchInput = document.getElementById('tableSearch');
        const accordion = document.getElementById('accordionSchema');
        const items = accordion.querySelectorAll('.accordion-item');

        sqlHintsCollapse.addEventListener('show.bs.collapse', function () {
            btnSqlHints.innerHTML = '💡 Hide SQL Hints';
        });
        sqlHintsCollapse.addEventListener('hide.bs.collapse', function () {
            btnSqlHints.innerHTML = '💡 Show SQL Hints';
        });

        dbSchemaCollapse.addEventListener('show.bs.collapse', function () {
            btnDbSchema.innerHTML = '📋 Hide All Tables & Columns';
            searchInput.style.display = 'block';
            searchInput.focus();
        });
        dbSchemaCollapse.addEventListener('hide.bs.collapse', function () {
            btnDbSchema.innerHTML = '📋 Show All Tables & Columns';
            searchInput.value = '';
            searchInput.style.display = 'none';
            items.forEach(item => item.style.display = 'block');
        });

        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            items.forEach(item => {
                const tableName = item.getAttribute('data-table-name');
                item.style.display = tableName.includes(filter) ? 'block' : 'none';
            });
        });
    });
</script>

<!-- copy saved queries -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleSavedQueries');
    const section = document.getElementById('savedQueriesSection');

    toggleBtn.addEventListener('click', function () {
        const isVisible = section.style.display === 'block';
        section.style.display = isVisible ? 'none' : 'block';
        toggleBtn.textContent = isVisible ? '💾 Show Saved Queries' : '💾 Hide Saved Queries';
    });

    document.querySelectorAll('.use-query-btn').forEach(function(button) {
        button.addEventListener('click', function () {
            const query = this.getAttribute('data-query');
            const textarea = document.getElementById('sql');
            textarea.value = query;
            textarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });
});

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.masterCustom', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\perceive_system_2025_v02\resources\views/custom_query.blade.php ENDPATH**/ ?>