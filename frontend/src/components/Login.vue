<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 flex items-center justify-center p-6">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-emerald-300 to-teal-400 rounded-full opacity-20 blur-3xl"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-cyan-300 to-blue-400 rounded-full opacity-20 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
      <!-- Header Section -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl mb-6 shadow-lg transform hover:scale-105 transition-transform duration-300">
          <span class="text-4xl text-white">🌱</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Crops Monitoring System</h1>
        <p class="text-gray-600">
          {{ isLogin ? 'Welcome back! Please sign in to continue.' : 'Create your account to get started.' }}
        </p>
      </div>

      <!-- Login/Register Card -->
      <div class="bg-white rounded-2xl shadow-2xl p-8 backdrop-blur-sm bg-opacity-95 hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-1">
        <!-- Tab Headers -->
        <div class="flex mb-8 bg-gray-100 rounded-xl p-1">
          <button
            @click="isLogin = true"
            :class="[
              'flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-300',
              isLogin
                ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg'
                : 'text-gray-600 hover:text-gray-800'
            ]"
          >
            <span class="flex items-center justify-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m0 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
              </svg>
              Login
            </span>
          </button>
          <button
            @click="isLogin = false"
            :class="[
              'flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-300',
              !isLogin
                ? 'bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-lg'
                : 'text-gray-600 hover:text-gray-800'
            ]"
          >
            <span class="flex items-center justify-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
              </svg>
              Register
            </span>
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Username Field -->
          <div class="group">
            <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-emerald-600 transition-colors duration-200">
              <span class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Username
              </span>
            </label>
            <div class="relative">
              <input
                v-model="username"
                type="text"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100 group-hover:shadow-md"
                placeholder="Enter your username"
                required
              />
              <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 opacity-0 group-focus-within:opacity-10 transition-opacity duration-200 pointer-events-none"></div>
            </div>
          </div>

          <!-- Password Field -->
          <div class="group">
            <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-emerald-600 transition-colors duration-200">
              <span class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 0h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Password
              </span>
            </label>
            <div class="relative">
              <input
                v-model="password"
                type="password"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100 group-hover:shadow-md"
                placeholder="Enter your password"
                required
              />
              <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 opacity-0 group-focus-within:opacity-10 transition-opacity duration-200 pointer-events-none"></div>
            </div>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="isLoading"
            :class="[
              'group/btn relative w-full px-6 py-4 font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 overflow-hidden text-white',
              isLogin
                ? 'bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700'
                : 'bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700',
              isLoading ? 'cursor-not-allowed opacity-75' : ''
            ]"
          >
            <div :class="[
              'absolute inset-0 bg-gradient-to-r opacity-0 group-hover/btn:opacity-100 transition-opacity duration-200',
              isLogin 
                ? 'from-emerald-600 to-teal-700' 
                : 'from-purple-600 to-pink-700'
            ]"></div>
            <span class="relative flex items-center justify-center">
              <template v-if="isLoading">
                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Processing...
              </template>
              <template v-else>
                {{ isLogin ? 'Sign In' : 'Create Account' }}
                <svg class="w-5 h-5 ml-2 group-hover/btn:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
              </template>
            </span>
          </button>
        </form>

        <!-- Additional Options -->
        <div class="mt-8 pt-6 border-t border-gray-100">
          <div class="text-center">
            <p class="text-sm text-gray-600 mb-4">
              {{ isLogin ? "Don't have an account?" : "Already have an account?" }}
            </p>
            <button
              @click="toggleMode"
              class="text-emerald-600 hover:text-emerald-700 font-semibold text-sm hover:underline transition-all duration-200"
            >
              {{ isLogin ? 'Create new account' : 'Sign in instead' }}
            </button>
          </div>
          
          <div v-if="isLogin" class="text-center mt-4">
            <a href="#" class="text-xs text-gray-500 hover:text-gray-700 transition-colors duration-200">
              Forgot your password?
            </a>
          </div>
        </div>
      </div>

      <!-- Features Preview -->
      <div class="mt-8 grid grid-cols-3 gap-4 text-center">
        <div class="bg-white bg-opacity-60 backdrop-blur-sm rounded-xl p-4 hover:bg-opacity-80 transition-all duration-300">
          <div class="text-2xl mb-2">📊</div>
          <div class="text-xs text-gray-600 font-medium">Real-time Monitoring</div>
        </div>
        <div class="bg-white bg-opacity-60 backdrop-blur-sm rounded-xl p-4 hover:bg-opacity-80 transition-all duration-300">
          <div class="text-2xl mb-2">📈</div>
          <div class="text-xs text-gray-600 font-medium">Data Analytics</div>
        </div>
        <div class="bg-white bg-opacity-60 backdrop-blur-sm rounded-xl p-4 hover:bg-opacity-80 transition-all duration-300">
          <div class="text-2xl mb-2">🎛️</div>
          <div class="text-xs text-gray-600 font-medium">Smart Controls</div>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center mt-8">
        <p class="text-xs text-gray-500">© 2025 Smart Agriculture System. Built for precision farming.</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginPage',
  data() {
    return {
      username: '',
      password: '',
      isLogin: true,
      isLoading: false
    };
  },
  methods: {
    async handleSubmit() {
      this.isLoading = true;
      
      console.log(`Sending ${this.isLogin ? 'login' : 'register'} request with:`, { 
        username: this.username, 
        password: this.password 
      });
      
      try {
        const endpoint = this.isLogin ? '/api/login' : '/api/register';
        const response = await fetch(`http://localhost:3000${endpoint}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ 
            username: this.username, 
            password: this.password 
          })
        });
        
        const data = await response.json();
        console.log(`${this.isLogin ? 'Login' : 'Register'} response:`, data);
        
        if (response.ok) {
          if (this.isLogin) {
            localStorage.setItem('token', data.token);
            this.$router.push('/dashboard');
          } else {
            alert('Registration successful! Please login.');
            this.isLogin = true;
            this.password = '';
          }
        } else {
          alert(data.error || `${this.isLogin ? 'Login' : 'Registration'} failed`);
        }
      } catch (error) {
        console.error(`${this.isLogin ? 'Login' : 'Register'} error:`, error);
        alert(`${this.isLogin ? 'Login' : 'Registration'} failed: Network error`);
      } finally {
        this.isLoading = false;
      }
    },
    
    toggleMode() {
      this.isLogin = !this.isLogin;
      this.password = '';
    }
  }
};
</script>

<style scoped>
/* Additional hover effects and animations */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

/* Custom animations for smooth interactions */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

/* Enhanced shadow effects */
.shadow-3xl {
  box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
}

/* Smooth focus transitions */
.group:focus-within .group-focus-within\:opacity-10 {
  opacity: 0.1;
}

/* Button hover animations */
.group\/btn:hover .group-hover\/btn\:translate-x-1 {
  transform: translateX(0.25rem);
}

.group\/btn:hover .group-hover\/btn\:opacity-100 {
  opacity: 1;
}
</style>