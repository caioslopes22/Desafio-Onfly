<template>
  <form @submit.prevent="save" style="display:grid;gap:8px">
    <input v-model="form.requester_name" placeholder="Nome do solicitante" class="in" />
    <input v-model="form.destination" placeholder="Destino" class="in" />
    <input type="date" v-model="form.departure_date" class="in" />
    <input type="date" v-model="form.return_date" class="in" />
    <button :disabled="loading" style="padding:8px;border-radius:8px;background:#111;color:#fff">
      {{ loading? 'Salvando...' : 'Salvar' }}
    </button>
    <p v-if="error" style="color:#c00">{{ error }}</p>
  </form>
</template>
<script setup lang="ts">
import { reactive, ref } from 'vue';
import http from '@/api/http';

const emit = defineEmits(['created']);
const form = reactive({ requester_name:'', destination:'', departure_date:'', return_date:'' });
const loading = ref(false); const error = ref('');

async function save(){
  loading.value = true; error.value='';
  try { await http.post('/travel-requests', form); emit('created'); Object.assign(form,{ requester_name:'', destination:'', departure_date:'', return_date:'' }); }
  catch(e:any){ error.value = e?.response?.data?.message || 'Erro ao criar'; }
  finally{ loading.value=false; }
}
</script>
<style scoped>
.in{ padding:8px; border:1px solid #ddd; border-radius:8px; }
</style>
