<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Balance Sheet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

    <style>
        body, html { font-family: Lato !important}
        thead th { position: sticky; top: 0; background: #f9fafb; z-index: 10; padding:5px; }
        .table-container { max-height: 500px; overflow-y: auto; }
        mark { background: #fde68a; padding: 0 .1rem; border-radius: .125rem; }
        .pane-shadow { box-shadow: -12px 0 24px -18px rgba(0,0,0,.25); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="min-h-screen bg-gray-100 py-8">
    <!-- Filters -->
    <div class="max-w-7xl mx-auto bg-white shadow rounded-2xl p-6 border border-gray-200 mb-6">
        <form method="GET" action="{{ route('balance.sheet') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1">
                <h2 class="text-3xl font-bold text-gray-800">📊 Balance Sheet</h2>
                <p class="text-gray-500 text-sm">&nbsp;</p>
            </div>

            <!-- Date Filters -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">From</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="border rounded-lg px-3 py-2 w-40 focus:ring focus:ring-blue-300 bg-gray-50"/>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">To</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="border rounded-lg px-3 py-2 w-40 focus:ring focus:ring-blue-300 bg-gray-50"/>
            </div>

            <!-- Account range filter -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">From Account</label>
                <input type="text" name="acc_from" id="accFrom" value="{{ request('acc_from') }}"
                       placeholder="Start code" class="border rounded-lg px-3 py-2 w-32 focus:ring focus:ring-blue-300 bg-gray-50"/>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">To Account</label>
                <input type="text" name="acc_to" id="accTo" value="{{ request('acc_to') }}"
                       placeholder="End code" class="border rounded-lg px-3 py-2 w-32 focus:ring focus:ring-blue-300 bg-gray-50"/>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">Search</label>
                <input id="liveSearch" type="text" name="search" placeholder="🔍 Account code, name"
                       value="{{ request('search') }}"
                       class="border rounded-lg px-4 py-2 w-64 focus:ring focus:ring-blue-300 bg-gray-50"/>
            </div>

               <p class="text-xs text-gray-500 mt-2" style="    margin-right: 15%;">
            Tip: Live search sorts by <b>exact</b> &rarr; <b>code starts with</b> &rarr; <b>name starts with</b> &rarr; <b>contains</b>. You can also filter by account range.
        </p>
            <!-- Buttons -->
            <!-- Buttons -->
<div class="flex flex-wrap gap-3 mt-2">
    <!-- Apply Button -->
    <button type="submit"
            class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-2xl shadow-lg hover:from-blue-600 hover:to-blue-700 hover:scale-105 transition-transform duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Apply
    </button>

    <!-- Reset Button -->
    <a href="{{ route('balance.sheet') }}"
       class="flex items-center gap-2 bg-gray-100 text-gray-800 px-6 py-2 rounded-2xl shadow hover:bg-gray-200 hover:scale-105 transition-transform duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6" />
        </svg>
        Reset
    </a>

    <!-- Export Button -->
    <button type="submit" name="export" value="1"
            class="flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2 rounded-2xl shadow-lg hover:from-green-600 hover:to-green-700 hover:scale-105 transition-transform duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
        </svg>
        Export
    </button>
</div>
        </form>
     
    </div>

    <!-- Main content -->
    <div id="splitRoot" class="max-w-7xl mx-auto flex gap-4">
        <div id="leftCol" class="w-full transition-all duration-300">
            <div class="space-y-8">
                @php 
                    $grandDebit = 0; $grandCredit = 0; $grandBalance = 0;
                    $showAll = !request('from') && !request('to') && !request('search') && !request('acc_from') && !request('acc_to');
                @endphp

                @foreach($classes as $class)
                    @php 
                        $filteredAccounts = $class->accounts->filter(function($account) use ($showAll){
                            if($showAll) return true;

                            $items = $account->journalItems;
                            if(request('from')) $items = $items->filter(fn($i)=>$i->voucher && $i->voucher->date >= request('from'));
                            if(request('to'))   $items = $items->filter(fn($i)=>$i->voucher && $i->voucher->date <= request('to'));

                            if(request('search')){
                                $s = strtolower(request('search'));
                                $codeMatch = str_starts_with(strtolower($account->code ?? ''), $s);
                                $nameMatch = str_contains(strtolower($account->name_en ?? ''), $s) || str_contains(strtolower($account->name_ar ?? ''), $s);
                                if(!$codeMatch && !$nameMatch) return false;
                            }

                            // Account range filter
                            $from = request('acc_from') ? strtolower(request('acc_from')) : null;
                            $to = request('acc_to') ? strtolower(request('acc_to')) : null;
                            $code = strtolower($account->code ?? '');
                            if($from && $code < $from) return false;
                            if($to && $code > $to) return false;

                            return $items->count() > 0 || request('search');
                        });

                        if($filteredAccounts->isEmpty()) continue;
                        $subtotalDebit = $subtotalCredit = $subtotalBalance = 0;
                    @endphp

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden class-block">
                        <div class="bg-gradient-to-r from-indigo-100 via-blue-100 to-indigo-200 p-4 border-b border-gray-300">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $class->code }} — {{ $class->name_en }} / {{ $class->name_ar }}</h3>
                        </div>

                        <div class="overflow-x-auto table-container">
                            <table class="w-full border-collapse">
                                <thead class="bg-gray-50 sticky top-0 z-10 text-sm text-gray-600">
                                    <tr>
                                        <th class="p-3 text-left">Code</th>
                                        <th class="p-3 text-left">Account</th>
                                        <th class="p-3 text-right">Debit</th>
                                        <th class="p-3 text-right">Credit</th>
                                        <th class="p-3 text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700 text-sm">
                                    @foreach($filteredAccounts as $idx => $account)
                                        @php
                                            $items = $account->journalItems;
                                            if(request('from')) $items = $items->filter(fn($i)=>$i->voucher && $i->voucher->date >= request('from'));
                                            if(request('to'))   $items = $items->filter(fn($i)=>$i->voucher && $i->voucher->date <= request('to'));
                                            $debit = (float)$items->sum('debit');
                                            $credit = (float)$items->sum('credit');
                                            $balance = $debit - $credit;
                                            $subtotalDebit += $debit; $subtotalCredit += $credit; $subtotalBalance += $balance;
                                            $grandDebit += $debit; $grandCredit += $credit; $grandBalance += $balance;
                                        @endphp

                                        <tr class="hover:bg-blue-50 cursor-pointer transition account-row"
                                            data-id="{{ $account->id }}"
                                            data-code="{{ strtolower($account->code ?? '') }}"
                                            data-name="{{ strtolower(($account->name_en ?? '').' / '.($account->name_ar ?? '')) }}"
                                            data-debit="{{ $debit }}" data-credit="{{ $credit }}" data-balance="{{ $balance }}"
                                            onclick="document.getElementById('acc{{ $account->id }}').classList.toggle('hidden')">
                                            <td class="p-3 font-mono code-cell" data-original="{{ $account->code }}">{{ $account->code }}</td>
                                            <td class="p-3 name-cell" data-original="{{ $account->name_en }} / {{ $account->name_ar }}">{{ $account->name_en }} / {{ $account->name_ar }}</td>
                                            <td class="p-3 text-right text-green-700 font-medium">{{ number_format($debit,2) }}</td>
                                            <td class="p-3 text-right text-red-700 font-medium">{{ number_format($credit,2) }}</td>
                                            <td class="p-3 text-right font-semibold {{ $balance >=0?'text-green-800':'text-red-800' }}">{{ number_format($balance,2) }}</td>
                                        </tr>

                                        <tr id="acc{{ $account->id }}" class="hidden bg-gray-50 detail-row">
                                            <td colspan="5" class="p-3">
                                                <table class="w-full ml-4 border-l-4 border-blue-300 text-xs">
                                                    <thead><tr class="bg-gray-100 text-gray-600"><th>JV No.</th><th>Date</th><th>Description</th><th class="text-right">Debit</th><th class="text-right">Credit</th></tr></thead>
                                                    <tbody>
                                                        @foreach($items as $item)
                                                            <tr class="hover:bg-gray-100 cursor-pointer" onclick="openJV('{{ url('/docs/journal_vouchers/'.$item->journal_voucher_id) }}')">
                                                                <td>{{ $item->voucher->number ?? '' }}</td>
                                                                <td>{{ $item->voucher->date ?? '' }}</td>
                                                                <td>{{ $item->description }}</td>
                                                                <td class="text-right">{{ number_format($item->debit,2) }}</td>
                                                                <td class="text-right">{{ number_format($item->credit,2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr class="bg-blue-50 font-bold border-t border-blue-200 class-subtotal">
                                        <td colspan="2" class="p-3 text-right">Subtotal ({{ $class->name_en }} / {{ $class->name_ar }})</td>
                                        <td class="p-3 text-right text-green-700 subtotal-debit">{{ number_format($subtotalDebit,2) }}</td>
                                        <td class="p-3 text-right text-red-700 subtotal-credit">{{ number_format($subtotalCredit,2) }}</td>
                                        <td class="p-3 text-right text-blue-800 subtotal-balance">{{ number_format($subtotalBalance,2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden" id="grandTotals">
                    <div class="bg-gradient-to-r from-green-100 to-green-200 p-4 border-b border-gray-300">
                        <h3 class="text-lg font-semibold text-gray-800">TOTAL BALANCE</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <tbody>
                                <tr class="bg-green-50 font-bold">
                                    <td colspan="2" class="p-3 text-right">Grand Total</td>
                                    <td class="p-3 text-right text-green-700" id="grandDebit">{{ number_format($grandDebit,2) }}</td>
                                    <td class="p-3 text-right text-red-700" id="grandCredit">{{ number_format($grandCredit,2) }}</td>
                                    <td class="p-3 text-right text-blue-800" id="grandBalance">{{ number_format($grandBalance,2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <aside id="rightPane" class="hidden md:block w-0 transition-all duration-300 pane-shadow bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-2 border-b bg-gray-50">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-gray-700">Journal Voucher</span>
                    <a id="openNewTabLink" href="#" target="_blank" class="text-xs text-blue-600 hover:underline hidden md:inline">Open in new tab ↗</a>
                </div>
                <button onclick="closePane()" class="rounded-lg px-2 py-1 text-gray-600 hover:text-red-600 focus:outline-none" title="Close">✕</button>
            </div>
            <div id="paneLoader" class="flex items-center justify-center py-8">
                <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="ml-3 text-sm text-gray-500">Loading voucher…</span>
            </div>
            <iframe id="jvFrame" src="about:blank" class="w-full border-0" style="height: calc(100vh - 12rem);" loading="lazy"></iframe>
        </aside>
    </div>
</div>

<script>
function fmt(n){return (Math.round(n*100)/100).toFixed(2);}
function highlightText(text,q){if(!q)return text;try{const re=new RegExp('('+q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+')','ig');return text.replace(re,'<mark>$1</mark>');}catch(e){return text;}}

(function(){
    const input = document.getElementById('liveSearch');
    const classBlocks = Array.from(document.querySelectorAll('.class-block'));

    function scoreRow(row,q){
        if(!q)return {score:0,exact:false,match:false};
        const code=row.dataset.code||'', name=row.dataset.name||'';
        const exact = code===q || name===q;
        const codePref = code.startsWith(q), namePref=name.startsWith(q);
        const contains = code.includes(q)||name.includes(q); let s=0;
        if(exact) s=3; else if(codePref) s=2.5; else if(namePref) s=2; else if(contains) s=1;
        return {score:s, exact, match:(s>0)};
    }

    function applySearch(){
        const q=(input.value||'').trim().toLowerCase();
        let grandD=0, grandC=0, grandB=0;

        classBlocks.forEach(block=>{
            const tbody=block.querySelector('tbody');
            const accRows=Array.from(tbody.querySelectorAll('tr.account-row'));
            const detailRows=Array.from(tbody.querySelectorAll('tr.detail-row'));
            accRows.forEach(r=>{const c=r.querySelector('.code-cell');const n=r.querySelector('.name-cell');if(c?.dataset.original)c.innerHTML=c.dataset.original;if(n?.dataset.original)n.innerHTML=n.dataset.original;});

            if(!q){accRows.forEach(r=>r.classList.remove('hidden'));detailRows.forEach(d=>d.classList.add('hidden'));} 
            else{
                const scored=accRows.map(r=>({...scoreRow(r,q),row:r})).filter(x=>x.match);
                scored.sort((a,b)=>b.score-a.score);
                accRows.forEach(r=>r.classList.add('hidden')); detailRows.forEach(d=>d.classList.add('hidden'));
                scored.forEach(({row})=>{row.classList.remove('hidden'); const det=tbody.querySelector(`tr.detail-row[data-parent-id="${row.dataset.id}"]`); if(det) tbody.appendChild(det); const c=row.querySelector('.code-cell'); const n=row.querySelector('.name-cell'); if(c?.dataset.original)c.innerHTML=highlightText(c.dataset.original,q); if(n?.dataset.original)n.innerHTML=highlightText(n.dataset.original,q);});
            }

            let cD=0,cC=0,cB=0,vis=0;
            accRows.forEach(r=>{if(r.classList.contains('hidden')) return; vis++; cD+=parseFloat(r.dataset.debit||0);cC+=parseFloat(r.dataset.credit||0);cB+=parseFloat(r.dataset.balance||0);});
            const subRow = tbody.querySelector('tr.class-subtotal');
            if(subRow){subRow.querySelector('.subtotal-debit').textContent=fmt(cD);subRow.querySelector('.subtotal-credit').textContent=fmt(cC);subRow.querySelector('.subtotal-balance').textContent=fmt(cB);}
            if(vis===0) block.classList.add('hidden'); else block.classList.remove('hidden');
            grandD+=cD; grandC+=cC; grandB+=cB;
        });
        document.getElementById('grandDebit').textContent=fmt(grandD);
        document.getElementById('grandCredit').textContent=fmt(grandC);
        document.getElementById('grandBalance').textContent=fmt(grandB);
    }

    input.addEventListener('input',applySearch);
    applySearch();
})();

const leftCol=document.getElementById('leftCol');
const rightPane=document.getElementById('rightPane');
const jvFrame=document.getElementById('jvFrame');
const loader=document.getElementById('paneLoader');
const openNewTabLink=document.getElementById('openNewTabLink');

function openJV(rawUrl){
    const url = rawUrl + (rawUrl.includes('?') ? '&' : '?') + 'embed=1';
    leftCol.classList.remove('w-full'); leftCol.classList.add('md:w-2/3');
    rightPane.classList.remove('hidden'); rightPane.classList.add('md:w-1/3');
    openNewTabLink.href = rawUrl; openNewTabLink.classList.remove('hidden');
    loader.classList.remove('hidden'); jvFrame.classList.add('opacity-0'); jvFrame.src = url;
}
function closePane(){leftCol.classList.remove('md:w-2/3');leftCol.classList.add('w-full');rightPane.classList.add('hidden');rightPane.classList.remove('md:w-1/3'); jvFrame.src='about:blank'; loader.classList.add('hidden'); openNewTabLink.classList.add('hidden');}
jvFrame.addEventListener('load',()=>{loader.classList.add('hidden'); jvFrame.classList.remove('opacity-0');});
document.addEventListener('keydown',(e)=>{if(e.key==='Escape') closePane();});
</script>
</body>
</html>
<style>
    td {
    padding: 5px;
}
tr {text-align:left !important;padding:5px;}
</style>