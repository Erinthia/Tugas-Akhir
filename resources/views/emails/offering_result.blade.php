<!DOCTYPE html>
<html>

<head>
    <title>Hasil Offering</title>
</head>

<body>
    <h2>Halo {{ $applicant->fullname }},</h2>

    <p>Selamat! Kamu dinyatakan <strong>lolos</strong> dalam tahap terakhir <strong>Interview User</strong>.</p>
    <p>Berikut adalah penawaran (offering) yang kami sampaikan:</p>

    <ul>
        <li><strong>Benefit:</strong> {{ $Offering->benefit }}</li>
        <li><strong>Hasil Seleksi:</strong> {{ $Offering->selection_result }}</li>
        <li><strong>Deadline Konfirmasi:</strong>
            {{ \Carbon\Carbon::parse($Offering->deadline_offering)->format('d M Y') }}</li>
        <li><strong>Catatan Hasil Offering:</strong> {{ $Offering->offering_result }}</li>
    </ul>

    <p>Jika kamu <strong>setuju</strong> dengan tawaran ini, silakan balas email ini sebelum tanggal yang ditentukan.
    </p>
    <p>Jika ada pertanyaan, jangan ragu untuk menghubungi tim HR kami.</p>

    <br>
    <p>Salam,</p>
    <p>Tim HRD CareerApp</p>
</body>

</html>
