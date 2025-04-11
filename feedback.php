<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма обратной связи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">    
    <style>
        .form-container {
            max-width: 450px;
            max-height: 620px;
            margin: 10px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            text-align: center;
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
        .table {
            margin: 10px;
        }

    </style>
</head>

<body>
    <div id="app">
        <header class="bg-light py-2">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Обратная связь</h3>
                    <button @click="logout" class="btn btn-primary">Выйти</button>
                </div>
            </div>
        </header>
        <div class="container" style="display: flex;"> 
            <div class="form-container bg-white">
                <h2 class="form-title">Форма обратной связи</h2>
                
                <form @submit.prevent="onSubmit" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">ФИО*</label>
                        <input type="text" class="form-control" id="name" v-model="name">
                        <span class="error-message text-danger" v-if="errors.name">{{ errors.name }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email*</label>
                        <input type="email" class="form-control" id="email" v-model="email">
                        <span class="error-message text-danger" v-if="errors.email">{{ errors.email }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Номер телефона</label>
                        <input type="tel" class="form-control" id="phone" v-model="phone">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание*</label>
                        <textarea id="description" class="form-control" v-model="description"></textarea>
                        <span class="error-message text-danger" v-if="errors.description">{{ errors.description }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="file" class="form-label">Файлы</label>
                        <input type="file" class="form-control" id="formFileMultiple" multiple @change="handleFileUploade($event)">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Отправить</button>
                    </div>
                    
                    <div class="success-message mt-3" v-if="successMessage">
                        {{ successMessage }}
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>#</th>
                        <th>ФИО</th>
                        <th>Email</th>
                        <th>Номер телефона</th>
                        <th>Описание</th>
                        <th>Файлы</th>
                    </thead>
                    <tbody>
                        <tr v-for="(element, index) in tableElements" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ element.name}}</td>
                            <td>{{ element.email}}</td>
                            <td>{{ element.phone}}</td>
                            <td>{{ element.description}}</td>
                            <td>{{ element.files}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        Vue.createApp({
            data() {
                return {
                    name: '',
                    email: '',
                    phone: '',
                    description: '',
                    files: '',
                    errors: {},
                    successMessage: '',
                    tableElements: []
                }
            },
            methods: {
                handleFileUploade(event) {
                    this.files = event.target.files;
                },
                checkForm() {
                    this.errors = {};
                    let isValid = true;
                    
                    if (!this.name.trim()) {
                        this.errors.name = 'ФИО обязательно для заполнения';
                        isValid = false;
                    }
                    
                    if (!this.email.trim()) {
                        this.errors.email = 'Email обязателен для заполнения';
                        isValid = false;
                    } 
                    
                    if (!this.description.trim()) {
                        this.errors.description = 'Описание обязательно для заполнения';
                        isValid = false;
                    }
                    
                    return isValid;
                },
                resetForm() {
                    this.name = '';
                    this.email = '';
                    this.phone = '';
                    this.description = '';
                    this.files = '';
                    document.getElementById('formFileMultiple').value = '';
                },
                getElements() {
                    axios.post('form.php?action=get')
                    .then(response => {
                        this.tableElements = response.data.elements;
                    });
                },
                onSubmit() {
                    if (!this.checkForm()) {
                        return;
                    }
                    this.successMessage = 'bbbbb';
                    
                    var formData = new FormData();
                    formData.append('name', this.name);
                    formData.append('email', this.email);
                    if (this.phone.trim()) {
                        formData.append('phone', this.phone);
                    }
                    formData.append('description', this.description);
                    var files = this.files;
                    var totalfiles = this.files.length;
                    for (var index = 0; index < totalfiles; index++) {
                        formData.append("files[]", files[index]);
                    }
 
                    axios.post('form.php', formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        if (response.data.success) {
                            this.successMessage = response.data.message;
                            this.resetForm();
                            this.getElements();
                        } else {
                            this.successMessage = response.data.message;
                        }
                    })
                    .catch(error => {
                        this.successMessage = error;
                    });
                },
                logout() {
                    window.location.href = 'index.php';
                }
            },
            mounted() {
                this.getElements();
            }
        }).mount('#app');
    </script>
</body>
</html>
