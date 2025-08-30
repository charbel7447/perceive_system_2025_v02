<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Custom Dashboard Layout</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- TailwindCSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Fonts / Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<!-- GridStack -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridstack@10.1.2/dist/gridstack.min.css" />
<script src="https://cdn.jsdelivr.net/npm/gridstack@10.1.2/dist/gridstack-all.js"></script>

<!-- Vue 3 + Axios -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
<style>
.grid-stack { background: #f9fafb; }
.grid-stack-item-content {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  box-shadow: 0 1px 2px rgb(0 0 0 / 6%), 0 1px 3px rgb(0 0 0 / 10%);
  display: flex; flex-direction: column;
  overflow: hidden;
}
 body { font-family: Lato !important; background-color: #f9fafb; }
.widget-header { display:flex; justify-content:space-between; align-items:center;
  padding: 10px 14px; background:#f3f4f6; border-bottom:1px solid #e5e7eb;}
.widget-title { font-weight:600; font-size:14px; color:#111827; }
.widget-body { flex:1; overflow:auto; padding:14px; }
.handle { cursor:move; color:#6b7280; }
.toolbar .btn { @apply inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white shadow; }
.btn-primary { background:#2563eb; } .btn-success{background:#059669;} .btn-neutral{background:#374151;}
.btn-primary:hover{background:#1d4ed8;} .btn-success:hover{background:#047857;} .btn-neutral:hover{background:#111827;}
</style>
</head>
<body class="bg-gray-100">
<div id="app" class="p-5">
  <div class="max-w-[1400px] mx-auto bg-white border border-gray-200 rounded-2xl shadow-sm">
    <!-- Toolbar -->
    <div class="toolbar flex justify-between items-center p-5 border-b border-gray-200">
  <h1 class="text-xl font-bold text-gray-800">Custom Dashboard Layout</h1>
  <div class="flex gap-2">

  <a style="background:#000;" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl   text-white shadow transition"
          href="{{url('/')}}/dashboard/custom_layout/{{$dashboard_id}}/delete">
      <i class="fa-solid fa-plus"></i> Delete Dashboard
    </a>

    <!-- Add Widget Button -->
    <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white shadow transition"
            @click="openDrawer">
      <i class="fa-solid fa-plus"></i> Add Widget
    </button>

    <!-- Save Layout Button -->
    <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow transition"
            @click="saveLayout">
      <i class="fa-solid fa-floppy-disk"></i> Save
    </button>

    <!-- Export PDF Button -->
    <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white shadow transition"
            @click="exportPdf">
      <i class="fa-solid fa-file-pdf"></i> Export PDF
    </button>
  </div>
</div>
    <!-- Grid -->
    <div class="p-5">
      <div id="grid" class="grid-stack"></div>
    </div>
  </div>

  <!-- Drawer -->
  <div v-show="drawerOpen" class="fixed inset-0 z-40">
    <div class="absolute inset-0 bg-black/40" @click="closeDrawer"></div>
    <div class="absolute right-0 top-0 h-full w-full sm:w-[480px] bg-white shadow-xl p-5 overflow-y-auto">
      <h2 class="text-lg font-bold mb-3">Add Widget</h2>
      <input v-model="search" type="text" placeholder="Search…" class="w-full mb-3 border rounded px-3 py-2">
      <table class="min-w-full border">
        <tbody>
          <tr v-for="w in filteredWidgets" :key="w.id" class="border-t">
            <td class="px-3 py-2">@{{ w.name }}</td>
            <td class="px-3 py-2">@{{ w.description }}</td>
            <td class="px-3 py-2" style="background: #047857;"><button style="color: #fff;" class="text-white-600" @click="addWidget(w)">+</button></td>
          </tr>
        </tbody>
      </table>
      <button @click="closeDrawer" class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white shadow transition">
      <i class="fa fa-check"></i> Done</button>
    </div>
  </div>
</div>

<script>
const { createApp } = Vue;

createApp({
data() {
  return {
    grid: null,
    drawerOpen: false,
    search: '',
    library: [],
    user_dashboard_id: '{{ $dashboard_id ?? 0 }}',
  };
},
computed: {
  filteredWidgets() {
    return this.search
      ? this.library.filter(w => w.name.toLowerCase().includes(this.search.toLowerCase()))
      : this.library;
  }
},
mounted() {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
  this.grid = GridStack.init({ column: 12, cellHeight: 120, float: true, animate: true, draggable: { handle: '.handle'} }, '#grid');
  this.loadLibrary();
  this.loadLayout();
},
methods: {
  openDrawer(){this.drawerOpen=true}, closeDrawer(){this.drawerOpen=false},
    resetZoom() {
    // Set default cellHeight
    this.grid.cellHeight(120); // or your desired default
    // Refresh grid styles
    this.grid.update(); 
  },
  async loadLibrary() {
    const res = await axios.get('/dashboard/widgets');
    this.library = res.data.widgets || [];
  },

  widgetHtml(w, widgetId) {
    return `
      <div class="widget-header">
        <div><i class="fa fa-grip-lines handle"></i> <span class="widget-title">${w.name}</span></div>
        <button data-action="remove" class="text-red-500"><i class="fa fa-trash"></i></button>
      </div>
      <div class="widget-body" data-widget-id="${widgetId}">Loading...</div>
    `;
  },

  bindWidget(el, widgetId) {
    el.querySelector('[data-action="remove"]').addEventListener('click', () => this.grid.removeWidget(el));
    const targetEl = el.querySelector(`.widget-body[data-widget-id="${widgetId}"]`);
    if(targetEl) this.loadWidgetData(widgetId, targetEl);
  },

  async loadWidgetData(widgetId, targetEl) {
    try {
      const res = await axios.get(`/dashboard/custom/widget/${widgetId}`);
      const data = res.data;

      if(data.type === 'table'){
        let html = '<table class="w-full text-sm border"><thead><tr>';
        (data.columns || []).forEach(c => html += `<th class="border px-2 py-1">${c}</th>`);
        html += '</tr></thead><tbody>';
        (data.rows || []).forEach(r => {
          html += '<tr>';
          (data.columns || []).forEach(col => {
            let key = col.toLowerCase().replace(/\s+/g, '_');
            let value = r[key] ?? '';
            if(typeof value === 'object' && value.text) value = value.text;
            html += `<td class="border px-2 py-1" style="text-align:center">${value}</td>`;
          });
          html += '</tr>';
        });
        html += '</tbody></table>';
        targetEl.innerHTML = html;

      } else if(data.type === 'chart'){
        targetEl.innerHTML = `<canvas id="chart-${widgetId}"></canvas>`;
        new Chart(document.getElementById(`chart-${widgetId}`), data.chart);

      } else {
        targetEl.innerHTML = data.html || 'No data';
      }

    } catch(e) {
      console.error(e);
      targetEl.innerHTML = 'Failed to load';
    }
  },

  addWidget(w) {
    const el = this.grid.addWidget({w:4,h:3,content:this.widgetHtml(w,w.id)});
    el.dataset.widgetId = w.id;
    this.bindWidget(el, w.id);
  },

  async saveLayout() {
    const nodes = this.grid.engine.nodes.map(n=>({lib_id:n.el.dataset.widgetId,x:n.x,y:n.y,w:n.w,h:n.h}));
    await axios.post('/dashboard/custom-layout/save',{dashboard_id:this.user_dashboard_id,layout:nodes});
    alert('Saved!');
  },

  async loadLayout() {
    const res = await axios.get(`/dashboard/custom-layout/${this.user_dashboard_id}`);
    this.grid.removeAll();
    (res.data.layout||[]).forEach(item=>{
      const lib=this.library.find(l=>l.id==item.lib_id)||{name:'Widget', id: item.lib_id};
      const el=this.grid.addWidget({x:item.x,y:item.y,w:item.w,h:item.h,content:this.widgetHtml(lib, item.lib_id)});
      el.dataset.widgetId=item.lib_id;
      this.bindWidget(el,item.lib_id);
    });
  },

  exportPdf() {
    // Redirect to a Laravel route that generates PDF of the current dashboard layout
    const url = `/dashboard/custom-layout/${this.user_dashboard_id}/export-pdf`;
    window.open(url, '_blank');
  }
}
}).mount('#app');
</script>
</body>
</html>
