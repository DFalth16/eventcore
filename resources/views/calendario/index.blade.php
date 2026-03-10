@extends('layouts.app')

@section('title', 'Calendario')
@section('page_title', 'Calendario de Eventos')

@section('head_scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
@endsection

@section('extra_styles')
<style>
    :root {
        --fc-border-color: var(--border);
        --fc-daygrid-event-dot-width: 8px;
        --fc-today-bg-color: rgba(0, 212, 255, 0.05);
    }
    .fc { font-family: 'Outfit', sans-serif; background: var(--c1); border: 1px solid var(--border); border-radius: var(--r); padding: 20px; color: var(--t1); }
    .fc .fc-toolbar-title { font-size: 1.2rem; font-weight: 400; color: var(--t1); }
    .fc .fc-button-primary { background: var(--c2); border: 1px solid var(--border2); color: var(--t1); text-transform: capitalize; font-size: 0.9rem; transition: all 0.3s var(--ease); }
    .fc .fc-button-primary:hover { background: var(--border2); border-color: var(--cyan); }
    .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background: var(--cyan); color: #000; border-color: var(--cyan); shadow: 0 0 15px var(--cyan); }
    .fc-theme-standard td, .fc-theme-standard th { border-color: var(--border); }
    .fc-day-today { background: var(--fc-today-bg-color) !important; }
    .fc-event { cursor: pointer; border: none !important; padding: 2px 5px; border-radius: 4px; font-size: 0.85rem; font-weight: 500; }

    /* List view dark mode fixes */
    .fc-list { background: var(--c1) !important; border-color: var(--border) !important; }
    .fc-list-day-cushion { background: var(--c2) !important; color: var(--t1) !important; }
    .fc-list-event:hover td { background: rgba(255,255,255,0.03) !important; }
    .fc-list-table { border-color: var(--border) !important; }
    .fc-list-table tr td { border-color: var(--border) !important; }
    .fc-list-event-title a { color: var(--t1) !important; text-decoration: none; }
    .fc-list-empty { background: var(--c1); color: var(--t3); }

    /* Year view (multi-month) dark mode fixes - BULLETPROOF */
    .fc-multimonth { background: var(--c1) !important; }
    .fc-multimonth-title { color: var(--cyan) !important; font-weight: 500 !important; font-size: 1.1rem !important; }
    .fc-multimonth-daygrid-table, .fc-multimonth-month { border-color: var(--border) !important; }
    .fc-multimonth-daygrid-body, .fc-multimonth td, .fc-multimonth th { border-color: var(--border) !important; }
    .fc-multimonth-column-header-cell, .fc-multimonth .fc-col-header-cell { background: var(--c2) !important; color: var(--t1) !important; border-color: var(--border) !important; }
    .fc-daygrid-day-number { color: var(--t1) !important; }
    
    /* Force background on cells */
    .fc-multimonth .fc-daygrid-day, 
    .fc-multimonth .fc-daygrid-day-frame,
    .fc-multimonth .fc-daygrid-day-bg,
    .fc-multimonth .fc-daygrid-day-top { background: var(--c1) !important; color: var(--t1) !important; }
    
    .fc-multimonth-month { background: var(--c1) !important; }
    .fc-multimonth-daygrid-table { background: var(--c1) !important; }
    
    /* Modal styles */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 2000; }
    .modal-content { background: var(--c1); border: 1px solid var(--border2); border-radius: var(--r); width: 90%; max-width: 500px; padding: 30px; position: relative; animation: modal-in 0.4s var(--ease); }
    @keyframes modal-in { from { opacity: 0; transform: scale(0.9) translateY(20px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .modal-close { position: absolute; top: 15px; right: 15px; background: none; border: none; color: var(--t3); font-size: 24px; cursor: pointer; transition: color 0.3s; }
    .modal-close:hover { color: var(--rose); }
    .evt-detail { margin-top: 20px; }
    .evt-label { font-size: 10px; color: var(--t4); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
    .evt-val { font-size: 14px; color: var(--t1); margin-bottom: 15px; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center; background: var(--c1); padding: 15px; border-radius: var(--r); border: 1px solid var(--border)">
    <div style="font-size: 13px; color: var(--t2); font-weight: 500">Ir a:</div>
    <select id="jumpMonth" class="form-control" style="width: 140px; margin-bottom: 0">
        <option value="0">Enero</option><option value="1">Febrero</option><option value="2">Marzo</option>
        <option value="3">Abril</option><option value="4">Mayo</option><option value="5">Junio</option>
        <option value="6">Julio</option><option value="7">Agosto</option><option value="8">Septiembre</option>
        <option value="9">Octubre</option><option value="10">Noviembre</option><option value="11">Diciembre</option>
    </select>
    <select id="jumpYear" class="form-control" style="width: 100px; margin-bottom: 0">
        @for($y = date('Y')-2; $y <= date('Y')+5; $y++)
            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
    </select>
    <button class="btn btn-p" onclick="jumpToDate()" style="padding: 10px 15px"><i class="bi bi-arrow-right-short"></i> Ir</button>
</div>

<div id='calendar'></div>

<div class="modal-overlay" id="evtModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <h2 id="mTitle" style="font-weight:400; color:var(--cyan)"></h2>
        <div class="evt-detail">
            <div class="evt-label">Ubicación / Sede</div>
            <div class="evt-val" id="mSede"></div>
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px">
                <div>
                    <div class="evt-label">Inicio</div>
                    <div class="evt-val" id="mStart"></div>
                </div>
                <div>
                    <div class="evt-label">Fin</div>
                    <div class="evt-val" id="mEnd"></div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px">
                <div>
                    <div class="evt-label">Estado</div>
                    <div class="evt-val"><span id="mEstado" class="sbg"></span></div>
                </div>
                <div>
                    <div class="evt-label">Ocupación</div>
                    <div class="evt-val" id="mCupo"></div>
                </div>
            </div>

            <div class="evt-label">Descripción</div>
            <div class="evt-val" id="mDesc" style="font-size: 13px; line-height: 1.6; color: var(--t2)"></div>
        </div>
        <div style="margin-top: 20px; text-align: right">
            <button class="btn btn-g" onclick="closeModal()">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal para Crear Evento -->
<div class="modal-overlay" id="addModal">
    <div class="modal-content" style="max-width: 600px">
        <button class="modal-close" onclick="closeAddModal()">&times;</button>
        <h2 style="font-weight:400; color:var(--cyan); margin-bottom: 20px"><i class="bi bi-plus-circle"></i> Nuevo Evento</h2>
        
        <form action="{{ route('calendario.store') }}" method="POST">
            @csrf
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 15px">
                <div class="form-group" style="grid-column: span 2">
                    <label class="evt-label">Título del Evento</label>
                    <input type="text" name="titulo" class="form-control" required placeholder="Ej: Conferencia Tech 2026">
                </div>
                
                <div class="form-group">
                    <label class="evt-label">Categoría</label>
                    <select name="id_categoria" class="form-control" required>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id_categoria }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="evt-label">Sede</label>
                    <select name="id_sede" class="form-control" required>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id_sede }}">{{ $sede->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="evt-label">Fecha Inicio</label>
                    <input type="datetime-local" id="f_start" name="fecha_inicio" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="evt-label">Fecha Fin</label>
                    <input type="datetime-local" id="f_end" name="fecha_fin" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="evt-label">Cupo Máximo</label>
                    <input type="number" name="cupo_maximo" class="form-control" value="50" required min="1">
                </div>

                <div class="form-group" style="grid-column: span 2">
                    <label class="evt-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" placeholder="Opcional..."></textarea>
                </div>
            </div>

            <div style="margin-top: 25px; text-align: right; display: flex; gap: 10px; justify-content: flex-end">
                <button type="button" class="btn btn-g" onclick="closeAddModal()">Cancelar</button>
                <button type="submit" class="btn btn-p">Crear Evento</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let calendar;
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            selectable: true,
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'multiMonthYear,dayGridMonth,timeGridWeek,dayGridDay,listMonth'
            },
            buttonText: {
                today: 'Hoy',
                year: 'Año',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            events: '/api/calendario/eventos',
            eventClick: function(info) {
                showModal(info.event);
            },
            select: function(info) {
                showAddModal(info.startStr, info.endStr);
            }
        });
        calendar.render();

        // Set current month in select
        document.getElementById('jumpMonth').value = new Date().getMonth();
    });

    function jumpToDate() {
        const m = document.getElementById('jumpMonth').value;
        const y = document.getElementById('jumpYear').value;
        calendar.gotoDate(new Date(y, m, 1));
    }

    function showModal(event) {
        document.getElementById('mTitle').innerText = event.title;
        document.getElementById('mSede').innerText = event.extendedProps.sede;
        document.getElementById('mStart').innerText = new Date(event.start).toLocaleString();
        document.getElementById('mEnd').innerText = event.end ? new Date(event.end).toLocaleString() : 'N/A';
        document.getElementById('mDesc').innerText = event.extendedProps.descripcion || 'Sin descripción';
        document.getElementById('mCupo').innerText = event.extendedProps.inscritos + ' / ' + event.extendedProps.cupo + ' asistentes';
        
        const est = document.getElementById('mEstado');
        est.innerText = event.extendedProps.estado;
        est.className = 'sbg ' + event.extendedProps.estado.toLowerCase();

        document.getElementById('evtModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('evtModal').style.display = 'none';
    }

    function showAddModal(start, end) {
        // Formatear fechas para input datetime-local (YYYY-MM-DDTHH:MM)
        const formatDT = (dateStr) => {
            const d = new Date(dateStr);
            // Ajustar a zona horaria local si es necesario, pero startStr de FC suele venir en formato ISO compatible
            if (dateStr.length <= 10) { // Solo fecha
               return dateStr + 'T09:00';
            }
            return dateStr.substring(0, 16);
        };

        document.getElementById('f_start').value = formatDT(start);
        
        // Si la selección termina en el mismo día a medianoche (end de FC es exclusivo), ajustamos
        let endDate = new Date(end);
        if (formatDT(start).substring(0,10) === formatDT(end).substring(0,10)) {
             endDate.setHours(endDate.getHours() + 1); // Añadir 1 hora por defecto si es selección puntual
        }

        document.getElementById('f_end').value = formatDT(endDate.toISOString());
        document.getElementById('addModal').style.display = 'flex';
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }
</script>
@endsection
