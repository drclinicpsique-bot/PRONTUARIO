<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescrição Médica</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.8;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1a5f3d;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #1a5f3d;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
            font-size: 13px;
        }

        .info-box {
            text-align: left;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 3px solid #1a5f3d;
            padding-left: 15px;
        }

        .info-label {
            font-weight: bold;
            color: #1a5f3d;
        }

        .content {
            margin: 30px 0;
        }

        .prescription-section {
            margin-bottom: 40px;
        }

        .section-title {
            background-color: #1a5f3d;
            color: white;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .prescription-items {
            padding: 15px;
            line-height: 2.2;
            white-space: pre-wrap;
            font-size: 14px;
            background-color: #fafafa;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }

        .footer-signature {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            text-align: center;
        }

        .signature-block {
            border-top: 1px solid #000;
            padding-top: 10px;
            font-size: 13px;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }

        .signature-crm {
            font-size: 11px;
            color: #666;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .important-note {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #856404;
        }

        .patient-info {
            font-size: 13px;
            margin-bottom: 20px;
        }

        .patient-info strong {
            color: #1a5f3d;
        }
    </style>
</head>
<body>
    <div class="container">

        {{-- CABEÇALHO --}}
        <div class="header">
            <h1>💊 PRESCRIÇÃO MÉDICA</h1>
            <p>Prescrição de Medicamentos</p>
        </div>

        {{-- INFORMAÇÕES PACIENTE E MÉDICO --}}
        <div class="header-info">
            <div class="info-box">
                <div class="info-label">PACIENTE:</div>
                <div>{{ $prontuario->paciente->nome_completo }}</div>
                <div style="font-size: 12px; color: #666;">CPF: {{ $prontuario->paciente->cpf }}</div>
            </div>

            <div class="info-box">
                <div class="info-label">PROFISSIONAL:</div>
                <div>Dr(a). {{ $prontuario->profissional->usuario->nome_completo }}</div>
                <div style="font-size: 12px; color: #666;">CRM: {{ $prontuario->profissional->crm }}</div>
                <div style="font-size: 12px; color: #666;">{{ $prontuario->profissional->especialidade }}</div>
            </div>
        </div>

        <div class="patient-info">
            <strong>Data da Consulta:</strong> {{ $prontuario->data_atendimento->format('d/m/Y H:i') }}<br>
            <strong>Emitido em:</strong> {{ now()->format('d/m/Y H:i') }}
        </div>

        <div class="important-note">
            ⚠️ <strong>IMPORTANTE:</strong> Esta prescrição é válida por 30 dias. Apresente este documento à farmácia para adquirir os medicamentos.
        </div>

        {{-- CONTEÚDO PRESCRIÇÃO --}}
        <div class="content">
            <div class="prescription-section">
                <div class="section-title">MEDICAMENTOS PRESCRITOS</div>
                <div class="prescription-items">{{ $prontuario->prescricao_medicamentos }}</div>
            </div>

            @if($prontuario->observacoes_gerais)
            <div class="prescription-section">
                <div class="section-title">OBSERVAÇÕES IMPORTANTES</div>
                <div class="prescription-items">{{ $prontuario->observacoes_gerais }}</div>
            </div>
            @endif

            @if($prontuario->data_retorno)
            <div class="prescription-section">
                <div class="section-title">PRÓXIMA CONSULTA</div>
                <div style="padding: 15px; font-size: 14px; background-color: #fafafa; border: 1px solid #ddd; border-radius: 4px;">
                    <strong>Data de Retorno:</strong> {{ $prontuario->data_retorno->format('d/m/Y') }}
                </div>
            </div>
            @endif
        </div>

        {{-- ASSINATURA --}}
        <div class="footer-signature">
            <div class="signature-block">
                <div class="signature-name">Dr(a). {{ $prontuario->profissional->usuario->nome_completo }}</div>
                <div class="signature-crm">CRM: {{ $prontuario->profissional->crm }}</div>
                <div class="signature-crm">{{ $prontuario->profissional->especialidade }}</div>
            </div>
        </div>

        {{-- RODAPÉ --}}
        <div class="footer">
            <p>Prescrição eletrônica gerada pelo Sistema DRCLINIC</p>
            <p>Documento de validade legal para uso em farmácias</p>
            <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

    </div>
</body>
</html>