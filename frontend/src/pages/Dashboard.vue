<template>
  <div style="max-width:1000px;margin:0 auto;padding:24px;display:grid;gap:16px">
    <header style="display:flex;justify-content:space-between;align-items:center">
      <h1 style="font-size:22px;font-weight:600">Pedidos de Viagem</h1>
      <button @click="logout" style="border:1px solid #ddd;padding:6px 12px;border-radius:8px">Sair</button>
    </header>

    <section style="border:1px solid #eee;border-radius:12px;padding:16px">
      <h2 style="font-weight:600;margin-bottom:8px">Novo Pedido</h2>
      <CreateRequestForm @created="refresh" />
    </section>

    <section style="border:1px solid #eee;border-radius:12px;padding:16px">
      <RequestTable ref="table" />
    </section>
  </div>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuth } from '@/stores/auth';
import CreateRequestForm from '@/components/CreateRequestForm.vue';
import RequestTable from '@/components/RequestTable.vue';

const auth = useAuth();
const table = ref<any>(null);

function logout(){ auth.logout(); location.href = '/login'; }
function refresh(){ table.value && table.value.fetchData && table.value.fetchData(); }

onMounted(async ()=>{ if(!auth.user) try { await auth.fetchMe(); } catch{} });
</script>
