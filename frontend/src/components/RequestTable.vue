<template>
  <div style="display:grid;gap:12px">
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:end">
      <select v-model="filters.status" style="padding:8px;border:1px solid #ddd;border-radius:8px">
        <option value="">Todos</option>
        <option value="solicitado">Solicitado</option>
        <option value="aprovado">Aprovado</option>
        <option value="cancelado">Cancelado</option>
      </select>
      <input v-model="filters.destination" placeholder="Destino" style="padding:8px;border:1px solid #ddd;border-radius:8px" />
      <select v-model="filters.date_field" style="padding:8px;border:1px solid #ddd;border-radius:8px">
        <option value="created">Criado em</option>
        <option value="travel">Datas de viagem</option>
      </select>
      <input type="date" v-model="filters.from" style="padding:8px;border:1px solid #ddd;border-radius:8px" />
      <input type="date" v-model="filters.to" style="padding:8px;border:1px solid #ddd;border-radius:8px" />
      <button @click="fetchData" style="padding:8px 12px;border-radius:8px;background:#111;color:#fff">Filtrar</button>
    </div>

    <table style="width:100%;border:1px solid #eee;border-collapse:collapse">
      <thead>
        <tr style="background:#f7f7f7">
          <th class="th">#</th>
          <th class="th">Solicitante</th>
          <th class="th">Destino</th>
          <th class="th">Ida</th>
          <th class="th">Volta</th>
          <th class="th">Status</th>
          <th class="th" v-if="isAdmin">Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="r in rows" :key="r.id" style="border-top:1px solid #eee">
          <td class="td">{{ r.id }}</td>
          <td class="td">{{ r.requester_name }}</td>
          <td class="td">{{ r.destination }}</td>
          <td class="td">{{ format(r.departure_date) }}</td>
          <td class="td">{{ format(r.return_date) }}</td>
          <td class="td">{{ r.status }}</td>
          <td class="td" v-if="isAdmin">
            <button style="padding:4px 8px;border:1px solid #ddd;border-radius:6px;margin-right:6px" @click="update(r,'aprovado')">Aprovar</button>
            <button style="padding:4px 8px;border:1px solid #ddd;border-radius:6px" @click="update(r,'cancelado')">Cancelar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-if="error" style="color:#c00">{{ error }}</p>
  </div>
</template>
<script setup lang="ts">
import { onMounted, reactive, ref, computed } from 'vue';
import http from '@/api/http';
import { useAuth } from '@/stores/auth';

const rows = ref<any[]>([]);
const error = ref('');
const filters = reactive({ status: '', destination: '', date_field: 'created', from: '', to: '' });
const auth = useAuth();
const isAdmin = computed(() => auth.user?.is_admin);

function format(d?: string){ return d ? new Date(d).toLocaleDateString() : ''; }

async function fetchData(){
  error.value='';
  try {
    const { data } = await http.get('/travel-requests', { params: { ...filters, status: filters.status || undefined }});
    rows.value = data.data ?? data;
  } catch(e:any){ error.value = e?.response?.data?.message || 'Falha ao carregar'; }
}

async function update(row:any, status:'aprovado'|'cancelado'){
  try {
    await http.patch(`/travel-requests/${row.id}/status`, { status });
    await fetchData();
  } catch(e:any){
    error.value = e?.response?.data?.message || 'Falha ao atualizar status';
  }
}

onMounted(async () => {
  try {
    if(!auth.user) { await auth.fetchMe(); }
  } catch {}
  fetchData();
});
</script>
<style scoped>
.th,.td{ padding:8px; text-align:left; }
</style>
