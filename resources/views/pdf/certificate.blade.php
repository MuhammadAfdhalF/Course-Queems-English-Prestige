<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->certificate_number }}</title>

    @php
    $templateBackground = $certificate->certificateTemplate?->background_image;
    $templateBackgroundPath = $templateBackground
    ? public_path('storage/' . $templateBackground)
    : null;

    $hasTemplateBackground = $templateBackgroundPath && file_exists($templateBackgroundPath);

    $logoPath = public_path('images/logo-queens-english.png');
    $hasLogo = file_exists($logoPath);

    $signaturePath = $certificateSetting?->signature_image
    ? public_path('storage/' . $certificateSetting->signature_image)
    : null;

    $hasSignature = $signaturePath && file_exists($signaturePath);

    $signerName = $certificateSetting?->signerName() ?? 'Queens English Prestige';
    $signerTitle = $certificateSetting?->signerTitle() ?? 'Authorized Signature';

    $verificationUrl = $certificate->verification_token
    ? route('certificates.verify', $certificate->verification_token)
    : null;

    $qrSvg = $verificationUrl
    ? \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(130)->margin(1)->generate($verificationUrl)
    : null;

    $qrDataUri = $qrSvg
    ? 'data:image/svg+xml;base64,' . base64_encode($qrSvg)
    : null;
    @endphp

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            background: #ffffff;
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
        }

        .certificate {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            background: #fffdf6;
            text-align: center;
        }

        .template-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            z-index: 0;
        }

        .default-border {
            position: absolute;
            top: 6mm;
            left: 6mm;
            right: 6mm;
            bottom: 6mm;
            border: 3mm solid #071738;
            z-index: 1;
        }

        .inner-border {
            position: absolute;
            top: 13mm;
            left: 13mm;
            right: 13mm;
            bottom: 13mm;
            border: 0.35mm solid rgba(212, 160, 23, 0.35);
            z-index: 2;
        }

        .corner {
            position: absolute;
            width: 24mm;
            height: 24mm;
            z-index: 3;
        }

        .corner-top-left {
            top: 15mm;
            left: 15mm;
            border-left: 0.9mm solid #d4a017;
            border-top: 0.9mm solid #d4a017;
        }

        .corner-top-right {
            top: 15mm;
            right: 15mm;
            border-right: 0.9mm solid #d4a017;
            border-top: 0.9mm solid #d4a017;
        }

        .corner-bottom-left {
            bottom: 15mm;
            left: 15mm;
            border-left: 0.9mm solid #d4a017;
            border-bottom: 0.9mm solid #d4a017;
        }

        .corner-bottom-right {
            bottom: 15mm;
            right: 15mm;
            border-right: 0.9mm solid #d4a017;
            border-bottom: 0.9mm solid #d4a017;
        }

        .watermark-left {
            position: absolute;
            left: -35mm;
            top: 42mm;
            width: 90mm;
            height: 90mm;
            border: 8mm solid rgba(212, 160, 23, 0.08);
            border-radius: 50%;
            z-index: 1;
        }

        .watermark-right {
            position: absolute;
            right: -40mm;
            bottom: 25mm;
            width: 105mm;
            height: 105mm;
            border: 9mm solid rgba(7, 23, 56, 0.06);
            border-radius: 50%;
            z-index: 1;
        }

        .content-layer {
            position: relative;
            z-index: 5;
            width: 297mm;
            height: 210mm;
        }

        .header {
            position: absolute;
            top: 17mm;
            left: 30mm;
            right: 30mm;
            text-align: center;
        }

        .brand-mark {
            width: 16mm;
            height: 16mm;
            margin: 0 auto;
            border-radius: 50%;
            border: 0.3mm solid #dbe3ef;
            background: #ffffff;
            color: #071738;
            font-size: 8.5pt;
            font-weight: 900;
            line-height: 16mm;
            text-align: center;
            overflow: hidden;
        }

        .brand-logo {
            width: 13mm;
            height: 13mm;
            margin-top: 1.4mm;
        }

        .brand {
            margin-top: 3mm;
            color: #d4a017;
            font-size: 5.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3.2px;
        }

        .title {
            margin: 5mm 0 0;
            color: #071738;
            font-size: 36pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 8px;
            line-height: 1;
        }

        .subtitle {
            margin-top: 3mm;
            color: #64748b;
            font-size: 7.4pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .gold-line {
            width: 92mm;
            height: 0.55mm;
            margin: 5mm auto 0;
            background: #d4a017;
        }

        .recipient {
            position: absolute;
            top: 80mm;
            left: 35mm;
            right: 35mm;
            text-align: center;
        }

        .presented {
            color: #475569;
            font-size: 8.5pt;
        }

        .student-name {
            margin-top: 3mm;
            color: #020617;
            font-size: 24pt;
            font-weight: 900;
            line-height: 1.08;
        }

        .thin-line {
            width: 130mm;
            height: 0.28mm;
            margin: 4mm auto 0;
            background: #cbd5e1;
        }

        .completion-text {
            margin-top: 5mm;
            color: #475569;
            font-size: 8.5pt;
        }

        .course-name {
            margin-top: 2mm;
            color: #071738;
            font-size: 17pt;
            font-weight: 900;
            line-height: 1.1;
        }

        .program-name {
            margin-top: 2mm;
            color: #d4a017;
            font-size: 6.4pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.7px;
        }

        .meta-table {
            position: absolute;
            left: 72mm;
            top: 135mm;
            width: 153mm;
            border-collapse: separate;
            border-spacing: 3mm 0;
        }

        .meta-box {
            width: 50%;
            padding: 3.2mm 3.8mm;
            border: 0.3mm solid #dbe3ef;
            background: rgba(255, 255, 255, 0.90);
            text-align: left;
        }

        .meta-label {
            color: #94a3b8;
            font-size: 5.2pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.1px;
        }

        .meta-value {
            margin-top: 1.4mm;
            color: #0f172a;
            font-size: 7pt;
            font-weight: 900;
        }

        .bottom-table {
            position: absolute;
            left: 50mm;
            top: 160mm;
            width: 197mm;
            border-collapse: collapse;
        }

        .bottom-cell {
            width: 50%;
            vertical-align: middle;
            text-align: center;
            padding: 0 10mm;
        }

        .signature-image {
            width: 54mm;
            height: 14mm;
            object-fit: contain;
            margin: 0 auto 1.5mm;
            display: block;
        }

        .signature-placeholder {
            height: 14mm;
            margin-bottom: 1.5mm;
        }

        .signature-line {
            width: 68mm;
            height: 0.3mm;
            background: #334155;
            margin: 0 auto 3mm;
        }

        .signature-name {
            color: #0f172a;
            font-size: 7.4pt;
            font-weight: 900;
        }

        .signature-title {
            margin-top: 1mm;
            color: #94a3b8;
            font-size: 5.2pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .qr-box {
            display: inline-block;
            width: 31mm;
            padding: 1.8mm;
            border: 0.3mm solid #dbe3ef;
            background: rgba(255, 255, 255, 0.96);
            text-align: center;
        }

        .qr-box img {
            width: 21mm;
            height: 21mm;
            display: block;
            margin: 0 auto;
        }

        .qr-title {
            margin-top: 1mm;
            color: #0f172a;
            font-size: 5.5pt;
            font-weight: 900;
        }

        .qr-subtitle {
            margin-top: 0.5mm;
            color: #94a3b8;
            font-size: 4.2pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>
    <div class="certificate">
        @if ($hasTemplateBackground)
        <img
            src="{{ $templateBackgroundPath }}"
            alt="Certificate Template Background"
            class="template-background">
        @else
        <div class="default-border"></div>
        <div class="inner-border"></div>

        <div class="corner corner-top-left"></div>
        <div class="corner corner-top-right"></div>
        <div class="corner corner-bottom-left"></div>
        <div class="corner corner-bottom-right"></div>

        <div class="watermark-left"></div>
        <div class="watermark-right"></div>
        @endif

        <div class="content-layer">
            <div class="header">
                <div class="brand-mark">
                    @if ($hasLogo)
                    <img
                        src="{{ $logoPath }}"
                        alt="Queens English Prestige"
                        class="brand-logo">
                    @else
                    QEP
                    @endif
                </div>

                <div class="brand">
                    Queens English Prestige
                </div>

                <div class="title">
                    Certificate
                </div>

                <div class="subtitle">
                    Of Completion
                </div>

                <div class="gold-line"></div>
            </div>

            <div class="recipient">
                <div class="presented">
                    This certificate is proudly presented to
                </div>

                <div class="student-name">
                    {{ $student?->name ?? 'Student Name' }}
                </div>

                <div class="thin-line"></div>

                <div class="completion-text">
                    for successfully completing the course
                </div>

                <div class="course-name">
                    {{ $courseLevel?->name ?? 'Course Name' }}
                </div>

                <div class="program-name">
                    {{ $courseProgram?->name ?? 'Queens English Prestige Program' }}
                </div>
            </div>

            <table class="meta-table">
                <tr>
                    <td class="meta-box">
                        <div class="meta-label">Certificate No.</div>
                        <div class="meta-value">{{ $certificate->certificate_number }}</div>
                    </td>

                    <td class="meta-box">
                        <div class="meta-label">Issued Date</div>
                        <div class="meta-value">{{ $certificate->issued_at?->format('d F Y') ?? '-' }}</div>
                    </td>
                </tr>
            </table>

            <table class="bottom-table">
                <tr>
                    <td class="bottom-cell">
                        @if ($hasSignature)
                        <img
                            src="{{ $signaturePath }}"
                            alt="{{ $signerName }}"
                            class="signature-image">
                        @else
                        <div class="signature-placeholder"></div>
                        @endif

                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $signerName }}</div>
                        <div class="signature-title">{{ $signerTitle }}</div>
                    </td>

                    <td class="bottom-cell">
                        @if ($qrDataUri)
                        <div class="qr-box">
                            <img src="{{ $qrDataUri }}" alt="Certificate Verification QR">
                            <div class="qr-title">Verify Certificate</div>
                            <div class="qr-subtitle">Scan to Verify</div>
                        </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>