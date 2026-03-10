@extends('layouts.app')
@section('title', 'Eventos')
@section('page_title', 'Gestión de Eventos')

@section('head_scripts')
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@endsection

@section('content')
<div id="vue-events-app" v-cloak>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <h2 style="font-weight:200">Listado de <strong>Eventos</strong></h2>
        <a href="/eventos/crear" class="btn btn-p"><i class="bi bi-plus-lg"></i> Nuevo Evento</a>
    </div>

    <div class="card" style="margin-bottom:20px">
        <div style="display:flex;gap:10px">
            <input type="text" v-model="searchQuery" class="form-control" placeholder="Buscar por título, código o sede..." style="flex:1">
            <button @click="fetchEvents" class="btn btn-g">
                <i class="bi bi-search" v-if="!loading"></i>
                <i class="bi bi-arrow-clockwise bi-spin" v-else></i>
                Buscar
            </button>
        </div>
    </div>

    <div class="card">
        <div v-if="loading" style="text-align:center;padding:60px;color:var(--t4)">
            <i class="bi bi-arrow-clockwise bi-spin" style="font-size:2rem"></i>
            <p style="margin-top:10px">Cargando eventos...</p>
        </div>
        <table v-else class="et">
            <thead>
                <tr>
                    <th>Evento</th><th>Sede</th><th>Fecha</th><th>Ocupación</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="ev in filteredEvents" :key="ev.id_evento">
                    <td>
                        <div style="color:var(--t1)">@{{ ev.titulo }}</div>
                        <div style="font-size:11px;color:var(--t4)">@{{ ev.categoria_nombre }} · @{{ ev.codigo_evento }}</div>
                    </td>
                    <td>@{{ ev.sede_nombre }}</td>
                    <td><span style="font-family:'JetBrains Mono',monospace;font-size:12px">@{{ formatDate(ev.fecha_inicio) }}</span></td>
                    <td>
                        <span class="obar">
                            <span class="ofill" :style="{ width: getOcupacion(ev) + '%', background: getOcupacionColor(getOcupacion(ev)) }"></span>
                        </span>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:11px">@{{ getOcupacion(ev) }}%</span>
                    </td>
                    <td>
                        <span class="sbg" :class="ev.estado_nombre.toLowerCase()">@{{ ev.estado_nombre }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:12px;flex-wrap:wrap">
                            <a :href="'/eventos/' + ev.id_evento + '/inscritos'" style="color:var(--lime);text-decoration:none;font-size:12px;font-weight:500">
                                Inscritos (@{{ ev.total_inscritos || 0 }})
                            </a>
                            <a :href="'/eventos/' + ev.id_evento + '/editar'" style="color:var(--cyan);text-decoration:none;font-size:12px">Editar</a>
                            <button v-if="ev.id_estado != 4" 
                                    @click="cancelEvent(ev)" 
                                    style="background:none;border:none;color:var(--rose);cursor:pointer;font-size:12px;font-family:inherit">
                                Cancelar
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="filteredEvents.length === 0">
                    <td colspan="6" style="text-align:center;padding:40px;color:var(--t4)">No se encontraron eventos.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<style>
[v-cloak] { display: none; }
.bi-spin {
    animation: spin 1s linear infinite;
    display: inline-block;
}
@keyframes spin { 100% { transform: rotate(360deg); } }
</style>

<script>
    const { createApp, ref, computed, onMounted } = Vue;

    createApp({
        setup() {
            const events = ref([]);
            const loading = ref(true);
            const searchQuery = ref('');

            const fetchEvents = async () => {
                loading.value = true;
                try {
                    const response = await axios.get('/api/eventos');
                    if (response.data.success) {
                        events.value = response.data.data;
                    }
                } catch (error) {
                    console.error('Error fetching events:', error);
                } finally {
                    loading.value = false;
                }
            };

            const filteredEvents = computed(() => {
                if (!searchQuery.value) return events.value;
                const q = searchQuery.value.toLowerCase();
                return events.value.filter(e => 
                    e.titulo.toLowerCase().includes(q) || 
                    e.codigo_evento.toLowerCase().includes(q) || 
                    e.sede_nombre.toLowerCase().includes(q)
                );
            });

            const formatDate = (dateStr) => {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
            };

            const getOcupacion = (ev) => {
                if (!ev.cupo_maximo) return 0;
                return Math.round((ev.total_inscritos || 0) * 100 / ev.cupo_maximo);
            };

            const getOcupacionColor = (p) => {
                if (p >= 90) return 'var(--rose)';
                if (p >= 60) return 'var(--amber)';
                return 'var(--cyan)';
            };

            const cancelEvent = async (ev) => {
                if (!confirm(`¿Estás seguro de que deseas cancelar el evento "${ev.titulo}"?`)) return;
                
                try {
                    const response = await axios.delete(`/api/eventos/${ev.id_evento}`);
                    if (response.data.success) {
                        // Refresh the list
                        await fetchEvents();
                    }
                } catch (error) {
                    console.error('Error canceling event:', error);
                    alert('Error al cancelar el evento.');
                }
            };

            onMounted(fetchEvents);

            return {
                events,
                loading,
                searchQuery,
                filteredEvents,
                formatDate,
                getOcupacion,
                getOcupacionColor,
                cancelEvent,
                fetchEvents
            };
        }
    }).mount('#vue-events-app');
</script>
@endsection

