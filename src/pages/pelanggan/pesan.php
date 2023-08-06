<?php

if (false === session()->isAuthenticatedAs('pelanggan')) html_unauthorized();
$listBeras = app()->getManager()->getService('KelolaStok')->listStokBeras(100);

?>
<main x-data="container">
    <section id="errors">
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
    </section>
    <div class="p-6 bg-white rounded-lg border">
        <div class="mb-4 col-span-full">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Pilih Beras</h1>
        </div>
        <div class="grid grid-cols-3">
            <div class="col-span-2">
                <div class="flex justify-between">
                    <form action="#" method="GET" class="flex flex-row justify-start items-center mb-4">
                        <div class="flex justify-end w-96 text-sm">
                            <input type="text" name="email" id="products-search" class="bg-gray-50 border rounded-bl-lg rounded-tl-lg border-gray-300 text-gray-900 sm:text-sm focus:border-gray-200 focus:border-gray-200 outline-none block w-full p-2.5" placeholder="Cari nama beras..">
                            <buttton type="submit" class="border border-gray-300 cursor-pointer rounded-tr-lg rounded-br-lg bg-gray-100 hover:bg-gray-200 px-5 py-2 focus:outline-none outline-none hover:bg-gray-200">
                                <svg class="w-5 h-6 text-gray-100" viewBox="0 0 20 20">
                                    <path d="M19.129,18.164l-4.518-4.52c1.152-1.373,1.852-3.143,1.852-5.077c0-4.361-3.535-7.896-7.896-7.896
                            c-4.361,0-7.896,3.535-7.896,7.896s3.535,7.896,7.896,7.896c1.934,0,3.705-0.698,5.078-1.853l4.52,4.519
                            c0.266,0.268,0.699,0.268,0.965,0C19.396,18.863,19.396,18.431,19.129,18.164z M8.567,15.028c-3.568,0-6.461-2.893-6.461-6.461
                            s2.893-6.461,6.461-6.461c3.568,0,6.46,2.893,6.46,6.461S12.135,15.028,8.567,15.028z"></path>
                                </svg>
                            </buttton>
                        </div>
                    </form>
                    <div class="flex items-center">
                        <span>Urutkan:</span>
                        <select @change="sortItem" class="cursor-pointer outline-none">
                            <option value="1">nama jenis beras (A-Z)</option>
                            <option value="2">harga terendah ke tertinggi
                            <option value="3">harga tertinggi ke terendah</option>
                        </select>
                    </div>
                </div>
                <?php if($listBeras): ?>
                    <table class="w-full my-4">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                No
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Jenis Beras (Takaran)
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Harga (Rupiah)
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Stok Tersedia (kg)
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
                                        <span class="" x-text="currencyToRupiah(item.harga)"></span>
                                    </p>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <p class="text-base text-gray-600">
                                        <span class="" x-text="addDotToNumber(item.jumlah_stok)"></span> Kg
                                    </p>
                                </td>
                                <td class="p-4 space-x-2 whitespace-nowrap flex justify-end">
                                    <button @click="selectProduct(item)" type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-500 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                        <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 21">
                                            <path d="M15 14H7.78l-.5-2H16a1 1 0 0 0 .962-.726l.473-1.655A2.968 2.968 0 0 1 16 10a3 3 0 0 1-3-3 3 3 0 0 1-3-3 2.97 2.97 0 0 1 .184-1H4.77L4.175.745A1 1 0 0 0 3.208 0H1a1 1 0 0 0 0 2h1.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 10 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3Zm-8 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm8 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                                            <path d="M19 3h-2V1a1 1 0 0 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 0 0 2 0V5h2a1 1 0 1 0 0-2Z"/>
                                        </svg>
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                <?php else: ?>
                <div class="text-center py-4">Tidak ada data.</div>
                <?php endif; ?>
            </div>
            <div class="ml-4">
                <div class="rounded border text-blue-600 border-blue-600 p-2 mb-2">
                    <strong>Informasi:</strong>
                    <p class="text-sm">- Kolom <b>*jumlah beli</b> diisi jumlah pembelian dalam satuan takaran. </p>
                    <p class="text-sm">- <b>Sub-Total</b> dihitung berdasarkan banyak jumlah beli dengan harga per takaran. </p>
                </div>
                <div class="border rounded border-gray-300 p-4 mb-4">
                    <div class="flex justify-between gap-4 items-center">
                        <div class="col-span-2">
                            <p class="font-semibold text-lg text-gray-900" x-text="properties.form.selected.beras_jenis"></p>
                            <p class="text-sm text-gray-500"><span x-text="currencyToRupiah(properties.form.selected.harga)"></span> / takaran</p>
                        </div>
                        <div class="gap-1 w-48 grid-cols-5 grid items-center">
                            <input @keyup="countTotal" x-model="properties.form.selected.jumlah_beli" type="number" class="col-span-3 border border-gray-300 rounded bg-gray-100 px-2 py-1 outline-none focus:border-gray-400" placeholder="jumlah beli*">
                            <small class="col-span-2">
                                x <span x-text="properties.form.selected.takaran_variant"></span>
                            </small>
                        </div>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-300 py-2 mt-4">
                        <div class="text-sm">
                            <span>Takaran: </span>
                            <span x-text="properties.form.selected.takaran_variant.toUpperCase()"></span> |
                            <span>Stok: </span>
                            <span x-text="addDotToNumber(properties.form.selected.stok)"></span> Kg
                        </div>
                        <div class="text-right font-semibold">
                            <span>Sub-Total: </span>
                            <span x-text="currencyToRupiah(properties.form.selected.total)"></span>
                        </div>
                    </div>
                    <button @click="tambahkanKeKeranjang" type="button" class="text-sm w-full text-white bg-gray-500 rounded py-2 px-4 hover:bg-gray-600 text-center">Simpan Ke Keranjang</button>
                </div>
                <div class="border rounded border-gray-300 p-4 mb-4">
                    <p class="font-semibold text-lg text-gray-900 pb-1 mb-1">Keranjang Belanja</p>
                    <div class="mb-4">
                        <template x-for="item in properties.data.list_keranjang.items">
                            <div class="flex justify-between border-b pb-1 mb-2">
                                <div>
                                    <div class="text-gray-600 font-semibold">
                                        <span x-text="item.detail.relations.beras.jenis"></span>
                                        <small>(takaran: <span x-text="item.detail.relations.takaran.variant"></span>)</small>
                                    </div>
                                    <div class="text-sm italic text-gray-400">
                                        dipesan: <span x-text="item.jumlah_beli"></span> x <span x-text="item.detail.relations.takaran.variant"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p x-text="currencyToRupiah(item.total_harga)"></p>
                                    <div class="text-sm text-red-500">
                                        <button @click="editItemKeranjang(item)" type="button" class=" hover:underline">Ubah</button>
                                        -
                                        <button @click="hapusItemDariKeranjang(item)" type="button" class=" hover:underline">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="flex justify-between items-center">
                            <strong>Total Belanja</strong>
                            <strong x-text="currencyToRupiah(properties.data.list_keranjang.total)"></strong>
                        </div>
                    </div>
                    <div>
                        <a href="<?= site_url('pelanggan/keranjang') ?>" class="block text-sm w-full text-white bg-green-500 rounded py-2 px-4 hover:bg-green-600 text-center">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {
            "hapusItemDariKeranjang": function (item) {
                if(!confirm('Anda yakin ingin menghapus data ini ?')) return;

                this.clearMassage();

                let alpineObj = this;
                this.postData(
                    '/api/keranjang/delete',
                    this.createFormData({
                        'key': item.key,
                    }),
                    function (response) {
                        alpineObj.properties.data.list_keranjang = response.data.data;
                    },
                    function (err) {
                        alpineObj.addErrorMassage('bad_request', 'Gagal menghapus data, mohon muat ulang halaman dan coba lagi.')
                    }
                )
            },
            "selectProduct": function (selected) {
                this.properties.form.selected.key = '';
                this.properties.form.selected.beras_id = selected.beras_id;
                this.properties.form.selected.takaran_id = selected.takaran_id;
                this.properties.form.selected.beras_jenis = selected.relations.beras.jenis;
                this.properties.form.selected.takaran_variant = selected.relations.takaran.variant;
                this.properties.form.selected.harga = selected.harga;
                this.properties.form.selected.stok = selected.jumlah_stok;

                let indexInKeranjang = this.properties.data.list_keranjang.items.findIndex(el => {
                    return el.detail.beras_id == selected.beras_id && el.detail.takaran_id == selected.takaran_id;
                });

                if(indexInKeranjang !== -1) {
                    this.editItemKeranjang(this.properties.data.list_keranjang.items[indexInKeranjang]);

                    return;
                }

                this.properties.form.selected.jumlah_beli = 0;
                this.countTotal()
            },
            "countTotal": function () {
                this.properties.form.selected.total = this.properties.form.selected.jumlah_beli * this.properties.form.selected.harga;
            },
            "tambahkanKeKeranjang": function () {
                if (this.properties.form.selected.beras_id < 1 || this.properties.form.selected.takaran_id < 1) return;

                let alpineObj = this;
                this.postData(
                    '/api/keranjang/add',
                    this.createFormData({
                        'beras': this.properties.form.selected.beras_id,
                        'takaran': this.properties.form.selected.takaran_id,
                        'jumlah_beli': this.properties.form.selected.jumlah_beli,
                        'key': this.properties.form.selected.key
                    }),
                    function (response) {
                        alpineObj.properties.data.list_keranjang = response.data.data;
                        alpineObj.properties.form.selected.key = '';
                        alpineObj.properties.form.selected.beras_id = -1;
                        alpineObj.properties.form.selected.takaran_id = -1;
                        alpineObj.properties.form.selected.beras_jenis = '-';
                        alpineObj.properties.form.selected.takaran_variant = '0 Kg';
                        alpineObj.properties.form.selected.harga = 0;
                        alpineObj.properties.form.selected.stok = 0;
                        alpineObj.properties.form.selected.jumlah_beli = 0;
                    },
                    function (err) {
                        alpineObj.addErrorMassage('bad_request', 'Gagal dalam menyimpan, mohon periksa data dan coba lagi.')
                    }
                )
            },
            "loadKeranjang": async function () {
                this.properties.data.list_keranjang = (await this.getApiRequest('/api/keranjang/list')).data;
            },
            "editItemKeranjang": function (item) {
                this.properties.form.selected.beras_id = item.detail.beras_id;
                this.properties.form.selected.takaran_id = item.detail.takaran_id;
                this.properties.form.selected.beras_jenis = item.detail.relations.beras.jenis;
                this.properties.form.selected.takaran_variant = item.detail.relations.takaran.variant;
                this.properties.form.selected.harga = item.detail.harga;
                this.properties.form.selected.stok = item.detail.jumlah_stok;
                this.properties.form.selected.key = item.key;

                this.properties.form.selected.jumlah_beli = item.jumlah_beli;
                this.countTotal()
            },
            "sortItem": function () {
                const selected = this.$event.target.value;

                switch (selected) {
                    case '1' :
                        this.properties.data.list_beras.sort((a, b) => ('' + a.jenis).localeCompare(b.jenis));
                        break;
                    case '2' :
                        this.properties.data.list_beras.sort((a, b) => a.harga - b.harga);
                        break;
                    case '3' :
                        this.properties.data.list_beras.sort((a, b) => b.harga - a.harga);
                        break;
                }
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
                        "button_title": 'Tambahkan Jenis Beras Baru',
                        "show_password_input": true
                    },
                    "messages": {
                        "errors": [],
                        "normal": []
                    },
                    "data": {
                        "list_stok_beras": JSON.parse('<?= json_encode($listBeras) ?>'),
                        "list_keranjang": {}
                    },
                    "form": {
                        "selected": {
                            'beras_id' : -1,
                            'takaran_id' : -1,
                            'beras_jenis': '-',
                            'takaran_variant': '-',
                            'harga': 0,
                            'stok': 0,
                            'jumlah_beli': 0,
                            'jumlah_stok_beli': 0,
                            'total': 0,
                            'key': null
                        }
                    }
                },
                "init": function() {
                    this.loadKeranjang();
                    // this.properties.data.list_beras.sort((a, b) => ('' + a.jenis).localeCompare(b.jenis));
                }
            })
        );
    });
</script>
