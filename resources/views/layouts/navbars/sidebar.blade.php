<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg" id="mySidebar">
    <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
    <div class="logo">
        {{-- <button id="toggleButton">Mostrar/Ocultar</button> --}}
        <a href="#" class="simple-text logo-normal">
            {{ __('Gestor de Procesos') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">

            {{-- Dashboard --}}
            {{-- @can('modulo_dashboard')
                <li
                    class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="material-icons">dashboard</i>
                <p>{{ __('Dashboard') }}</p>
            </a>
            </li>
            @endcan--}}
            {{-- ------------------------------------------ --}}

            <!-- {{-- Modulo de Cirugia --}}
            {{-- @can('modulo_cirugia')
                <li
                    class="nav-item {{ $activePage == 'asignacionCamaCirugia' || $activePage == 'compraCirugia' || $activePage == 'reservaSangre' ? ' active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
                <i class="material-icons">business</i>
                <p>{{ __('Cirugia') }}
                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse show" id="laravelExample">
                <ul class="nav">
@can('modulo_cirugia_cama')
                    <li class="nav-item{{ $activePage == 'asignacionCamaCirugia' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('compras.index') }}">
                            <i class="material-icons">airline_seat_individual_suite</i>
                            <span class="sidebar-normal">{{ __('Asignacion De Camas') }} </span>
                        </a>
                    </li>
@endcan

@can('modulo_cirugia_compra')
                    <li class="nav-item{{ $activePage == 'compraCirugia' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('compras.index') }}">
                            <i class="material-icons">monetization_on</i>
                            <span class="sidebar-normal"> {{ __('Compras') }} </span>
                        </a>
                    </li>
@endcan

@can('modulo_cirugia_sangre')
                    <li class="nav-item{{ $activePage == 'reservaSangre' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('compras.index') }}">
                            <i class="material-icons">opacity</i>
                            <span class="sidebar-normal"> {{ __('Reserva de Sangre') }} </span>
                        </a>
                    </li>
@endcan
                </ul>
            </div>
            </li>
@endcan--}}
            {{-- ------------------------------------------ --}} -->

            {{-- Modulo de Facturacion --}}
            @can('modulo_facturacion')
            <li class="nav-item {{ $activePage == 'facturacion' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#laravelExample2" aria-expanded="true">
                    <i class="material-icons">hot_tub</i>
                    <p>{{ __('Facturacion') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse{{ $activePage == 'facturacionCarpetas' || $activePage == 'facturacionVerPacientes' || $activePage == 'facturacionRips' ? ' show' : '' }}" id="laravelExample2">
                    @can('facturacion_pacientes_admitidos')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'facturacionCarpetas' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('carpetas.index') }}">
                                <i class="material-icons">people</i>
                                <span class="sidebar-normal">
                                    {{ __('Pacientes Admitidos') }} </span>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('facturacion_soportes')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'facturacionVerPacientes' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('carpetas.pacientes') }}">
                                <i class="material-icons">picture_as_pdf</i>
                                <span class="sidebar-normal"> {{ __('Soportes') }} </span>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    <!-- @can('facturacion_rips')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'facturacionRips' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('carpetas.rips') }}">
                                <i class="material-icons">library_books</i>
                                <span class="sidebar-normal"> {{ __('Rips') }} </span>
                            </a>
                        </li>
                    </ul>
                    @endcan -->
                </div>
            </li>
            @endcan
            {{-- ------------------------------------------ --}}

            <!--{{-- Modulo de Farmacia --}}
@can('modulo_farmacia')
            <li class="nav-item {{ $activePage == 'Farmacia' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#laravelExample4" aria-expanded="true">
                    <i class="material-icons">vignette</i>
                    <p>{{ __('Farmacia') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="laravelExample4">
@can('farmacia_despacho')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'FarmaciaDespacho' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('despacho.index') }}">
                                <i class="material-icons">exit_to_app</i>
                                <span class="sidebar-normal"> {{ __('Despacho') }} </span>
                            </a>
                        </li>
                    </ul>
@endcan
                </div>
            </li>
@endcan
            {{-- ------------------------------------------ --}} -->

            <!-- {{-- Modulo de Laboratorio --}}
@can('modulo_laboratorio')
            <li class="nav-item {{ $activePage == 'laboratorio' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#laravelExample3" aria-expanded="true">
                    <i class="material-icons">healing</i>
                    <p>{{ __('Laboratorio') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="laravelExample3">
@can('laboratorio_pedidos')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'laboratorioPedidos' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('pedidos.index') }}">
                                <i class="material-icons">card_travel</i>
                                <span class="sidebar-normal"> {{ __('Pedido De Insumo') }} </span>
                            </a>
                        </li>
                    </ul>
@endcan
@can('laboratorio_entregas')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'laboratorio' ? ' active' : '' }}">
                            <a class="nav-link" href="#">
                                <i class="material-icons">colorize</i>
                                <span class="sidebar-normal"> {{ __('Entrega De Insumo') }} </span>
                            </a>
                        </li>
                    </ul>
@endcan
                </div>
            </li>
