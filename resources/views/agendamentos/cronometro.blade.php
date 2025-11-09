{{-- Cronômetro da Consulta --}}
@if($agendamento->status === 'em_atendimento')
<div id="cronometro-container">
    <!-- HTML do cronômetro aqui -->
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const dataInicio = new Date("{{ $agendamento->data_inicio_consulta->toIso8601String() }}");
            const duracaoMinutos = {{ $agendamento->duracao_minutos ?? 60 }};
            const duracaoMs = duracaoMinutos * 60 * 1000;

            const tempoDecorridoEl = document.getElementById('tempo-decorrido');
            const barraProgressoEl = document.getElementById('barra-progresso');
            const percentualEl = document.getElementById('percentual-progresso');

                console.warn('Elementos não encontrados');
                return;
            }

            function atualizarTempo() {
                const agora = new Date();
                const tempoDecorridoMs = agora - dataInicio;
                
                const horas = Math.floor(tempoDecorridoMs / (1000 * 60 * 60));
                const minutos = Math.floor((tempoDecorridoMs % (1000 * 60 * 60)) / (1000 * 60));
                const segundos = Math.floor((tempoDecorridoMs % (1000 * 60)) / 1000);

                const formatoTempo = String(horas).padStart(2, '0') + ':' +
                                    String(minutos).padStart(2, '0') + ':' +
                                    String(segundos).padStart(2, '0');

                tempoDecorridoEl.textContent = formatoTempo;

                const percentual = Math.min(Math.round((tempoDecorridoMs / duracaoMs) * 100), 100);
                barraProgressoEl.style.width = percentual + '%';
                percentualEl.textContent = percentual;

                if (tempoDecorridoMs > duracaoMs) {
                    barraProgressoEl.classList.remove('from-blue-500', 'to-blue-600');
                    barraProgressoEl.classList.add('from-red-500', 'to-red-600');
                    tempoDecorridoEl.classList.remove('text-blue-600');
                    tempoDecorridoEl.classList.add('text-red-600');
                }
            }

            atualizarTempo();
            setInterval(atualizarTempo, 1000);
        } catch (error) {
            console.error('Erro no cronômetro:', error);
        }
    });
</script>
@endpush
@endif