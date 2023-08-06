<?php

if (false === session()->isAuthenticatedAs('pelanggan')) html_unauthorized();
/** @var $keranjang Keranjang */
$keranjang = app()->getManager()->getService('KelolaKeranjang')->get();

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
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Keranjang Belanja</h1>
        </div>
        <?php if ($keranjang->getItems()): ?>
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
                <table class="my-4 w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            No
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                            Jenis Beras
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Harga Satuan (Rp)
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Jumlah Beli
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                            Total
                        </th>
                        <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="(item, index) in properties.data.keranjang.items">
                            <tr>
                                <td class="w-4 p-4 text-center" x-text="index + 1"></td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="text-base font-semibold text-gray-900 dark:text-white" x-text="item.detail.relations.beras.jenis"></span>
                                    ( takaran: <span class="" x-text="item.detail.relations.takaran.variant.toUpperCase()"></span> )
                                </td>
                                <td class="p-4 text-gray-500 text-base text-center" x-text="currencyToRupiah(item.detail.harga)"></td>
                                <td class="p-4 text-gray-500 text-base text-center"><span x-text="addDotToCurrentcy(item.jumlah_beli)"></span> x <span x-text="item.detail.relations.takaran.variant.toUpperCase()"></span></td>
                                <td class="p-4 text-gray-500 text-base text-center" x-text="currencyToRupiah(item.total_harga)"></td>
                                <td class="p-4 space-x-2 whitespace-nowrap flex justify-end">
                                    <div class="text-sm text-red-500">
                                        <button @click="editItemKeranjang(item)" type="button" class=" hover:underline">Ubah</button>
                                        -
                                        <button @click="hapusItemDariKeranjang(item)" type="button" class=" hover:underline">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr class="border-t">
                            <td class="text-gray-500 text-base text-right pt-4" colspan="5">Total Tagihan :</td>
                            <td class="text-gray-500 text-base text-right pt-4" x-text="currencyToRupiah(properties.data.keranjang.total)"></td>
                        </tr>
                        <tr>
                            <td class="text-gray-500 text-base text-right" colspan="5">Diskon :</td>
                            <td class="text-gray-500 text-base text-right" x-text="currencyToRupiah(properties.data.nominal_diskon)"></td>
                        </tr>
                        <tr>
                            <td class="text-gray-500 text-base text-right" colspan="5">Total Dibayar:</td>
                            <td class="text-gray-500 text-base text-right font-bold" x-text="currencyToRupiah(properties.form.total_bayar)"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="ml-4">
                <div class="rounded border text-blue-600 border-blue-600 p-2 mb-4">
                    <strong>Informasi:</strong>
                    <p class="text-sm">- Kolom <b>*jumlah beli</b> diisi jumlah pembelian dalam satuan takaran. </p>
                    <p class="text-sm">- <b>Sub-Total</b> dihitung berdasarkan banyak jumlah beli dengan harga per takaran. </p>
                </div>
                <div class="border rounded border-gray-300 p-4 mb-4 flex justify-between gap-2">
                    <input type="text" x-model="properties.form.kupon" class="w-full border border-gray-300 rounded bg-gray-100 px-2 py-1 outline-none focus:border-gray-400" placeholder="Masukkan kupon dan dapatkan promo.">
                    <button @click="cekKupon" type="button" class="text-sm w-1/3 text-white bg-gray-500 rounded py-2 px-4 hover:bg-gray-600 text-center">Cek Kupon</button>
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
                            <span x-text="properties.form.selected.takaran_variant.toUpperCase()"></span>
                        </div>
                        <div class="text-right font-semibold">
                            <span>Sub-Total: </span>
                            <span x-text="currencyToRupiah(properties.form.selected.total)"></span>
                        </div>
                    </div>
                    <button @click="tambahkanKeKeranjang" type="button" class="text-sm w-full text-white bg-gray-500 rounded py-2 px-4 hover:bg-gray-600 text-center">Perbaharui</button>
                </div>
                <div>
                    <button @click="simpanPesanan" class="block text-sm w-full text-white bg-green-500 rounded py-2 px-4 hover:bg-green-600 text-center">Proses Pesanan</button>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="italic text-gray-500">Beranjang masih kosong, silahkan <a href="<?= site_url('pelanggan/pesan') ?>" class="text-orange-700 underline hover:text-orange-800">pemesanan</a>.</div>
        <?php endif; ?>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {
            "simpanPesanan": function () {
                let alpineObj = this;
                this.postData(
                    '/api/keranjang/save',
                    this.createFormData({
                        'kode_kupon_promo': this.properties.form.kupon
                    }),
                    function (response) {
                        alpineObj.addNormalMessage('form_response', `Berhasil! Pesanan anda telah dicatat dengan nomor ... Anda akan dialihkan untuk mengisi informasi pengiriman.`);
                        setTimeout(function () {
                            window.location = `${alpineObj.properties.sites.api_url}/pelanggan/pengiriman?nomor=${response.data.data.nomor_pesanan}`
                        }, 2000)
                    },
                    function (err) {
                        alpineObj.addErrorMassage('bad_request', error.response.data.errors.message)
                    }
                )
            },
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
                        alpineObj.properties.data.keranjang = response.data.data;
                        alpineObj.addNormalMessage('form_response', `Berhasil! Item telah dihapus dari keranjang.`);

                        if (alpineObj.properties.data.keranjang.items.length < 1) window.location.reload();

                        alpineObj.countTotalAfterDiskon();
                    },
                    function (err) {
                        alpineObj.addErrorMassage('bad_request', 'Gagal menghapus data, mohon muat ulang halaman dan coba lagi.')
                    }
                )
            },
            "countTotal": function () {
                this.properties.form.selected.total = this.properties.form.selected.jumlah_beli * this.properties.form.selected.harga;
            },
            "tambahkanKeKeranjang": function () {
                if (this.properties.form.selected.id < 1) return;

                let alpineObj = this;
                this.postData(
                    '/api/keranjang/add',
                    this.createFormData({
                        'beras': this.properties.form.selected.beras_id,
                        'takaran': this.properties.form.selected.takaran_id,
                        'jumlah_beli': this.properties.form.selected.jumlah_beli,
                        'key': this.properties.form.selected.key
                    }),
                    response => {
                        alpineObj.properties.data.keranjang = response.data.data;
                        alpineObj.properties.form.selected.key = '';
                        alpineObj.properties.form.selected.beras_id = -1;
                        alpineObj.properties.form.selected.takaran_id = -1;
                        alpineObj.properties.form.selected.beras_jenis = '-';
                        alpineObj.properties.form.selected.takaran_variant = '0 Kg';
                        alpineObj.properties.form.selected.harga = 0;
                        alpineObj.properties.form.selected.stok = 0;
                        alpineObj.properties.form.selected.jumlah_beli = 0;

                        this.countTotalAfterDiskon();
                    },
                    err => {
                        console.error(err);
                        alpineObj.addErrorMassage('bad_request', 'Gagal dalam menyimpan, mohon periksa data dan coba lagi.')
                    }
                )
            },
            "loadKeranjang": async function () {
                 this.getApiRequest(
                     '/api/keranjang/list',
                     null,
                     response => {
                         this.properties.data.keranjang = response.data;
                     },
                     error => {
                         this.addErrorMassage('server_error', 'Server error! Mohon muat ulang halaman dan coba kembali');
                     }
                 );
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
                this.countTotal();
            },
            "sortItem": function () {
                const selected = this.$event.target.value;

                switch (selected) {
                    case '1' :
                        this.properties.data.keranjang.items.sort((a, b) => ('' + a.detail.jenis).localeCompare(b.detail.jenis));
                        break;
                    case '2' :
                        this.properties.data.keranjang.items.sort((a, b) => a.total_harga - b.total_harga);
                        break;
                    case '3' :
                        this.properties.data.keranjang.items.sort((a, b) => b.total_harga - a.total_harga);
                        break;
                }
            },
            "cekKupon": function () {
                this.clearMassage();

                this.getApiRequest(
                    '/api/kupon/search',
                    { 'kupon': this.properties.form.kupon },
                    response => {
                        this.addNormalMessage('valid_promo', `Selamat, kode promo: ${response.data.data.kode_kupon} valid. Diskon akan diberikan jika minimum pembelian sebesar ${this.currencyToRupiah(response.data.data.minimum_pembelian)}.`);
                        this.properties.form.promo = response.data.data;

                        this.countTotalAfterDiskon();
                    },
                    error => {
                        this.addErrorMassage('bad_request', error.response.data.errors.message);
                        this.properties.form.promo = {};

                        this.properties.data.nominal_diskon = 0;
                        this.countTotalAfterDiskon();
                    }
                );
            },
            "countTotalAfterDiskon": function () {
                if (this.properties.form.promo.hasOwnProperty('id')) {
                    if (this.properties.data.keranjang.total >= this.properties.form.promo.minimum_pembelian) {
                        if (this.properties.form.promo.is_persen) this.properties.data.nominal_diskon = (this.properties.data.keranjang.total * this.properties.form.promo.potongan_harga) / 100;
                        else this.properties.data.nominal_diskon = this.properties.form.promo.potongan_harga;
                    }
                }

                let totalTagihan = this.properties.data.keranjang.total;
                let diskon = this.properties.data.nominal_diskon;

                this.properties.form.total_bayar = totalTagihan - diskon;
            }
        };

        const utils = {
            "tanggalToIndo": function (tanggal) {
                if (!tanggal) return null;
                let date = new Date(tanggal);

                return date.toLocaleDateString('id-ID',  { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            },
            "currencyToRupiah": function (number) {
                return 'Rp ' + this.addDotToCurrentcy(number);
            },
            "addDotToCurrentcy": function (number) {
                return (new Intl.NumberFormat('id-Id', {"maximumSignificantDigits": 3}).format(number));
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
            "getApiRequest": function (to, params = null, callback, callbackError) {
                return axios
                    .get(this.properties.sites.api_url + to, { params: params })
                    .then(res => callback(res))
                    .catch(err => callbackError(err));
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
                        "keranjang": JSON.parse('<?= json_encode($keranjang->toArray()) ?>'),
                        'nominal_diskon': 0
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
                        },
                        'kupon': "",
                        'promo': {},
                        'total_bayar': 0
                    }
                },
                "init": function() {
                    this.countTotalAfterDiskon();
                }
            })
        );
    });
</script>
