@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pilih Alat untuk Dibandingkan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{background:#f1f5f9}
.card-eq{
    border-radius:14px;
    cursor:pointer;
    transition:.25s;
    border:2px solid transparent;
    position:relative;
}
.card-eq:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(0,0,0,.12)}
.card-eq.selected{
    border-color:#2563eb;
    box-shadow:0 0 0 2px rgba(37,99,235,.2);
}
.card-img-top{height:170px;object-fit:cover}
.selected-mark{
    position:absolute;
    top:10px;right:10px;
    background:#2563eb;
    color:#fff;
    width:28px;height:28px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    display:none;
}
.card-eq.selected .selected-mark{display:flex}
.badge-status{
    position:absolute;left:10px;top:10px;
    font-size:11px;padding:4px 10px;border-radius:999px
}
.available{background:#dcfce7;color:#15803d}
.rented{background:#fee2e2;color:#b91c1c}

.compare-bar{
    position:fixed;bottom:0;left:0;right:0;
    background:#fff;
    box-shadow:0 -6px 20px rgba(0,0,0,.1);
    padding:12px 20px;
    display:none;
    z-index:999;
}
</style>
</head>
<body>

<div class="container py-4 mb-5">

<h3 class="mb-3">🔍 Pilih Alat untuk Dibandingkan</h3>

<!-- SEARCH & FILTER -->
<div class="row mb-4">
    <div class="col-md-4 mb-2">
        <input id="searchInput" class="form-control" placeholder="Cari nama alat...">
    </div>
    <div class="col-md-4 mb-2">
        <select id="categoryFilter" class="form-control">
            <option value="">Semua Kategori</option>
            @foreach($equipment->pluck('category')->unique() as $cat)
                <option value="{{ strtolower($cat) }}">{{ ucfirst($cat) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-2">
        <select id="statusFilter" class="form-control">
            <option value="">Semua Status</option>
            <option value="available">Tersedia</option>
            <option value="rented">Tidak Tersedia</option>
        </select>
    </div>
</div>

<form id="compareForm" method="POST" action="{{ route('customer.compare.result') }}">
@csrf
<input type="hidden" name="items" id="compareItems">

<div class="row" id="equipmentGrid">
@foreach($equipment as $item)
<div class="col-md-4 col-lg-3 mb-4 eq-item"
     data-name="{{ strtolower($item->name) }}"
     data-category="{{ strtolower($item->category) }}"
     data-status="{{ $item->status }}"
     data-id="{{ $item->id }}">

<div class="card card-eq h-100">
    <span class="selected-mark">✓</span>
    <span class="badge-status {{ $item->status === 'available' ? 'available' : 'rented' }}">
        {{ $item->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
    </span>

    <img src="{{ asset('uploads/equipment/'.$item->image) }}" class="card-img-top">

    <div class="card-body">
        <h6 class="fw-bold mb-1">{{ $item->name }}</h6>
        <div class="small text-muted">{{ ucfirst($item->category) }}</div>
        <div class="fw-semibold text-success mt-1">
            Rp {{ number_format($item->price_per_hour) }} / jam
        </div>
        <div class="small text-muted mt-2">
            {{ Str::limit($item->description, 60) }}
        </div>
    </div>
</div>
</div>
@endforeach
</div>
</form>
</div>

<!-- STICKY COMPARE BAR -->
<div class="compare-bar d-flex justify-content-between align-items-center">
    <div>
        <strong><span id="compareCount">0</span></strong> alat dipilih
    </div>
    <button id="compareBtn" class="btn btn-primary" disabled>
        Bandingkan Sekarang
    </button>
</div>

<script>
const cards = document.querySelectorAll('.eq-item');
const selected = new Set();
const bar = document.querySelector('.compare-bar');
const countEl = document.getElementById('compareCount');
const btn = document.getElementById('compareBtn');
const input = document.getElementById('compareItems');

cards.forEach(card=>{
    card.addEventListener('click',()=>{
        const id = card.dataset.id;
        const c = card.querySelector('.card-eq');

        if(selected.has(id)){
            selected.delete(id);
            c.classList.remove('selected');
        }else{
            selected.add(id);
            c.classList.add('selected');
        }

        countEl.innerText = selected.size;
        bar.style.display = selected.size ? 'flex' : 'none';
        btn.disabled = selected.size < 2;
        input.value = [...selected].join(',');
    });
});

btn.addEventListener('click',()=>document.getElementById('compareForm').submit());

/* SEARCH & FILTER */
const search=document.getElementById('searchInput');
const cat=document.getElementById('categoryFilter');
const status=document.getElementById('statusFilter');

function filter(){
    cards.forEach(i=>{
        const ok =
            i.dataset.name.includes(search.value.toLowerCase()) &&
            (!cat.value || i.dataset.category===cat.value) &&
            (!status.value || i.dataset.status===status.value);
        i.style.display = ok ? 'block' : 'none';
    });
}
[search,cat,status].forEach(el=>el.addEventListener('input',filter));
</script>

</body>
</html>
