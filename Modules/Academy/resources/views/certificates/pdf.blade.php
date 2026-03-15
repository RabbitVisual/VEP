<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $courseTitle }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; padding: 40px; color: #1e293b; }
        .cert { border: 3px solid #4f46e5; padding: 48px; min-height: 100%; }
        .title { font-size: 28px; font-weight: bold; text-align: center; color: #312e81; margin-bottom: 24px; }
        .subtitle { font-size: 16px; text-align: center; color: #6366f1; margin-bottom: 32px; }
        .name { font-size: 22px; font-weight: bold; text-align: center; margin: 24px 0; }
        .course { font-size: 18px; text-align: center; color: #334155; margin-bottom: 16px; }
        .hours { font-size: 14px; text-align: center; color: #64748b; margin-bottom: 24px; }
        .date { font-size: 14px; text-align: center; color: #64748b; }
        .code { font-size: 10px; text-align: center; color: #94a3b8; margin-top: 32px; }
    </style>
</head>
<body>
    <div class="cert">
        <h1 class="title">Certificado de Conclusão</h1>
        <p class="subtitle">Este certificado atesta que</p>
        <p class="name">{{ $studentName }}</p>
        <p class="course">concluiu o curso <strong>{{ $courseTitle }}</strong></p>
        <p class="hours">com carga horária total de <strong>{{ $totalHours }}</strong> hora(s).</p>
        <p class="date">Emitido em {{ $issuedAt }}.</p>
        <p class="code">Código de validação: {{ $validationCode }}</p>
    </div>
</body>
</html>
