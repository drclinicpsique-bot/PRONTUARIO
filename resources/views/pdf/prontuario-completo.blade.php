<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prontuário Médico</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            border-bottom: 3px solid #1a5f3d;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #1a5f3d;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            font-size: 12px;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #1a5f3d;
            color: white;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .field-row {
            margin-bottom: 10px;
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 15px;
        }

        .field-label {
            font-weight: bold;
            color: #1a5f3d;
            font-size: 12px;
        }

        .field-value {
            font-size: 12px;
            padding: 5px;
            background-color: #f5f5f5;
            border-left: 2px solid #1a5f3d;
            padding-left: 10px;
        }

        .text-area-value {
            font-size: 12px;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 2px solid #1a5f3d;
            padding-left: 10px;
            white-space: pre-wrap;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .empty-field {
            color: #999;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table th {
            background-color: #1a5f3d;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        .assinatura {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            text-align: center;
            font-size: 12px;
        }

        .assinatura-line {
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">

        {{-- CABEÇALHO --}}
        <div class="header">
            <h1>PRONTUÁRIO MÉDICO</h1>
            <div class="header-info">
                <div><strong>ID:</strong> #{{ $prontuario->id }}</div>
                <div><strong>Data:</strong> {{ $prontuario->created_at->format('d/m/Y H:i') }}</div>
                <div><strong>Status:</strong> {{ $prontuario->finalizado ? 'FINALIZADO' : 'EM ABERTO' }}</div>
                <div><strong>Última atualização:</strong> {{ $prontuario->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        {{-- SEÇÃO: IDENTIFICAÇÃO --}}
        <div class="section">
            <div class="section-title">IDENTIFICAÇÃO</div>

            <div class="field-row">
                <span class="field-label">Paciente:</span>
                <span class="field-value">{{ $prontuario->paciente->nome_completo }}</span>
            </div>

            <div class="field-row">
                <span class="field-label">CPF:</span>
                <span class="field-value">{{ $prontuario->paciente->cpf }}</span>
            </div>

            <div class="field-row">
                <span class="field-label">Data de Nascimento:</span>
                <span class="field-value">{{ $prontuario->paciente->data_nascimento->format('d/m/Y') }}</span>
            </div>

            <div class="field-row">
                <span class="field-label">Profissional:</span>
                <span class="field-value">Dr(a). {{ $prontuario->profissional->usuario->nome_completo }}</span>
            </div>

            <div class="field-row">
                <span class="field-label">Especialidade:</span>
                <span class="field-value">{{ $prontuario->profissional->especialidade }}</span>
            </div>

            <div class="field-row">
                <span class="field-label">Data do Atendimento:</span>
                <span class="field-value">{{ $prontuario->data_atendimento->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        {{-- SEÇÃO: HISTÓRIA CLÍNICA --}}
        <div class="section">
            <div class="section-title">HISTÓRIA CLÍNICA</div>

            @if($prontuario->queixa_principal)
            <div class="field-row">
                <span class="field-label">Queixa Principal:</span>
                <span class="field-value">{{ $prontuario->queixa_principal }}</span>
            </div>
            @endif

            @if($prontuario->historia_doenca_atual)
            <div class="field-row">
                <span class="field-label">HDA:</span>
                <span class="text-area-value">{{ $prontuario->historia_doenca_atual }}</span>
            </div>
            @endif

            @if($prontuario->historia_patologica_pregressa)
            <div class="field-row">
                <span class="field-label">HPP:</span>
                <span class="text-area-value">{{ $prontuario->historia_patologica_pregressa }}</span>
            </div>
            @endif

            @if($prontuario->historia_familiar)
            <div class="field-row">
                <span class="field-label">História Familiar:</span>
                <span class="text-area-value">{{ $prontuario->historia_familiar }}</span>
            </div>
            @endif

            @if($prontuario->historia_social)
            <div class="field-row">
                <span class="field-label">História Social:</span>
                <span class="text-area-value">{{ $prontuario->historia_social }}</span>
            </div>
            @endif
        </div>

        {{-- SEÇÃO: EXAME E DIAGNÓSTICO --}}
        <div class="section">
            <div class="section-title">EXAME E DIAGNÓSTICO</div>

            @if($prontuario->exame_fisico)
            <div class="field-row">
                <span class="field-label">Exame Físico:</span>
                <span class="text-area-value">{{ $prontuario->exame_fisico }}</span>
            </div>
            @endif

            @if($prontuario->hipotese_diagnostica)
            <div class="field-row">
                <span class="field-label">Hipótese Diagnóstica:</span>
                <span class="text-area-value">{{ $prontuario->hipotese_diagnostica }}</span>
            </div>
            @endif
        </div>

        {{-- SEÇÃO: CONDUTA E TRATAMENTO --}}
        <div class="section">
            <div class="section-title">CONDUTA E TRATAMENTO</div>

            @if($prontuario->conduta_tratamento)
            <div class="field-row">
                <span class="field-label">Conduta/Tratamento:</span>
                <span class="text-area-value">{{ $prontuario->conduta_tratamento }}</span>
            </div>
            @endif

            @if($prontuario->prescricao_medicamentos)
            <div class="field-row">
                <span class="field-label">Prescrição:</span>
                <span class="text-area-value">{{ $prontuario->prescricao_medicamentos }}</span>
            </div>
            @endif

            @if($prontuario->exames_solicitados)
            <div class="field-row">
                <span class="field-label">Exames Solicitados:</span>
                <span class="text-area-value">{{ $prontuario->exames_solicitados }}</span>
            </div>
            @endif

            @if($prontuario->observacoes_gerais)
            <div class="field-row">
                <span class="field-label">Observações:</span>
                <span class="text-area-value">{{ $prontuario->observacoes_gerais }}</span>
            </div>
            @endif

            @if($prontuario->data_retorno)
            <div class="field-row">
                <span class="field-label">Retorno em:</span>
                <span class="field-value">{{ $prontuario->data_retorno->format('d/m/Y') }}</span>
            </div>
            @endif
        </div>

        {{-- ASSINATURA --}}
        <div class="assinatura">
            <div>
                <div class="assinatura-line">
                    Paciente/Responsável
                </div>
            </div>
            <div>
                <div class="assinatura-line">
                    Dr(a). {{ $prontuario->profissional->usuario->nome_completo }}<br>
                    CRM: {{ $prontuario->profissional->crm }}
                </div>
            </div>
        </div>

        {{-- RODAPÉ --}}
        <div class="footer">
            <p>Documento gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>Sistema DRCLINIC - Prontuário Eletrônico</p>
        </div>

    </div>
</body>
</html>