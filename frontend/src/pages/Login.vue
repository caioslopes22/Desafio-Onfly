<template>
  <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
    <form @submit.prevent="submit" style="width:100%;max-width:360px;display:grid;gap:12px">
      <h1 style="font-size:22px;font-weight:600">Login</h1>
      <input v-model="email" placeholder="Email" style="padding:10px;border:1px solid #ddd;border-radius:8px" />
      <input v-model="password" type="password" placeholder="Senha" style="padding:10px;border:1px solid #ddd;border-radius:8px" />
      <button :disabled="loading" style="padding:10px;border-radius:8px;background:#111;color:#fff">
        {{ loading ? 'Entrando...' : 'Entrar' }}
      </button>
      <p v-if="error" style="color:#c00">{{ error }}</p>
    </form>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/stores/auth';

const email = ref('admin@example.com');
const password = ref('secret');
const loading = ref(false);
const error = ref('');
const auth = useAuth();
const router = useRouter();

const submit = async () => {
  loading.value = true; error.value='';
  try { await auth.login(email.value, password.value); router.push('/'); }
  catch (e:any) { error.value = e?.response?.data?.message || 'Falha no login'; }
  finally { loading.value = false; }
}
</script>
