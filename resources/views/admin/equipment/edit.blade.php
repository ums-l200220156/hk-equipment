<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat Berat</title>
    <style>
        /* Reset & base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-primary {
            background: #007bff;
            color: #fff;
            border: none;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #444;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 6px rgba(0,123,255,0.3);
        }

        img {
            border-radius: 8px;
            margin-top: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        textarea {
            resize: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Alat Berat</h2>

        <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary mb-3">Kembali</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi Kesalahan!</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.equipment.update', $equipment->id) }}" enctype="multipart/form-data">
            @csrf

            <label class="form-label">Nama Alat</label>
            <input type="text" name="name" value="{{ $equipment->name }}" class="form-control" required>

            <label class="form-label">Kategori</label>
            <select name="category" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Excavator" {{ $equipment->category == 'Excavator' ? 'selected' : '' }}>Excavator</option>
                <option value="Bulldozer" {{ $equipment->category == 'Bulldozer' ? 'selected' : '' }}>Bulldozer</option>
                <option value="Crane" {{ $equipment->category == 'Crane' ? 'selected' : '' }}>Crane</option>
                <option value="Forklift" {{ $equipment->category == 'Forklift' ? 'selected' : '' }}>Forklift</option>
                <option value="Dump Truck" {{ $equipment->category == 'Dump Truck' ? 'selected' : '' }}>Dump Truck</option>
                <option value="Vibro Roller" {{ $equipment->category == 'Vibro Roller' ? 'selected' : '' }}>Vibro Roller</option>
            </select>

            <label class="form-label">Harga Sewa per Jam</label>
            <input type="number" name="price_per_hour" value="{{ $equipment->price_per_hour }}" class="form-control" required>

            <label class="form-label">Tahun</label>
            <input type="number" name="year" value="{{ $equipment->year }}" class="form-control">

            <label class="form-label">Merk</label>
            <input type="text" name="brand" value="{{ $equipment->brand }}" class="form-control">

            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ $equipment->description }}</textarea>

            <label class="form-label">Foto Lama</label><br>
            @if ($equipment->image)
                <img src="{{ asset('uploads/equipment/'.$equipment->image) }}" width="150">
            @endif

            <label class="form-label">Ganti Foto (Opsional)</label>
            <input type="file" name="image" class="form-control">

            <button class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>