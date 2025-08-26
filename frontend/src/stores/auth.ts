import { defineStore } from 'pinia';
import http from '@/api/http';

export const useAuth = defineStore('auth', {
  state: () => ({ user: null as any, token: localStorage.getItem('token') as string | null }),
  actions: {
    async login(email: string, password: string) {
      const { data } = await http.post('/auth/login', { email, password });
      this.token = data.access_token; this.user = data.user;
      localStorage.setItem('token', this.token!);
    },
    async fetchMe() { const { data } = await http.get('/auth/me'); this.user = data; },
    logout() { this.user = null; this.token = null; localStorage.removeItem('token'); }
  }
});
