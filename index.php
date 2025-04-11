<!DOCTYPE html>
<html lang="ru">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Авторизация</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">    
<style>
   .auth-container {
      max-width: 500px;
      margin: 10px auto;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
   }
   .nav-link.active {
      font-weight: bold;
   }
   </style>
</head>

<body>
   <div id="app">
      <div class="auth-container bg-white">
            <h2 class="text-center mb-4">Авторизация</h2>
            
            <div v-if="message" class="mt-3 alert alert-danger">
               {{ message }}
            </div>

            <ul class="nav nav-tabs mb-4">
               <li class="nav-item">
                  <button  class="nav-link" :class="{ 'active': activeTab === 'login' }" @click="switchTab('login')">Вход</button>
               </li>
               <li class="nav-item">
                  <button class="nav-link" :class="{ 'active': activeTab === 'register' }" @click="switchTab('register')">Регистрация</button>
               </li>
            </ul>
            
            <form @submit.prevent="submitForm">
               <div class="mb-3">
                  <div class="form-floating">
                     <input type="email" class="form-control" id="email" placeholder="Email" v-model="form.email" required>
                     <label for="email">Email</label>
                  </div>
               </div>
                
               <div class="mb-4">
                  <div class="form-floating">
                     <input type="password" class="form-control" id="password" placeholder="Пароль" v-model="form.password" minlength="6" required>
                     <label for="password">Пароль</label>
                  </div>
               </div>

               <div v-if="activeTab === 'register'" class="mb-4">
                  <div class="form-floating">
                     <input type="password" class="form-control" id="confirm_password" placeholder="Повторите пароль" v-model="form.confirm_password" required>
                     <label for="confirm_password">Повторите пароль</label>
                  </div>
                  <div v-if="errors.confirm_password" class="error-message text-danger">
                     {{ errors.confirm_password }}
                  </div>
               </div>
                
               <button type="submit" class="btn btn-primary col-12">
                  {{ activeTab === 'login' ? 'Войти' : 'Зарегистрироваться' }}
               </button>
            </form>
      </div>
   </div>
   <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<script>
   Vue.createApp({
      data() {
         return {
            activeTab: 'login',
            form: {
               email: '',
               password: '',
               confirm_password: ''
            },
            errors: {},
            message: ''
         }
      },
      methods: {
         switchTab(tab) {
            this.activeTab = tab;
            this.resetForm();
         },
         resetForm() {
            this.form = {
	            email: '',
	            password: '',
	            confirm_password: ''
            };
            this.errors = {};
            this.message = '';
         },
         validateForm() {
            this.errors = {};
            let isValid = true;
                    
            if (this.activeTab === 'register') {
               if (this.form.password !== this.form.confirm_password) {
                  this.errors.confirm_password = 'Пароли не совпадают';
                  isValid = false;
               }
            }
                    
            return isValid;
         },
         submitForm() {
            if (!this.validateForm()) {
               return;
            }

            this.message = '';
                    
            var url = 'auth.php?action=' + this.activeTab;
            var formData = new FormData();
            formData.append('email', this.form.email);  
            formData.append('password', this.form.password); 
            axios.post(url, formData)
               .then(response => {
                  if (response.data.success) {        
                     if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                     } 
                  } else {
                     this.message = response.data.message || 'Произошла ошибка';
                  }
               })
               .catch(error => {
                  this.message = 'Ошибка соединения с сервером';
               });
         }
      }
   }).mount('#app');
   </script>
</body>
</html>