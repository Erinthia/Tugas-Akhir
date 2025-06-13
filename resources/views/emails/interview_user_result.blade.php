<!DOCTYPE html>
<html>

<head>
    <title>Hasil Interview User</title>
</head>

<body>
    <h2>Halo {{ $applicant->fullname }},</h2>

    @if ($result === 'lolos')
    <p>Selamat! Kamu dinyatakan <strong>lolos</strong> dalam tahap <strong>Interview User</strong>.</p>
    <p>Kami akan menghubungi kamu untuk tahapan selanjutnya.</p>
    @else
    <p>Terima kasih telah melamar. Setelah nilai interview user kamu, kami memutuskan kamu <strong>belum
            lolos</strong>
        pada
        tahap ini.</p>
    <p>Semangat terus, dan semoga sukses untuk kesempatan berikutnya!</p>
    @endif

    <br>
    <p>Salam,</p>
    <p>Tim HRD CareerApp</p>
</body>

</html>