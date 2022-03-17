<div>

    <div class="page-header d-flex flex-row align-items-center justify-content-between mb-2">
        @if(auth()->user()->id === 1)
        <h2 class="f-h2">Área administrativa</h2>
        <div class="div-right-admin">
            <a href="{{route('register')}}" class="btn btn-new">+ Novo usuário</a>
            @if ($estado_manutencao == 0)
            <button wire:click.prevent="manutencao" wire:loading.attr="disabled" class="btn btn-new">Ativar manutenção</button>
            @elseif($estado_manutencao == 1)
            <button wire:click.prevent="manutencao" wire:loading.attr="disabled" class="btn-maint">Desativar manutenção</button>
            @endif
        </div>
        @else
        <h2 class="f-h2">Área restrita</h2>
        @endif
    </div>

    <div class="block">

        @if(auth()->user()->id === 1)
            <div class="admin-area">

                <div class="card">
                    
                    <div class="topo-ico d-flex flex-row align-items-center mb-1">
                        <i style="color: #725BC2;" class="fad fa-users fa-fw fa-lg mr-2"></i>
                        <div class="card-topo">
                            <div style="margin-bottom: 0 !important;" class="title-block f-calc">                                                                        
                                Usuários cadastrados
                                <span class="period">/ Quantidade: {{$users_count}}</span>
                            </div>                     
                        </div>
                    </div>
                
                    <div wire:target="qtd" style="margin-top: 125px; margin-bottom: 125px;"
                        wire:loading wire:loading.class="d-flex flex-row align-items-center justify-content-center">
                        <i style="color: #725BC2; opacity: 90%;" class="fad fa-spinner-third fa-fw fa-3x fa-spin"></i>
                    </div>

                    <div wire:target="qtd" wire:loading.remove class="card-body px-0 pb-0 table-responsive yampay-scroll">

                        @if($users->count())
        
                            <table style="cursor: default; white-space: nowrap;" class="table table-borderless">
                                <thead class="t-head">
                                    <tr class="t-head-border">
                                        <th>ID #</th>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Montante</th>
                                        <th>Qtd. de operações</th>
                                        <th>Data de criação</th>                                 
                                        <th>Último login</th>
                                        <th>Outros dados</th>
                                    </tr>
                                </thead>
                                <tbody class="t-body">
        
                                    @php
                                        $dia_atual = Carbon\Carbon::now();
                                    @endphp
        
                                    @foreach ($users as $user)
                                
                                        @php
                                            //Data de criação

                                            $data_criacao = $user->created_at->format('d/m/Y H:i');
                                            $ultimo_login = date('d/m/Y H:i', strtotime($user->last_login));
                                            
                                            $date1 = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dia_atual);
                                            $date2 = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at);
                                            
                                            $diferenca = $date2->diffInDays($date1);
                                            $tempo = 'dias';
                                            
                                            if ($diferenca === 1) {
                                                $diferenca = 'um';
                                                $tempo = 'dia';
                                            }
                                            
                                            if ($diferenca === 0) {
                                                $diferenca = $date2->diffInHours($date1);
                                                $tempo = 'horas';
        
                                                if ($diferenca === 1) {
                                                    $diferenca = 'uma';
                                                    $tempo = 'hora';
                                                }
                                            
                                                if ($diferenca === 0) {
                                                    $diferenca = $date2->diffInMinutes($date1);
                                                    $tempo = 'minutos';
                                            
                                                    if ($diferenca === 1) {
                                                        $diferenca = 'um';
                                                        $tempo = 'minuto';
                                                    }
                                            
                                                    if ($diferenca === 0) {
                                                        $diferenca = 'poucos';
                                                        $tempo = 'segundos';
                                                    }
                                                }
                                            }
                                                                                     
                                        @endphp

                                        @php
                                            //Último login

                                            $date1_log = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dia_atual);
                                            $date2_log = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login);
                                            
                                            $diferenca_log = $date2_log->diffInDays($date1_log);
                                            $tempo_log = 'dias';
                                            
                                            if ($diferenca_log === 1) {
                                                $diferenca_log = 'um';
                                                $tempo_log = 'dia';
                                            }
                                            
                                            if ($diferenca_log === 0) {
                                                $diferenca_log = $date2_log->diffInHours($date1_log);
                                                $tempo_log = 'horas';
        
                                                if ($diferenca_log === 1) {
                                                    $diferenca_log = 'uma';
                                                    $tempo_log = 'hora';
                                                }
                                            
                                                if ($diferenca_log === 0) {
                                                    $diferenca_log = $date2_log->diffInMinutes($date1_log);
                                                    $tempo_log = 'minutos';
                                            
                                                    if ($diferenca_log === 1) {
                                                        $diferenca_log = 'um';
                                                        $tempo_log = 'minuto';
                                                    }
                                            
                                                    if ($diferenca_log === 0) {
                                                        $diferenca_log = 'poucos';
                                                        $tempo_log = 'segundos';
                                                    }
                                                }
                                            }
                                        @endphp

                                        @php
                                       

                                            $entradas_total = App\Models\Operation::where('user_id', $user->id)
                                            ->where('tipo', 1)
                                            ->sum('total');

                                            $saidas_ret_total = App\Models\Operation::where('user_id', $user->id)
                                            ->whereIn('tipo', [0,3])
                                            ->sum('total');

                                            $caixa_total = $entradas_total - $saidas_ret_total;
                                            $caixa_total = number_format($caixa_total,2,",",".");
                                        @endphp
        
                                        <tr class="tr-hover">

                                            <td class="align-middle">{{ $user->id }}</td>
                                            <td class="align-middle font-desc">{{ $user->name }}</td>
                                            <td class="align-middle">{{ $user->email }}</td>
                                            <td style="font-size: 15px; font-weight: 500; color: #01984E;" class="align-middle">
                                            R$ {{$caixa_total}}
                                            </td>
                                            <td style="font-size: 14px; font-weight: 500; color: #725BC2;" class="align-middle">
                                                @if ($user->operations->count() === 0)
                                                    Nenhuma
                                                @else
                                                {{ $user->operations->count() }}                                            
                                                @endif
                                            </td>
                                            
                                            <td class="align-middle">{{ $data_criacao }}<br>
                                                <span class="g-light">há {{ $diferenca }} {{ $tempo }}</span>
                                            </td>

                                            <td class="align-middle">
                                                <span style="font-size: 11px;" class="operacao-entrada text-nowrap">{{ $ultimo_login }}</span><br>
                                                <div class="mt-1">
                                                    <span class="g-light">há {{ $diferenca_log }} {{ $tempo_log }}</span>
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                          
                                                <div class="dropdown">

                                                    <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-tooltip="Visualizar" data-flow="left">
                                                        <i style="color: #725BC2; font-size: 25px;" class="fad fa-eye fa-fw"></i>
                                                    </button>

                                                    <div style="padding: 20px !important;" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <div style="margin-bottom: 8px !important;" class="title-block f-calc">                                                                        
                                                            Qtd. de categorias:
                                                            <span style="font-size: 15px; color: #725BC2 !important;" class="period px-1"> {{$user->categories->count()}}</span>
                                                        </div> 
                                                        <div style="margin-bottom: 8px !important;" class="title-block f-calc">                                                                        
                                                            Qtd. de links:
                                                            <span style="font-size: 15px; color: #725BC2 !important;" class="period px-1"> {{$user->shortcuts->count()}}</span>
                                                        </div> 
                                                        <div style="margin-bottom: 0 !important;" class="title-block f-calc">                                                                        
                                                            Qtd. de tarefas:
                                                            <span style="font-size: 15px; color: #725BC2 !important;" class="period px-1"> {{$user->tasks->count()}}</span>
                                                        </div> 
                                                    </div>
                                                </div>  

                                            </td>
                                               
                                        </tr>
        
                                    @endforeach
        
                                </tbody>
                            </table>
        
                        @else
        
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="211"
                                    height="145">
                                    <style>
                                        <![CDATA[
                                        .B {
                                            fill: #fff
                                        }
        
                                        .C {
                                            stroke-linecap: round
                                        }
        
                                        .D {
                                            stroke-width: 1.395
                                        }
        
                                        .E {
                                            stroke: #a4afb7
                                        }
        
                                        .F {
                                            fill-rule: nonzero
                                        }
        
                                        .G {
                                            stroke-linejoin: round
                                        }
        
                                        .H {
                                            fill: #d5dadf
                                        }
        
                                        ]]>
                                    </style>
                                    <g fill="none" fill-rule="evenodd">
                                        <path
                                            d="M53.803 136.846h122.8m3.907 0h5.58m-141.207 0h5.58m138.977 0h1.674m-150.696 0h1.674"
                                            class="C D E" />
                                        <path d="M121.264 47.475c6.093 1.704 9.14 3.238 9.14 4.603" stroke="#170040"
                                            class="C D G" />
                                        <path
                                            d="M3.093 132.455l-.3-33.292L1 98.633v-12.7l42.558-4.92 42.558 4.92v12.7l-1.783.53-.3 33.292-40.465 9.003z"
                                            class="D E H" />
                                        <path fill-opacity=".5" fill="#c2cbd2"
                                            d="M1 86.62l42.558-4.92v62.122l-40.465-7.31-.3-37.35L1 98.633z" />
                                        <g class="D E H">
                                            <path d="M3.093 136.513l-.3-37.35 40.775 5.6 40.775-5.6-.3 37.35-40.465 7.607z" />
                                            <path d="M1 98.633V86.41l42.558 5.088 42.558-5.088v12.224l-42.558 6.12z" />
                                        </g>
                                        <path fill-opacity=".5"
                                            d="M83.508 113.56v-13.6l-34.92 4.804zm1.9-26.454l-41.18 5.1.1 11.74 41.08-5.85z"
                                            fill="#c2cbd2" />
                                        <g class="D E">
                                            <path d="M43.558 91.497v52.326" />
                                            <g class="B F">
                                                <path
                                                    d="M76.532 65.72c-6.107 5.778-16.427 9.333-18.62 13.24-1.628 2.9-2.144 7.78-1.874 11.144 3.335-.325 4.11-.45 6.436-.838-.273-3.572.4-5.742 1.16-6.798 2.537-3.484 18.037-7.733 18.303-11"
                                                    class="C G" />
                                                <path
                                                    d="M141.144 29.827L89.04 29.01c-3.54-.056-6.93 1.425-9.295 4.06l-2.888 3.216c-.724.806-1.365 1.684-1.912 2.62-1.976 3.378-2.985 5.908-3.04 7.53-.703 20.954-.858 36.17-.467 45.636.178 4.308.765 8.13 1.76 11.47a16.05 16.05 0 0 0 15.38 11.469h59.993a16.05 16.05 0 0 0 16.046-16.046v-49.83c0-6.943-4.466-13.1-11.066-15.254l-12.41-4.05z" />
                                            </g>
                                        </g>
                                        <rect x="70.332" y="28.22" width="81.73" height="81.882" rx="21.767" class="B F" />
                                        <g class="D E B F">
                                            <rect x="71.03" y="28.917" width="80.334" height="80.487" rx="16.744" />
                                            <g class="C G">
                                                <use xlink:href="#B" />
                                                <use xlink:href="#B" x="-23.441" />
                                            </g>
                                        </g>
                                        <rect fill-opacity=".7" fill="#e6e9ec" x="75.291" y="32.893" width="71.86"
                                            height="71.86" rx="11.498" class="F" />
                                        <path
                                            d="M97.123 72.242c0 1.8-2.25 3.28-5.023 3.28s-5.023-1.47-5.023-3.28 2.25-3.28 5.023-3.28 5.023 1.47 5.023 3.28m23.44 0c0 1.8 2.25 3.28 5.023 3.28s5.023-1.47 5.023-3.28-2.25-3.28-5.023-3.28-5.023 1.47-5.023 3.28"
                                            class="H" />
                                        <g class="D E">
                                            <path
                                                d="M94.325 70.656c2.986 0 5.733-2.328 5.733-5.877s-2.328-5.654-5.315-5.654-5.5 2.547-5.5 6.095 2.094 5.436 5.08 5.436z"
                                                class="B" />
                                            <path d="M94.08 62.234c-.772 1.904-.772 3.548 0 4.932" class="C" />
                                            <path
                                                d="M123.592 70.656c-2.986 0-5.733-2.328-5.733-5.877s2.328-5.654 5.315-5.654 5.5 2.547 5.5 6.095-2.094 5.436-5.08 5.436z"
                                                class="B" />
                                            <path d="M123.838 62.234c.772 1.904.772 3.548 0 4.932" class="C" />
                                            <g class="B">
                                                <path
                                                    d="M172.708 136.447h-7.356l-1.266-2.218-6.018-10.082-.083-.154c-.895-1.75-.653-3.892 1.096-4.788.14-.072.285-.133.434-.184a3.23 3.23 0 0 1 3.919 1.606c.044.088.102.226.173.414l2.072 6.425c-.09-.562-.566-4.083-1.427-10.565a4.38 4.38 0 0 1 3.985-4.737 4.34 4.34 0 0 1 .558-.012l.25.01c2.464.103 4.378 2.184 4.275 4.647-.003.084-.01.167-.018.25l-1.914 14.53c.024.8.697-1.637 2.02-7.308a2.66 2.66 0 0 1 3.782-1.39c1.49.83 1.588 2.23.763 3.724l-5.247 9.83z" />
                                                <path
                                                    d="M161.104 130.992c3.01 0 5.462 2.38 5.577 5.362l.003.218h-11.162c.001-3.01 2.382-5.46 5.362-5.576l.22-.004z" />
                                            </g>
                                            <ellipse fill="#a4afb7" cx="108.789" cy="83.823" rx="5.233" ry="6.977" />
                                            <g class="B">
                                                <path
                                                    d="M108.8 81.023c2.9 0 4.255-.993 4.06-1.342-.96-1.73-2.422-2.834-4.06-2.834-1.594 0-3.022 1.046-3.982 2.695-.155.267 1.092 1.48 3.982 1.48z" />
                                                <path
                                                    d="M158.6 79.778c5.473 3.196 11.174 10.04 14.06 9.766 5.987-.57 6.628-9.173 7.166-17.228.674-10.082 6.255 1.37 9.17-5.638 1.505-3.617-5.528-7.05-9.17-5.5s-4.14 4.532-4.494 7.385c-.233 1.87-1.567 13.905-2.98 13.743-2.796-.32-9.738-10.83-13.75-11.65"
                                                    class="C F G" />
                                            </g>
                                            <path
                                                d="M194.848 50.46c4.93 2.962 7.9 7.255 8.917 12.88m-.695-12.957c.56.286 4.166 2.128 4.5 6.606"
                                                class="C" />
                                        </g>
                                        <path d="M85.828 39.033c-2.26 1.377-3.86 3.128-4.802 5.253" stroke="#fff"
                                            stroke-width="3.349" class="C" />
                                        <circle cx="79.408" cy="49.399" r="1.326" class="B" />
                                        <g class="D E">
                                            <g class="C">
                                                <path
                                                    d="M96.774 47.475c-6.093 1.704-9.14 3.238-9.14 4.603m34.884-4.603c6.093 1.704 9.14 3.238 9.14 4.603"
                                                    class="G" />
                                                <path
                                                    d="M23.195 55.836c.46 3.045 2.795 1.956 3.087 1.774 1.88-1.173 3.03-1.663 4.342-.56 2.022 1.7-1.555 4.872.66 6.486 2.658 1.937 3.704-3.687 6.936-1.772S36.7 67.4 42.388 67.9M49.52 2.425c2.207.194 4.164 1.2 4.164 3.22 0 2.44-4.174 2.955-3.376 5.577.957 3.146 4.952-.44 5.836 3.482s-7.248 3.923-1.817 7.837" />
                                            </g>
                                            <g class="B">
                                                <path
                                                    d="M47.182 41.33l9.2 2.19a.35.35 0 0 1 .166.586l-7 7a.35.35 0 0 1-.586-.166l-2.19-9.2a.35.35 0 0 1 .42-.42z" />
                                                <circle cx="28.907" cy="32.893" r="4.884" />
                                            </g>
                                        </g>
                                    </g>
                                    <defs>
                                        <path id="B"
                                            d="M123.276 112.706l.55 18.708h-1.3c-1.432 0-2.593 1.16-2.593 2.593s1.16 2.593 2.593 2.593h4.065a3.49 3.49 0 0 0 3.49-3.49c0-.594-1.432-9.597-1.126-20.405" />
                                    </defs>
                                </svg>
                                <h3 class="my-4 no-results">Não há usuários a serem exibidos.</h3>
                                <div class="d-flex flex-column align-items-center justify-content-center mb-4">
                                    <h3 class="no-results-create mb-3">Comece cadastrando um</h3>
                                    <a href="{{route('register')}}" class="ml-2 btn btn-nr">+ Novo usuário</a>
                                </div>
                            </div>
        
                        @endif
        
                    </div>

                </div>
            </div>
        @else
            <div class="card">

                <div class="card-body px-0 pb-0">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="211"
                            height="145">
                            <style>
                                <![CDATA[
                                .B {
                                    fill: #fff
                                }
        
                                .C {
                                    stroke-linecap: round
                                }
        
                                .D {
                                    stroke-width: 1.395
                                }
        
                                .E {
                                    stroke: #a4afb7
                                }
        
                                .F {
                                    fill-rule: nonzero
                                }
        
                                .G {
                                    stroke-linejoin: round
                                }
        
                                .H {
                                    fill: #d5dadf
                                }
        
                                ]]>
                            </style>
                            <g fill="none" fill-rule="evenodd">
                                <path
                                    d="M53.803 136.846h122.8m3.907 0h5.58m-141.207 0h5.58m138.977 0h1.674m-150.696 0h1.674"
                                    class="C D E" />
                                <path d="M121.264 47.475c6.093 1.704 9.14 3.238 9.14 4.603" stroke="#170040"
                                    class="C D G" />
                                <path
                                    d="M3.093 132.455l-.3-33.292L1 98.633v-12.7l42.558-4.92 42.558 4.92v12.7l-1.783.53-.3 33.292-40.465 9.003z"
                                    class="D E H" />
                                <path fill-opacity=".5" fill="#c2cbd2"
                                    d="M1 86.62l42.558-4.92v62.122l-40.465-7.31-.3-37.35L1 98.633z" />
                                <g class="D E H">
                                    <path d="M3.093 136.513l-.3-37.35 40.775 5.6 40.775-5.6-.3 37.35-40.465 7.607z" />
                                    <path d="M1 98.633V86.41l42.558 5.088 42.558-5.088v12.224l-42.558 6.12z" />
                                </g>
                                <path fill-opacity=".5"
                                    d="M83.508 113.56v-13.6l-34.92 4.804zm1.9-26.454l-41.18 5.1.1 11.74 41.08-5.85z"
                                    fill="#c2cbd2" />
                                <g class="D E">
                                    <path d="M43.558 91.497v52.326" />
                                    <g class="B F">
                                        <path
                                            d="M76.532 65.72c-6.107 5.778-16.427 9.333-18.62 13.24-1.628 2.9-2.144 7.78-1.874 11.144 3.335-.325 4.11-.45 6.436-.838-.273-3.572.4-5.742 1.16-6.798 2.537-3.484 18.037-7.733 18.303-11"
                                            class="C G" />
                                        <path
                                            d="M141.144 29.827L89.04 29.01c-3.54-.056-6.93 1.425-9.295 4.06l-2.888 3.216c-.724.806-1.365 1.684-1.912 2.62-1.976 3.378-2.985 5.908-3.04 7.53-.703 20.954-.858 36.17-.467 45.636.178 4.308.765 8.13 1.76 11.47a16.05 16.05 0 0 0 15.38 11.469h59.993a16.05 16.05 0 0 0 16.046-16.046v-49.83c0-6.943-4.466-13.1-11.066-15.254l-12.41-4.05z" />
                                    </g>
                                </g>
                                <rect x="70.332" y="28.22" width="81.73" height="81.882" rx="21.767"
                                    class="B F" />
                                <g class="D E B F">
                                    <rect x="71.03" y="28.917" width="80.334" height="80.487" rx="16.744" />
                                    <g class="C G">
                                        <use xlink:href="#B" />
                                        <use xlink:href="#B" x="-23.441" />
                                    </g>
                                </g>
                                <rect fill-opacity=".7" fill="#e6e9ec" x="75.291" y="32.893" width="71.86"
                                    height="71.86" rx="11.498" class="F" />
                                <path
                                    d="M97.123 72.242c0 1.8-2.25 3.28-5.023 3.28s-5.023-1.47-5.023-3.28 2.25-3.28 5.023-3.28 5.023 1.47 5.023 3.28m23.44 0c0 1.8 2.25 3.28 5.023 3.28s5.023-1.47 5.023-3.28-2.25-3.28-5.023-3.28-5.023 1.47-5.023 3.28"
                                    class="H" />
                                <g class="D E">
                                    <path
                                        d="M94.325 70.656c2.986 0 5.733-2.328 5.733-5.877s-2.328-5.654-5.315-5.654-5.5 2.547-5.5 6.095 2.094 5.436 5.08 5.436z"
                                        class="B" />
                                    <path d="M94.08 62.234c-.772 1.904-.772 3.548 0 4.932" class="C" />
                                    <path
                                        d="M123.592 70.656c-2.986 0-5.733-2.328-5.733-5.877s2.328-5.654 5.315-5.654 5.5 2.547 5.5 6.095-2.094 5.436-5.08 5.436z"
                                        class="B" />
                                    <path d="M123.838 62.234c.772 1.904.772 3.548 0 4.932" class="C" />
                                    <g class="B">
                                        <path
                                            d="M172.708 136.447h-7.356l-1.266-2.218-6.018-10.082-.083-.154c-.895-1.75-.653-3.892 1.096-4.788.14-.072.285-.133.434-.184a3.23 3.23 0 0 1 3.919 1.606c.044.088.102.226.173.414l2.072 6.425c-.09-.562-.566-4.083-1.427-10.565a4.38 4.38 0 0 1 3.985-4.737 4.34 4.34 0 0 1 .558-.012l.25.01c2.464.103 4.378 2.184 4.275 4.647-.003.084-.01.167-.018.25l-1.914 14.53c.024.8.697-1.637 2.02-7.308a2.66 2.66 0 0 1 3.782-1.39c1.49.83 1.588 2.23.763 3.724l-5.247 9.83z" />
                                        <path
                                            d="M161.104 130.992c3.01 0 5.462 2.38 5.577 5.362l.003.218h-11.162c.001-3.01 2.382-5.46 5.362-5.576l.22-.004z" />
                                    </g>
                                    <ellipse fill="#a4afb7" cx="108.789" cy="83.823" rx="5.233" ry="6.977" />
                                    <g class="B">
                                        <path
                                            d="M108.8 81.023c2.9 0 4.255-.993 4.06-1.342-.96-1.73-2.422-2.834-4.06-2.834-1.594 0-3.022 1.046-3.982 2.695-.155.267 1.092 1.48 3.982 1.48z" />
                                        <path
                                            d="M158.6 79.778c5.473 3.196 11.174 10.04 14.06 9.766 5.987-.57 6.628-9.173 7.166-17.228.674-10.082 6.255 1.37 9.17-5.638 1.505-3.617-5.528-7.05-9.17-5.5s-4.14 4.532-4.494 7.385c-.233 1.87-1.567 13.905-2.98 13.743-2.796-.32-9.738-10.83-13.75-11.65"
                                            class="C F G" />
                                    </g>
                                    <path
                                        d="M194.848 50.46c4.93 2.962 7.9 7.255 8.917 12.88m-.695-12.957c.56.286 4.166 2.128 4.5 6.606"
                                        class="C" />
                                </g>
                                <path d="M85.828 39.033c-2.26 1.377-3.86 3.128-4.802 5.253" stroke="#fff"
                                    stroke-width="3.349" class="C" />
                                <circle cx="79.408" cy="49.399" r="1.326" class="B" />
                                <g class="D E">
                                    <g class="C">
                                        <path
                                            d="M96.774 47.475c-6.093 1.704-9.14 3.238-9.14 4.603m34.884-4.603c6.093 1.704 9.14 3.238 9.14 4.603"
                                            class="G" />
                                        <path
                                            d="M23.195 55.836c.46 3.045 2.795 1.956 3.087 1.774 1.88-1.173 3.03-1.663 4.342-.56 2.022 1.7-1.555 4.872.66 6.486 2.658 1.937 3.704-3.687 6.936-1.772S36.7 67.4 42.388 67.9M49.52 2.425c2.207.194 4.164 1.2 4.164 3.22 0 2.44-4.174 2.955-3.376 5.577.957 3.146 4.952-.44 5.836 3.482s-7.248 3.923-1.817 7.837" />
                                    </g>
                                    <g class="B">
                                        <path
                                            d="M47.182 41.33l9.2 2.19a.35.35 0 0 1 .166.586l-7 7a.35.35 0 0 1-.586-.166l-2.19-9.2a.35.35 0 0 1 .42-.42z" />
                                        <circle cx="28.907" cy="32.893" r="4.884" />
                                    </g>
                                </g>
                            </g>
                            <defs>
                                <path id="B"
                                    d="M123.276 112.706l.55 18.708h-1.3c-1.432 0-2.593 1.16-2.593 2.593s1.16 2.593 2.593 2.593h4.065a3.49 3.49 0 0 0 3.49-3.49c0-.594-1.432-9.597-1.126-20.405" />
                            </defs>
                        </svg>
                        <h3 style="font-size: 30px;" class="my-4 no-results">Área restrita <i class="fad fa-shield"></i></h3>                 
                        <div class="d-flex flex-column align-items-center justify-content-center mb-4">
                            <h3 style="font-size: 26px;" class="no-results-create mb-3">Você não possui permissão para visualizar este conteúdo.</h3>
                            <h3 style="font-size: 22px; color: #666;" class="no-results-create mb-3 mt-2">Tem dúvidas?</h3>
                            <a target="_blank" href="https://api.whatsapp.com/send?phone=5534998395367&text=Ol%C3%A1!%20Preciso%20de%20ajuda%20com%20o%20sistema%20financeiro%20da%20Yampay!" class="ml-2 btn btn-nr"><i class="fad fa-user-headset fa-lg mr-2"></i>Fale conosco</a>
                        </div>
                    </div>
                </div>
        
            </div>
        @endif

        <div style="user-select: none; padding-bottom: 150px;"
            class="d-flex flex-row align-items-center justify-content-between">

            @if(auth()->user()->id === 1)
                <div class="resultados d-flex flex-row align-items-center">
                    <select wire:model="qtd" class="form-control modal-input-cat rpp">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                    <span class="ml-3 ipp">Itens por página</span>
                </div>

                @if ($users->hasPages())
                    <div class="paginacao">
                        {{ $users->links() }}
                    </div>
                @endif

            @endif

        </div>

    </div>

</div>