@endcan
            {{-- ------------------------------------------ --}} -->

            {{-- Modulo de Reportes --}}
            @can('modulo_reportes')
            <li class="nav-item {{ $activePage == 'Reportes' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#laravelExample5" aria-expanded="false">
                    <i class="material-icons">format_size</i>
                    <p>{{ __('Reportes') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $activePage == 'ReportesIndexColsaludCoosalud' || $activePage == 'ReportesIndexColsaludInterconsultas' ? ' show' : '' }}" id="laravelExample5">
                    @can('reportes_colsalud')
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#colsaludSubmenu" aria-expanded="false">
                                <i class="material-icons">copyright</i>
                                <p>{{ __('Colsalud') }}
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <div class="collapse{{ $activePage == 'ReportesIndexColsaludCoosalud' || $activePage == 'ReportesIndexColsaludInterconsultas' ? ' show' : '' }}" id="colsaludSubmenu">
                                <ul class="nav">
                                    @can('reportes_colsalud_subsidiadoAC')
                                    <li class="nav-item{{ $activePage == 'ReportesIndexColsaludCoosalud' ? ' active' : '' }}">
                                        <a class="nav-link" href="{{ route('coosalud.index') }}">
                                            <i class="material-icons">cloud_download</i>
                                            <span class="sidebar-normal">
                                                {{ __('COOSALUD - CAPITA') }} </span>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('reportes_colsalud_interconsultas')
                                    <li class="nav-item{{ $activePage == 'ReportesIndexColsaludInterconsultas' ? ' active' : '' }}">
                                        <a class="nav-link" href="{{ route('interconsultas.index') }}">
                                            <i class="material-icons">list</i>
                                            <span class="sidebar-normal">
                                                {{ __('INTERCONSULTAS') }} </span>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    </ul>
                    @endcan
                    @can('reportes_uci')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'ReportesUci' ? ' active' : '' }}">
                            <a class="nav-link" data-toggle="collapse" href="#uciSubmenu" aria-expanded="false">
                                <i class="material-icons">trending_up</i>
                                <p>{{ __('Cuidado Critico') }}
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <div class="collapse" id="uciSubmenu">
                                <ul class="nav">
                                    @can('reportes_uci_digitalizacion')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('reportes.uciEgresos') }}">
                                            <i class="material-icons">list</i>
                                            <span class="sidebar-normal">
                                                {{ __('Digitalizacion') }} </span>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('reportes_uci_opcion2')
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <i class="material-icons">list</i>
                                            <span class="sidebar-normal">
                                                {{ __('Opción 2') }} </span>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    </ul>
                    @endcan
                    @can('reportes_cardiosalud')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'ReportesCardiosalud' ? ' active' : '' }}">
                            <a class="nav-link" data-toggle="collapse" href="#CardiosaludSubmenu" aria-expanded="false">
                                {{-- <a class="nav-link" href="{{ route('pedidos.index') }}">
                                --}}
                                <i class="material-icons">card_travel</i>
                                <p>{{ __('Cardiosalud') }}
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <div class="collapse" id="CardiosaludSubmenu">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <i class="material-icons">list</i>
                                            <span class="sidebar-normal">
                                                {{ __('Opción 1') }} </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <i class="material-icons">list</i>
                                            <span class="sidebar-normal">
                                                {{ __('Opción 2') }} </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    @endcan
                    @can('reportes_cid')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'ReportesCid' ? ' active' : '' }}">
                            <a class="nav-link" href="#">
                                <i class="material-icons">colorize</i>
                                <span class="sidebar-normal">
                                    {{ __('Centro De Imagenes') }} </span>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </div>
            </li>
            @endcan
            {{-- ------------------------------------------ --}}

            {{-- Modulo de Parametrizaciones --}}
            @can('modulo_parametrizaciones')
            <li class="nav-item {{ $activePage == 'Farmacia' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#laravelExample6" aria-expanded="true">
                    <i class="material-icons">receipt</i>
                    <p>{{ __('Parametrizaciones') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="laravelExample6">
                    @can('parametrizaciones_ValorVariable')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'ParametrizacionesIndex' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('parametrizacion.index') }}">
                                <i class="material-icons">exit_to_app</i>
                                <span class="sidebar-normal"> {{ __('Valor Variable') }}
                                </span>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </div>
            </li>
            @endcan
            {{-- ------------------------------------------ --}}

            {{-- Modulo de Consulta Externa --}}
            @can('modulo_consulta_externa')
            <li class="nav-item {{ $activePage == 'CitasColsalud' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#consulta_externa" aria-expanded="true">
                    <i class="material-icons">supervisor_account</i>
                    <p>{{ __('Consulta Externa') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse{{ $activePage == 'FoliosColsalud' || $activePage == 'CitasColsalud'  ? ' show' : '' }}" id="consulta_externa">
                    @can('consulta_externa_folios')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'FoliosColsalud' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('folio_index') }}">
                                <i class="material-icons">import_contacts</i>
                                <span class="sidebar-normal"> {{ __('Folios') }} </span>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('consulta_externa_citas')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'CitasColsalud' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('citas_index') }}">
                                <i class="material-icons">date_range</i>
                                <span class="sidebar-normal"> {{ __('Citas') }} </span>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </div>
            </li>
            @endcan
            {{-- ------------------------------------------ --}}

            {{-- Modulo de Administraccion --}}
            @can('modulo_administraccion')
            <li class="nav-item {{ $activePage == 'administraccion' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#administraccion" aria-expanded="true">
                    <i class="material-icons">person_add</i>
                    <p>{{ __('Administracción') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse{{  $activePage == 'logs' || $activePage == 'users' || $activePage == 'permissions' || $activePage == 'roles'  ? ' show' : '' }}" id="administraccion">
                    @can('user_index')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'users' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="material-icons">content_paste</i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('permission_index')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'permissions' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('permissions.index') }}">
                                <i class="material-icons">bubble_chart</i>
                                <p>{{ __('Permisos') }}</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('role_index')
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'roles' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('roles.index') }}">
                                <i class="material-icons">location_ons</i>
                                <p>{{ __('Roles') }}</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'logs' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('log.index') }}">
                                <i class="material-icons">description</i>
                                <p>{{ __('Auditoria') }}</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </div>
            </li>
            @endcan
        </ul>
    </div>
</div>