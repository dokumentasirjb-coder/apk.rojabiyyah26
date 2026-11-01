<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Bukti Peminjaman - {{ $loan->loan_no }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 11px;
            color: #475569;
            white-space: pre-line;
        }
        .title-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-box h3 {
            margin: 0;
            font-size: 14px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title-box .loan-num {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .meta-table td.label {
            width: 25%;
            font-weight: bold;
            color: #334155;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table-items th, .table-items td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }
        .table-items th {
            background-color: #f1f5f9;
            font-weight: 600;
            color: #0f172a;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            background: #e2e8f0;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            margin-top: 10px;
        }
        .signature-box {
            text-align: center;
            width: 33%;
            vertical-align: top;
        }
        .signature-space {
            height: 70px;
        }
        .qr-section {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
        }
        .qr-text {
            font-size: 10px;
            color: #64748b;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        @php
            $kopLogo = \App\Models\Setting::get('kop_logo_url') ?: \App\Models\Setting::get('logo_url');
        @endphp
        @if(!empty($kopLogo))
            <div style="margin-bottom: 8px;">
                <img src="{{ $kopLogo }}" style="max-height: 55px; max-width: 140px; object-fit: contain;" alt="Logo" />
            </div>
        @endif
        <h2>{{ $appName }}</h2>
        <p>{{ $subTheme }}</p>
        <p>{!! nl2br(e($letterhead)) !!}</p>
    </div>

    <div class="title-box">
        <h3>SURAT BUKTI PEMINJAMAN BARANG</h3>
        <div class="loan-num">Nomor: {{ $loan->loan_no }}</div>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Nama Peminjam</td>
            <td>: {{ $loan->borrower->name }} ({{ $loan->borrower->member_no ?? 'RJB' }})</td>
            <td class="label">Status</td>
            <td>: <span class="status-badge">{{ strtoupper($loan->status) }}</span></td>
        </tr>
        <tr>
            <td class="label">Divisi / Posko</td>
            <td>: {{ $loan->borrower->member->division->name ?? 'Panitia Umum' }}</td>
            <td class="label">Tanggal Pinjam</td>
            <td>: {{ $loan->requested_at ? $loan->requested_at->format('d/m/Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Keperluan</td>
            <td>: {{ $loan->purpose }}</td>
            <td class="label">Rencana Kembali</td>
            <td>: {{ $loan->due_at ? $loan->due_at->format('d/m/Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kontak / No. WA</td>
            <td>: {{ $loan->borrower->phone_wa ?? '-' }}</td>
            <td class="label">Petugas Verifikasi</td>
            <td>: {{ $loan->approver->name ?? 'Admin Logistik' }}</td>
        </tr>
    </table>

    <table class="table-items">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 20%;">Kode Barang</th>
                <th style="width: 45%;">Nama Barang & Spesifikasi</th>
                <th style="width: 15%; text-align: center;">Jml Disetujui</th>
                <th style="width: 15%; text-align: center;">Kondisi Awal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loan->loanItems as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $item->item->code }}</strong></td>
                <td>
                    {{ $item->item->name }}<br>
                    <small style="color: #64748b;">Sumber: {{ strtoupper($item->item->source) }}</small>
                </td>
                <td style="text-align: center; font-size: 13px; font-weight: bold;">
                    {{ $item->qty_approved > 0 ? $item->qty_approved : $item->qty_requested }} unit
                </td>
                <td style="text-align: center;">{{ strtoupper($item->item->condition) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td class="signature-box">
                Peminjam,<br>
                <div class="signature-space"></div>
                <strong><u>{{ $loan->borrower->name }}</u></strong><br>
                NIP/ID: {{ $loan->borrower->member_no ?? '-' }}
            </td>
            <td class="signature-box">
                <div class="qr-section">
                    @if(!empty($qrCode))
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="90" height="90" alt="QR Verifikasi" />
                    @else
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode($verifyUrl) }}" width="90" height="90" alt="QR Verifikasi" />
                    @endif
                    <div class="qr-text">Pindai untuk verifikasi keaslian surat</div>
                </div>
            </td>
            <td class="signature-box">
                Petugas Logistik,<br>
                <div class="signature-space"></div>
                <strong><u>{{ $loan->approver->name ?? 'Petugas Logistik' }}</u></strong><br>
                Divisi Perlengkapan
            </td>
        </tr>
    </table>

</body>
</html>

