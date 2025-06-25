<template>
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Login</h2>
    <form @submit.prevent="login">
      <div class="mb-4">
        <label class="block text-sm font-medium">Username</label>
        <input v-model="username" type="text" class="w-full p-2 border rounded" required />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium">Password</label>
        <input v-model="password" type="password" class="w-full p-2 border rounded" required />
      </div>
      <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Login</button>
    </form>
    <p class="mt-4">
      Don't have an account? <a href="#" @click.prevent="register" class="text-blue-500">Register</a>
    </p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: ''
    };
  },
  methods: {
    async login() {
      console.log('Sending login request with:', { username: this.username, password: this.password });
      try {
        const response = await fetch('http://localhost:3000/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: this.username, password: this.password })
        });
        const data = await response.json();
        console.log('Login response:', data);
        if (response.ok) {
          localStorage.setItem('token', data.token);
          this.$router.push('/dashboard');
        } else {
          alert(data.error || 'Login failed');
        }
      } catch (error) {
        console.error('Login error:', error);
        alert('Login failed: Network error');
      }
    },
    async register() {
      console.log('Sending register request with:', { username: this.username, password: this.password });
      try {
        const response = await fetch('http://localhost:3000/api/register', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: this.username, password: this.password })
        });
        const data = await response.json();
        console.log('Register response:', data);
        if (response.ok) {
          alert('Registration successful. Please login.');
        } else {
          alert(data.error || 'Registration failed');
        }
      } catch (error) {
        console.error('Register error:', error);
        alert('Registration failed: Network error');
      }
    }
  }
};
</script>