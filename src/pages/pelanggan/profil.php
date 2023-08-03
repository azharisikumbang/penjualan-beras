<?php

if (false === session()->isAuthenticatedAs('pelanggan')) html_unauthorized();

$pelanggan = app()->getManager()->getService('KelolaPelanggan')->cariBerdasarkanAkun(session()->auth());
if(is_null($pelanggan)) html_not_found();

?>
<main x-data="container">
    <div class="p-6 bg-white rounded-lg border w-2/3 mx-auto">
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
        <div class="mb-4 col-span-full xl:mb-2">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Pengaturan Profil</h1>
        </div>
        <form @submit.prevent="simpanData">
            <div class="grid grid-cols-2 gap-4">
                <div class="w-full mb-4">
                    <label for="" class="font-sans text-base text-gray-600 mb-2 block">Nama Lengkap</label>
                    <input  x-model="properties.form.nama" type="text" class="w-full bg-white text-gray-700 font-sans font-normal outline outline-0 border-2 text-sm px-3 py-3 rounded-md border-gray-200 focus:border-gray-400" autofocus>
                </div>
                <div class="w-full mb-4">
                    <label for="" class="font-sans text-base text-gray-600 mb-2 block">No. Handphone (Whatsapp)</label>
                    <input x-model="properties.form.kontak" type="text" class="w-full bg-white text-gray-700 font-sans font-normal outline outline-0 border-2 text-sm px-3 py-3 rounded-md border-gray-200 focus:border-gray-400">
                </div>
            </div>
            <div class="w-full mb-4">
                <label for="" class="font-sans text-base text-gray-600 mb-2 block">Alamat Lengkap</label>
                <textarea x-model="properties.form.alamat" class="w-full bg-white text-gray-700 font-sans font-normal outline outline-0 border-2 text-sm px-3 py-3 rounded-md border-gray-200 focus:border-gray-400"></textarea>
            </div>
            <div class="mb-2 mt-6 text-right">
                <button class="inline-flex items-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="submit">
                    Simpan Perubahan
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
                    '/api/pelanggan/update',
                    this.createFormData({
                        'nama': this.properties.form.nama,
                        'kontak': this.properties.form.kontak,
                        'alamat': this.properties.form.alamat
                    }),
                    response => {
                        this.addNormalMessage('form_response', response.data.data.message);
                    },
                    err => {
                        this.addErrorMassage('bad_request', err.response.data.errors['message']);
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
                    "data": {
                        'pelanggan': JSON.parse('<?= json_encode($pelanggan->toArray()) ?>')
                    },
                    "form": {
                        'nama' : '',
                        'kontak': '',
                        'alamat': ''
                    }
                },
                "init": function() {
                    this.properties.form.nama = this.properties.data.pelanggan.nama;
                    this.properties.form.kontak = this.properties.data.pelanggan.kontak;
                    this.properties.form.alamat = this.properties.data.pelanggan.alamat;
                }
            })
        );
    });
</script>