<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sewa Alat – {{ $equipment->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('assets/css/customer/rent/create.css') }}">
</head>
<body>

<div class="bg-overlay"></div> <div class="page-wrapper page-animate">
    <div class="top-nav">
        <button type="button"
                    class="btn btn-back-modern"
                    onclick="if (history.length > 1) { history.back(); } else { window.location.href='{{ route('customer.catalog') }}'; }">
                <i class="bi bi-arrow-left-short"></i>
                Kembali
            </button>
    </div>

    <div class="content-grid">
        <aside class="equipment-card">
            <div class="image-container">
                @if($equipment->image)
                    <img src="{{ asset('uploads/equipment/'.$equipment->image) }}" alt="{{ $equipment->name }}">
                @endif
                <div class="category-badge">{{ ucfirst($equipment->category) }}</div>
            </div>

            <div class="info-body">
                <span class="status-tag available">
                    <i class="fas fa-check-circle"></i> Tersedia
                </span>
                <h3>{{ $equipment->name }}</h3>
                
                <div class="price-tag">
                    <span class="currency">Rp</span>
                    <span class="amount">{{ number_format($equipment->price_per_hour) }}</span>
                    <span class="unit">/ jam</span>
                </div>

                <hr>
                
                <ul class="equipment-features">
                    <li><i class="fas fa-shield-alt"></i> Asuransi Alat Terjamin</li>
                    <li><i class="fas fa-tools"></i> Termasuk Truck Pengangkut</li>
                    <li><i class="fas fa-user-check"></i> Termasuk Operator</li>
                </ul>
            </div>
        </aside>

        <section class="form-container">
            <div class="form-header">
                <h2>Konfirmasi Penyewaan</h2>
                <p>Silakan isi detail durasi dan lokasi proyek Anda.</p>
            </div>

            <form method="POST" action="{{ route('rent.store', $equipment->id) }}" class="styled-form">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group-custom">
                            <label><i class="fas fa-calendar-alt"></i> Tanggal Sewa</label>
                            <input type="date" name="rent_date" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group-custom">
                            <label><i class="fas fa-clock"></i> Jam Mulai</label>

                            <input
                                type="text"
                                name="start_time"
                                id="start_time"
                                list="time-options"
                                placeholder="HH:MM (24 jam)"
                                maxlength="5"
                                inputmode="numeric"
                                autocomplete="off"
                                required
                            >

                            <datalist id="time-options">
                                <option value="06:00">
                                <option value="07:00">
                                <option value="08:00">
                                <option value="09:00">
                                <option value="10:00">
                                <option value="11:00">
                                <option value="12:00">
                                <option value="13:00">
                                <option value="14:00">
                                <option value="15:00">
                                <option value="16:00">
                                <option value="17:00">
                                <option value="18:00">
                                <option value="19:00">
                                <option value="20:00">
                                <option value="21:00">
                                <option value="22:00">
                                <option value="23:00">
                                <option value="00:00">
                                <option value="01:00">
                                <option value="02:00">
                                <option value="03:00">
                                <option value="04:00">
                                <option value="05:00">
                            </datalist>

                            <!-- 🔴 ERROR INLINE -->
                            <small class="time-error d-none">
                                Format jam tidak valid. Contoh: 21:00
                            </small>

                            <small class="time-hint">
                                Ketik atau pilih jam (format 24 jam)
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <div class="input-group-custom">
                            <label><i class="fas fa-hourglass-half"></i> Durasi (Jam)</label>
                            <input type="number" name="duration_hours" id="duration_input" min="1" placeholder="Contoh: 8" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group-custom highlight">
                            <label><i class="fas fa-money-bill-wave"></i> Estimasi Total</label>
                            <input type="text" id="totalPrice" readonly value="Rp 0">
                        </div>
                    </div>
                </div>

                <div class="input-group-custom mt-3">
                    <label><i class="fas fa-map-marker-alt"></i> Lokasi Proyek</label>
                    <input type="text" name="location" placeholder="Alamat lengkap lokasi pengerjaan" required>
                </div>

                <div class="input-group-custom mt-3">
                    <label><i class="fas fa-sticky-note"></i> Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="3" placeholder="Contoh: Butuh operator tambahan..."></textarea>
                </div>

                <button type="submit" class="btn-main-submit">
                    <span>Proses Penyewaan</span>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </form>
        </section>
    </div>
</div>

<script>
    const PRICE_PER_HOUR = {{ $equipment->price_per_hour }};
</script>
<script src="{{ asset('assets/js/customer/rent/create.js') }}"></script>

</body>
</html>