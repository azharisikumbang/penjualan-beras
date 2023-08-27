<?php if (false === session()->isAuthenticatedAs('pimpinan')) html_unauthorized(); ?>
<main x-data="container">
    <div class="px-4 pt-6">
        <?php if(session('temp')): ?>
            <div class="mb-4 block w-full text-base font-regular px-4 py-4 rounded-lg bg-green-500 text-white">
                <?php echo session('temp')['message'] ?>
            </div>
        <?php endif; ?>
        <template x-if="properties.messages.errors">
            <template x-for="error in properties.messages.errors">
                <?php html_alert("error.message", "error.color"); ?>
            </template>
        </template>
        <template x-if="properties.messages.normal">
            <template x-for="normal in properties.messages.normal">
                <?php html_alert("normal.message", "normal.color"); ?>
            </template>
        </template>
        <div class="mb-4 col-span-full">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white" ref="title">Ganti Password Akun</h1>
        </div>
        <form @submit.prevent="simpanData" method="post" class="w-4/12 p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div>
                <h3 class="font-semibold text-lg mb-4" x-text="properties.sites.button_title"></h3>
            </div>
            <div class="mb-4">
                <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Passwod Baru</label>
                <input x-model="properties.form.password" type="password" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" required autofocus>
            </div>
            <div class="mb-4">
                <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ketik Ulang Password Baru</label>
                <input x-model="properties.form.password_confirmation" type="password" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" required autofocus>
            </div>
            <div class="mb-2 mt-6 text-right">
                <button class="inline-flex items-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="submit">
                    Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {
            "simpanData": function () {
                this.clearMassage();

                this.postData(
                    '/api/akun/password/update',
                    this.createFormData({
                        'password': this.properties.form.password,
                        'password_confirmation': this.properties.form.password_confirmation
                    }),
                    response => {
                        this.addNormalMessage('form_response', `Berhasil! Password telah diperbaharui.`);
                        this.properties.form.password = '';
                        this.properties.form.password_confirmation = '';
                    },
                    err => {
                        this.addErrorMassage('bad_request', err.response.data.errors['message']);
                        this.properties.form.password = '';
                        this.properties.form.password_confirmation = '';
                    }
                );
            }
        };

        const utils = {
            "postData": function (to, data, callback, callbackError) {
                let that = this;
                return axios
                    .post(this.properties.sites.api_url + to, data)
                    .then(res => callback(res))
                    .catch(err => callbackError(err));
            },
            "createFormData": function (data) {
                const form = new FormData();
                for (const key in data) form.append(key, data[key]);

                return form;
            },
            "addErrorMassage": function (name, message) {
                this.addMessage(name, message);
            },
            "addNormalMessage": function (name, message) {
                this.addMessage(name, message, 'normal', 'green');
            },
            "addMessage": function (name, message, container = 'errors', color = 'red') {
                let exists = this.properties.messages[container].findIndex(item => item.name == name);

                if (exists != -1) {
                    this.properties.messages[container][exists] = { 'name': name, 'message': message, 'color': color };

                    return;
                }

                this.properties.messages[container].push({ 'name': name, 'message': message, 'color': color });
            },
            "clearMassage": function(){
                this.properties.messages.errors = [];
                this.properties.messages.normal = [];
            }
        };

        Alpine.data('container',
            () => ({
                ...actions,
                ...utils,
                "properties": {
                    "sites": {
                        "api_url": "<?= site_url() ?>"
                    },
                    "messages": {
                        "errors": [],
                        "normal": []
                    },
                    "data": {},
                    "form": {
                        'password' : '',
                        'password_confirmation': ''
                    }
                },
                "init": function() {}
            })
        );
    });
</script>

