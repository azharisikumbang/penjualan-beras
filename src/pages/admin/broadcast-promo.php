<?php

if (false === session()->isAuthenticatedAs('admin')) html_unauthorized();

$listPelanggan = app()->getManager()->getService('KelolaPelanggan')->listPelanggan(20);
$listPromo = app()->getManager()->getService('KelolaPromo')->listPromoBukanKadaluarsa();

?>
<main x-data="container">
    <div class="px-4 pt-6">
        <div class="mb-4 col-span-full">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white" ref="title">Pengaturan Broadcast Promo</h1>
        </div>
        <div>
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
        </div>
        <div class="grid grid-cols-4 gap-4">
            <div>
                <div class="mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="p-4 text-lg font-semibold text-gray-700 border-b">Daftar Penerima</h3>
                    <div class="p-4 max-h-full overflow-y-auto" style="max-height: 65vh">
                        <div>
                            <template x-for="pelanggan in properties.data.list_pelanggan">
                                <div class="flex items-start py-2 border-b">
                                    <div class="mr-4 pt-1">
                                        <input x-model="properties.form.list_target" type="checkbox" class="cursor-pointer" :value="pelanggan.kontak">
                                    </div>
                                    <div>
                                        <div class="font-semibold" x-text="pelanggan.nama"></div>
                                        <div x-text="pelanggan.kontak"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="px-4 pt-0 pb-4">
