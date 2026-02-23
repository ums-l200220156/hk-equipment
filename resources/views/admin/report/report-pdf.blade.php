<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan HK Equipment</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h2 { text-align:center; margin-bottom:5px; }
        table { width:100%; border-collapse: collapse; margin-top:10px; }
        th, td { border:1px solid #ddd; padding:6px; }
        th { background:#f3f4f6; }
        .right { text-align:right; }
        .green { color:green; font-weight:bold; }
        .red { color:red; font-weight:bold; }
        tr.total-row td {
            background: #f9fafb;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>LAPORAN BULANAN HK EQUIPMENT</h2>
<p style="text-align:center">{{ $month_name }}</p>

<hr>

<h4>RINGKASAN KEUANGAN</h4>
<table>
<tr><td>Total Pemasukan (Transaksi + Overtime)</td><td class="right">Rp {{ number_format($totalIncome) }}</td></tr>
<tr><td>Total Pengeluaran</td><td class="right">Rp {{ number_format($totalExpense) }}</td></tr>
<tr>
<td><b>Profit</b></td>
<td class="right {{ $profit >=0 ? 'green':'red' }}">
Rp {{ number_format($profit) }}
</td>
</tr>
</table>

<h4>TRANSAKSI SELESAI</h4>
<table>
<tr>
<th>User</th>
<th>Unit</th>
<th>Tanggal</th>
<th>Total</th>
</tr>
@foreach($rentalDetails as $r)
<tr>
<td>{{ $r->user->name ?? '-' }}</td>
<td>{{ $r->equipment->name ?? '-' }}</td>
<td>
{{ \Carbon\Carbon::parse($r->rent_date)
    ->timezone('Asia/Jakarta')
    ->format('d/m/Y') }}
</td>
<td class="right">Rp {{ number_format($r->total_price) }}</td>
</tr>
@endforeach
@php
    $totalRental = $rentalDetails->sum('total_price');
@endphp

<tr>
    <td colspan="3" class="right"><b>TOTAL</b></td>
    <td class="right"><b>Rp {{ number_format($totalRental) }}</b></td>
</tr>
</table>

<h4>OVERTIME</h4>
<table>
<tr>
<th>User</th>
<th>Tanggal</th>
<th>Harga</th>
</tr>
@foreach($overtimeDetails as $o)
<tr>
<td>{{ $o->rental->user->name ?? '-' }}</td>
<td>
{{ \Carbon\Carbon::parse($o->created_at)
    ->timezone('Asia/Jakarta')
    ->format('d/m/Y H:i') }}
</td>
<td class="right">Rp {{ number_format($o->price) }}</td>
</tr>
@endforeach
@php
    $totalOvertime = $overtimeDetails->sum('price');
@endphp

<tr>
    <td colspan="2" class="right"><b>TOTAL</b></td>
    <td class="right"><b>Rp {{ number_format($totalOvertime) }}</b></td>
</tr>
</table>

<h4>PENGELUARAN</h4>
<table>
<tr>
<th>Tanggal</th>
<th>Kategori</th>
<th>Deskripsi</th>
<th>Nominal</th>
</tr>
@foreach($expenseDetails as $e)
<tr>
<td>{{ $e->transaction_date->format('d/m/Y') }}</td>
<td>{{ $e->category }}</td>
<td>{{ $e->description }}</td>
<td class="right">Rp {{ number_format($e->amount) }}</td>
</tr>
@endforeach
@php
    $totalExpenseDetail = $expenseDetails->sum('amount');
@endphp

<tr>
    <td colspan="3" class="right"><b>TOTAL</b></td>
    <td class="right"><b>Rp {{ number_format($totalExpenseDetail) }}</b></td>
</tr>
</table>

<h4>STATISTIK</h4>
<table>
<tr><td>Transaksi Selesai</td><td>{{ $completed }}</td></tr>
<tr><td>Bulan Ini</td><td>{{ $thisMonth }}</td></tr>
<tr><td>Bulan Lalu</td><td>{{ $lastMonth }}</td></tr>
<tr>
<td>Growth</td>
<td class="{{ $growth >=0 ? 'green':'red' }}">
{{ $growth }}%
</td>
</tr>
</table>

</body>
</html>