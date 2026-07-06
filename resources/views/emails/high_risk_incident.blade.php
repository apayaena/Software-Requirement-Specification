<x-mail::message>
# Peringatan: Insiden Risiko Tinggi / Kecelakaan

Telah dilaporkan sebuah insiden dengan klasifikasi **High Risk** atau **Accident**.

**Nomor Tiket:** {{ $incident->ticket_number }}
**Kategori:** {{ $incident->category }}
**Tingkat Keparahan:** {{ $incident->severity }}
**Status:** {{ $incident->status }}

Silakan segera periksa detail insiden di sistem dan lakukan tindakan penanggulangan (CAPA).

<x-mail::button :url="route('dashboard')">
Lihat Dashboard
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