<!--                        <button class="bg-gray-500 text-white rounded px-4 py-2">-->
<!--                            Pilih Semua-->
<!--                        </button>-->
                    </div>
                </div>
            </div>
            <div class="col-span-3">
                <div class="px-4 py-8 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-lg mb-4">Broadcast Promo Baru</h3>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Promo:</label>
                        <select @change="selectPromo" x-model="properties.form.selected_promo" class="cursor-pointer shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5">
                            <option value="-1">-- Pilih Promo --</option>
                            <template x-for="promo in properties.data.list_promo">
                                <option :value="promo.id" x-text="'Kode Kupon: - ' + promo.kode_kupon"></option>
                            </template>
                        </select>
                    </div>
                    <div class="mb-4">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-500 text-white">
                                    <th scope="col" class="p-4 text-xs font-medium text-center uppercase">
                                        Jenis Promo
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-center uppercase">
                                        Tanggal Kadaluarsa
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-center uppercase">
                                        Kode Kupon
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-center uppercase">
                                        Minimum Pembelian
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-center uppercase">
                                        Potongan Harga
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-if="properties.form.has_selected_promo">
                                    <tr  class="bg-gray-100">
                                        <td class="p-4 whitespace-nowrap text-center">
                                            <p class="text-base">Kode Kupon</p>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-center">
                                            <p class="text-base inline" x-text="tanggalToIndo(properties.form.promo?.tanggal_kadaluarsa)"></p>
                                            <span x-show="properties.form.promo?.kadaluarsa" class="px-1 text-white bg-red-500 rounded text-sm">Kadaluarsa</span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-center">
                                            <p class="text-base text-gray-900 font-semibold" x-text="properties.form.promo?.kode_kupon"></p>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-center">
                                            <p class="text-base text-gray-900" x-text="currencyToRupiah(properties.form.promo?.minimum_pembelian)"></p>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-center">
                                            <p class="text-base text-gray-900" x-text="currencyToRupiah(properties.form.promo?.potongan_harga)" x-show="!properties.form.promo?.is_persen"></p>
                                            <p class="text-base text-gray-900" x-text="properties.form.promo?.potongan_harga + ' %'" x-show="properties.form.promo?.is_persen"></p>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="!properties.form.has_selected_promo" class="bg-gray-100">
                                    <td class="p-4 whitespace-nowrap text-center" colspan="5">
                                        <p class="text-base italic text-gray-600">Promo belum dipilih.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h3 class="font-semibold mt-8">Preview pesan promo: </h3>
                        <div x-show="properties.form.has_selected_promo" class="p-4 border rounded bg-gray-100 my-4">
                            <p>
                                Dapatkan promo dari Bumdes untuk sebesar
                                <span x-text="currencyToRupiah(properties.form.promo?.potongan_harga)" x-show="!properties.form.promo?.is_persen"></span>
                                <span x-text="properties.form.promo?.potongan_harga + ' %'" x-show="properties.form.promo?.is_persen"></span>
                                dengan memakai kode kupon: <strong x-text="properties.form.promo.kode_kupon"></strong> dengan minimum pembelian <span x-text="currencyToRupiah(properties.form.promo?.minimum_pembelian)"></span> untuk semua jenis beras.</p>
                            <p>Jangan sampai terlewat, promo hanya berlaku sampai dengan <span x-text="tanggalToIndo(properties.form.promo?.tanggal_kadaluarsa)"></span>.</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <button @click="broadcastPromo" class="bg-green-500 text-white rounded px-4 py-2 mt-2">
                            <svg class="w- h-4 inline mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.057 6.9a8.718 8.718 0 0 1 6.41-3.62v-1.2A2.064 2.064 0 0 1 9.626.2a1.979 1.979 0 0 1 2.1.23l5.481 4.308a2.107 2.107 0 0 1 0 3.3l-5.479 4.308a1.977 1.977 0 0 1-2.1.228 2.063 2.063 0 0 1-1.158-1.876v-.942c-5.32 1.284-6.2 5.25-6.238 5.44a1 1 0 0 1-.921.807h-.06a1 1 0 0 1-.953-.7A10.24 10.24 0 0 1 2.057 6.9Z"/>
                            </svg>
                            Kirim Pesan Broadcast Promo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {
            "broadcastPromo": async function () {
                this.clearMassage();

                if (this.properties.form.selected_promo == -1) {
                    this.properties.form.has_selected_promo = false;
                    this.addErrorMassage('bad_request', 'Mohon pilih promo yang akan di-broadcast terlebih dahulu.');

                    return;
                }

                if (this.properties.form.list_target.length < 1) {
                    this.addErrorMassage('invalid_selected_number', 'Mohon pilih nomor terlebih dahulu terlebih dahulu.');

                    return;
                }

                const ask = confirm('Anda yakin mengirim pesan broadcast ?');
                if (!ask) return;

                const elem = this.$event.target;
                elem.classList.add('opacity-50');

                await this.postData(
                    '/api/promo/broadcast',
                    this.createFormData({
                        'list_target': this.properties.form.list_target,
                        'promo': this.properties.form.promo.kode_kupon
                    }),
                    response => {
                        this.addNormalMessage('sent', response.data.data['message']);
                    },
                    err => {
                        console.error(err);
                        this.addErrorMassage('bad_request', err.response.data.errors['message']);
                    }
                );

                elem.classList.remove('opacity-50');
            },
            "selectPromo": function () {
                if (this.properties.form.selected_promo == -1) {
                    this.properties.form.has_selected_promo = false;
                    return;
                }

                this.properties.form.promo = this.properties.data.list_promo.filter(item => item.id == this.properties.form.selected_promo)[0];
                this.properties.form.has_selected_promo = true;
            }
        };

        const utils = {
            "tanggalToIndo": function (tanggal, details = false) {
                if (!tanggal) return null;
                let date = new Date(tanggal);

                let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                if (details) {
                    options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: false, second: 'numeric', timeZoneName: 'short' };
                }

                return date.toLocaleDateString('id-ID', options);
            },
            "currencyToRupiah": function (number) {
                return 'Rp ' + (new Intl.NumberFormat('id-Id', {"maximumSignificantDigits": 3}).format(number));
            },
            "buttonLoading": function(elem, statusText = 'Mohon Tunggu') {
                elem.disabled = true;
                elem.innerText = statusText;
                elem.classList.add('bg-gray-700');
                elem.classList.add('hover:bg-gray-700');
                elem.classList.add('focus:ring-gray-700');
                elem.classList.add('opacity-80');
                elem.classList.add('cursor-not-allowed');
            },
            "buttonRemoveLoading": function (elem, statusText, success = 'bg-green-700') {
                elem.disabled = false;
                elem.innerText = statusText;
                elem.classList.remove('bg-gray-700');
                elem.classList.remove('hover:bg-gray-700');
                elem.classList.remove('focus:ring-gray-700');
                elem.classList.remove('opacity-80');
                elem.classList.remove('cursor-not-allowed');

                elem.classList.add('bg-green-700');
            },
            "getApiRequest": function (to, params = null) {
                return axios
                    .get(this.properties.sites.api_url + to, { params: params })
                    .then(res => res.data)
                    .catch(err => console.log(err));
            },
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
                        "list_pelanggan": JSON.parse('<?= json_encode(array_map(fn ($item) => $item->toArray(), $listPelanggan)) ?>'),
                        "list_promo": JSON.parse('<?= json_encode($listPromo) ?>'),
                    },
                    "form": {
                        "has_selected_promo" : false,
                        "selected_promo": -1,
                        "promo": {},
                        "pesan_broadcast": "",
                        "list_target": []
                    }
                },
                "init": function() {}
            })
        );
    });
</script>
