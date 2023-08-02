<?php

if (false === session()->isAuthenticatedAs('admin')) html_unauthorized();

if (isset($_GET['page'])) {
    if ($_GET['page'] <= 0) html_not_found();
}

$page = $_GET['page'] ?? 1;

$listStokBeras = app()->getManager()->getService('KelolaStok')->listStokBeras();

?>
<main x-data="container">
    <div class="px-4 pt-6">
        <div class="mb-4 col-span-full">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white" ref="title">Data Stok dan Harga</h1>
        </div>
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
        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <div class="px-4 py-8 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <form action="#" method="GET" class="flex flex-row justify-start items-center">
                        <div class="flex justify-end w-2/6 ">
                            <input type= "text" name="email" id="products-search" class="bg-gray-50 border rounded-bl-lg rounded-tl-lg border-gray-300 text-gray-900 sm:text-sm focus:border-gray-200 focus:border-gray-200 outline-none block w-full p-2.5" placeholder="Cari produk">
                            <buttton type="submit" class="border border-gray-300 cursor-pointer rounded-tr-lg rounded-br-lg bg-gray-100 hover:bg-yellow-800 px-5 py-2 focus:outline-none outline-none hover:bg-gray-200">
                                <svg class="w-5 h-6 text-gray-100" viewBox="0 0 20 20">
                                    <path d="M19.129,18.164l-4.518-4.52c1.152-1.373,1.852-3.143,1.852-5.077c0-4.361-3.535-7.896-7.896-7.896
                            c-4.361,0-7.896,3.535-7.896,7.896s3.535,7.896,7.896,7.896c1.934,0,3.705-0.698,5.078-1.853l4.52,4.519
                            c0.266,0.268,0.699,0.268,0.965,0C19.396,18.863,19.396,18.431,19.129,18.164z M8.567,15.028c-3.568,0-6.461-2.893-6.461-6.461
                            s2.893-6.461,6.461-6.461c3.568,0,6.46,2.893,6.46,6.461S12.135,15.028,8.567,15.028z"></path>
                                </svg>
                            </buttton>
                        </div>
                    </form>
                    <table class="w-full my-4">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                No
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Jenis Beras
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Stok Tersedia (kg)
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Harga (Rupiah)
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="(item, index) in properties.data.list_stok_beras" :key="index">
                                <tr>
                                    <td class="w-4 p-4" x-text="index + 1"></td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="text-base font-semibold text-gray-900 dark:text-white" x-text="item.relations.beras.jenis"></span>
                                        ( takaran: <span class="" x-text="item.relations.takaran.variant.toUpperCase()"></span> )
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <p class="text-base text-gray-600">
                                            <span class="" x-text="addDotToNumber(item.jumlah_stok)"></span> Kg
                                        </p>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <p class="text-base text-gray-600">
                                            <span class="" x-text="currencyToRupiah(item.harga)"></span>
                                        </p>
                                    </td>
                                    <td class="p-4 space-x-2 whitespace-nowrap flex justify-end">
                                        <button @click="editData(item)" type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-orange-500 hover:bg-orange-700 focus:ring-4 focus:ring-orange-300">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                                            Perbaharui
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end text-gray-600">
                    <div class="px-4 py-2 rounded w-32 hover:underline cursor-pointer">
                        <?php if($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>">Sebelumnya</a>
                        <?php else: ?>
                            <span>Sebelumnya</span>
                        <?php endif; ?>
                    </div>
                    <div class="px-4 py-2 rounded hover:underline cursor-pointer">
                        <a href="?page=<?= $page + 1 ?>">Selanjutnya</a>
                    </div>
                </div>
            </div>
            <div>
                <form @submit.prevent="simpanData" class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div>
                        <h3 class="font-semibold text-lg mb-4">Perbaharui Stok dan Harga</h3>
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Beras (otomatis)</label>
                        <input x-model="properties.form.selected_string" type="text" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" disabled>
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok</label>
                        <input x-model="properties.form.stok" type="number" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5">
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Jual / Takaran</label>
                        <input x-model="properties.form.harga" type="number" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5">
                    </div>
                    <div class="mb-2 mt-6 text-right">
                        <button class="inline-flex items-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="submit">
                            <svg class="w-4 h-4 mr-2 text-white" viewBox="0 0 20 20">
                                <path fill="currentColor"  d="M7.197,16.963H7.195c-0.204,0-0.399-0.083-0.544-0.227l-6.039-6.082c-0.3-0.302-0.297-0.788,0.003-1.087
							C0.919,9.266,1.404,9.269,1.702,9.57l5.495,5.536L18.221,4.083c0.301-0.301,0.787-0.301,1.087,0c0.301,0.3,0.301,0.787,0,1.087
							L7.741,16.738C7.596,16.882,7.401,16.963,7.197,16.963z"></path>
                            </svg>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {
            "editData": function (item) {
                this.properties.form.beras_id = item.beras_id;
                this.properties.form.takaran_id = item.takaran_id;
                this.properties.form.stok = item.jumlah_stok;
                this.properties.form.harga = item.harga;

                this.properties.form.selected_string = `${item.relations.beras.jenis} - (takaran: ${item.relations.takaran.variant})`;
            },
            "simpanData": function () {
                this.clearMassage();
                let alpineObj = this;

                this.postData(
                    '/api/stok/update',
                    this.createFormData({
                        'beras_id': this.properties.form.beras_id,
                        'takaran_id': this.properties.form.takaran_id,
                        'stok': this.properties.form.stok,
                        'harga': this.properties.form.harga
                    }),
                    function (response) {
                        // updated
                        let index = alpineObj.properties.data.list_stok_beras.findIndex(item => {
                            return item.beras_id == response.data.data.beras_id && item.takaran_id == response.data.data.takaran_id;
                        });
                        alpineObj.properties.data.list_stok_beras[index].jumlah_stok = response.data.data.jumlah_stok;
                        alpineObj.properties.data.list_stok_beras[index].harga = response.data.data.harga;

                        alpineObj.addNormalMessage('form_response', `Berhasil! Stok dan harga (Beras: ${response.data.data.relations.beras.jenis} takaran: ${response.data.data.relations.takaran.variant}) telah diperbaharui.`);
                    },
                    function (err) {
                        console.log(err);
                        alpineObj.addErrorMassage('bad_request', err.response.data.errors.message);
                    }
                )
            }
        };

        const utils = {
            "tanggalToIndo": function (tanggal) {
                if (!tanggal) return null;
                let date = new Date(tanggal);

                return date.toLocaleDateString('id-ID',  { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            },
            "currencyToRupiah": function (number) {
                return 'Rp ' + this.addDotToNumber(number);
            },
            "addDotToNumber": function (number) {
                return (new Intl.NumberFormat('id-Id', {"maximumSignificantDigits": 3}).format(number));
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
                        "api_url": "<?= site_url() ?>",
                        "query_title": null,
                        "button_title": 'Tambahkan Jenis Beras Baru'
                    },
                    "messages": {
                        "errors": [],
                        "normal": []
                    },
                    "data": {
                        "list_stok_beras": JSON.parse('<?= json_encode($listStokBeras) ?>'),
                    },
                    "form": {
                        'beras_id' : -1,
                        'takaran_id': -1,
                        'harga': 0,
                        'stok': 0,
                        'selected_string': '-',
                    }
                },
                "init": function() {}
            })
        );
    });
</script>
