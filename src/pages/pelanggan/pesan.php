<?php

/*
TODO: menampilkan seluruh produk (done)
TODO: mengganti gambar
TODO: memilih produk dan menampilkan di form input jumlah beli (done)
TODO: menambahkan item ke keranjang (done)
TODO: merubah jumlah beli
TODO: menghapus item dari keranjang
TODO: validasi jumlah beli tidak boleh lebih dari stok tersedia
*/

if (false === session()->isAuthenticatedAs('pelanggan')) html_unauthorized();
$listBeras = app()->getManager()->getService('KelolaBeras')->listBeras();

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
                        <select name="" id="" class="cursor-pointer outline-none">
                            <option value="1">nama jenis beras (A-Z)</option>
                            <option value="2">harga terendah ke tertinggi
                            <option value="2">harga tertinggi ke terendah</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                <?php if($listBeras): ?>
                <template x-for="beras in properties.data.list_beras">
                    <div class="w-full max-w-sm bg-white border border-gray-200 rounded">
                        <div class="w-full h-48 relative">
                            <img class="object-cover w-full h-full rounded-t" src="<?= site_url('assets/static/no-image.jpg') ?>" alt="product image" />
                            <span class="absolute top-2 left-2 z-10 px-2 bg-green-600 text-sm text-white rounded" x-show="beras.stok_tersedia">
                                Stok: <span x-text="addDotToCurrentcy(beras.stok)"></span> kg
                            </span>
                            <span class="absolute top-2 left-2 z-10 px-2 bg-red-600 text-sm text-white italic rounded" x-show="!beras.stok_tersedia">Stok Kosong</span>
                        </div>
                        <div class="p-4">
                            <a href="#">
                                <h5 class="font-semibold text-xl text-gray-900" x-text="beras.jenis"></h5>
                            </a>
                            <div class="flex items-center justify-between">
                                <p class="text-gray-500"><span x-text="currencyToRupiah(beras.harga)"></span>/kg</p>
                                <button x-show="beras.stok_tersedia" @click="selectProduct(beras)"  type="button" class="text-sm text-white bg-orange-500 rounded px-4 py-1 hover:bg-orange-600 text-center">Pilih</button>
                            </div>
                        </div>
                    </div>
                </template>
                <?php else: ?>
                <div class="text-center py-4">Tidak ada data.</div>
                <?php endif; ?>
                </div>
            </div>
            <div class="ml-4">
                <div class="rounded border border-blue-600 p-4 mb-4 text-blue-600">
                    <strong>Informasi Pembelian !</strong>
                    <p>Anda bisa mendapatkan harga khusus untuk pembelian diatas 10kg yang akan dihitung pada langkah berikutnya.</p>
                </div>
                <div class="border rounded border-gray-300 p-4 grid grid-cols-3 gap-4 items-center mb-4">
                    <div>
                        <p class="font-semibold text-lg text-gray-900" x-text="properties.form.selected.jenis"></p>
                        <p class="text-sm text-gray-500"><span x-text="currencyToRupiah(properties.form.selected.harga)"></span> /kg</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <input @keyup="countTotal" x-model="properties.form.selected.jumlah_beli" type="number" class="w-full border border-gray-300 rounded bg-gray-100 px-2 py-1 outline-none focus:border-gray-400" placeholder="jumlah beli (kg)">
                        <span>kg</span>
                    </div>
                    <div class="text-right font-semibold">
                        <p x-text="currencyToRupiah(properties.form.selected.total)"></p>
                    </div>
                    <div class="col-span-3 ">
                        <button @click="tambahkanKeKeranjang" type="button" class="text-sm w-full text-white bg-gray-500 rounded py-2 px-4 hover:bg-gray-600 text-center">Simpan Ke Keranjang</button>
                    </div>
                </div>
                <div class="border rounded border-gray-300 p-4 mb-4">
                    <p class="font-semibold text-lg text-gray-900 pb-1 mb-1">Keranjang Belanja</p>
                    <div class="mb-4">
                        <template x-for="item in properties.data.list_keranjang.items">
                            <div class="flex justify-between border-b pb-1 mb-2">
                                <div>
                                    <p class="text-gray-600"><span x-text="item.detail.jenis"></span>@<span x-text="currencyToRupiah(item.detail.harga)"></span> x <span x-text="addDotToCurrentcy(item.jumlah_beli)"></span>kg</p>
                                    <div class="text-sm text-red-500">
                                        <button @click="editItemKeranjang(item)" type="button" class=" hover:underline">Ubah</button>
                                        -
                                        <button type="button" href="" class=" hover:underline">Hapus</button>
                                    </div>
                                </div>
                                <div>
                                    <p x-text="currencyToRupiah(item.total_harga)"></p>
                                </div>
                            </div>
                        </template>
                        <div class="flex justify-between items-center">
                            <strong>Total Belanja</strong>
                            <strong x-text="currencyToRupiah(properties.data.list_keranjang.total)"></strong>
                        </div>
                    </div>
                    <div>
                        <button class="text-sm w-full text-white bg-green-500 rounded py-2 px-4 hover:bg-green-600 text-center">Pesan Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        // @TODO: separate to file
        const actions = {
            "cari": function () {
                alert('not-implemented');
            },
            "editData": function (item) {
                this.properties.form.id = item.id;
                this.properties.form.jenis = item.jenis;
                this.properties.form.harga = item.harga;
                this.properties.form.stok = item.stok;

                this.properties.sites.query_title = `(dipilih: ${item.jenis})`;
                this.properties.sites.button_title = 'Perbaharui Data Beras';
            },
            "simpanData": function () {
                this.clearMassage();
                let alpineObj = this;

                this.postData(
                    '/api/beras/create',
                    this.createFormData({
                        'id': this.properties.form.id,
                        'jenis': this.properties.form.jenis,
                        'harga': this.properties.form.harga,
                        'stok': this.properties.form.stok,
                    }),
                    function (response) {
                        if (alpineObj.properties.form.id < 0) { // saved
                            alpineObj.properties.data.list_beras.push(response.data.data);
                            alpineObj.properties.form.jenis = "";
                            alpineObj.properties.form.harga = 0;
                            alpineObj.properties.form.stok = 0;
                            alpineObj.addNormalMessage('form_response', `Berhasil! Data (Beras: ${response.data.data.jenis}) telah disimpan.`);

                            return;
                        }

                        // updated
                        let index = alpineObj.properties.data.list_beras.findIndex(item => item.id == response.data.data.id);
                        alpineObj.properties.data.list_beras[index].jenis = response.data.data.jenis;
                        alpineObj.properties.data.list_beras[index].stok = response.data.data.stok;
                        alpineObj.properties.data.list_beras[index].harga = response.data.data.harga;

                        alpineObj.addNormalMessage('form_response', `Berhasil! Data (Beras: ${response.data.data.jenis}) telah diperbaharui.`);
                    },
                    function (err) {
                        alpineObj.addErrorMassage('bad_request', 'Gagal dalam menyimpan, mohon periksa data dan coba lagi.')
                    }
                )
            },
            "hapusData": function (entity) {
                if(!confirm('Anda yakin ingin menghapus data ini ?')) return;

                this.clearMassage();

                let alpineObj = this;
                this.postData(
                    '/api/beras/delete',
                    this.createFormData({
                        'id': entity.id,
                    }),
                    function (response) {
                        let index = alpineObj.properties.data.list_beras.findIndex(item => item.id == response.data.data.deleted_id);
                        alpineObj.properties.data.list_beras.splice(index, 1);

                        alpineObj.addNormalMessage('form_response', 'Berhasil! Data telah dihapus.');
                    },
                    function (err) {
                        alpineObj.addErrorMassage('bad_request', 'Gagal menghapus data, mohon muat ulang halaman dan coba lagi.')
                    }
                )
            },
            "selectProduct": function (selected) {
                this.properties.form.selected.id = selected.id;
                this.properties.form.selected.jenis = selected.jenis;
                this.properties.form.selected.harga = selected.harga;
                this.properties.form.selected.stok = selected.stok;

                let indexInKeranjang = this.properties.data.list_keranjang.items.findIndex(el => el.detail.id == selected.id);
                if(indexInKeranjang !== -1) {
                    this.editItemKeranjang(this.properties.data.list_keranjang.items[indexInKeranjang]);

                    return;
                }

                console.log(this.properties.data.list_keranjang.items[indexItem]);

                this.properties.form.selected.jumlah_beli = 0;
                this.countTotal()
            },
            "countTotal": function () {
                this.properties.form.selected.total = this.properties.form.selected.jumlah_beli * this.properties.form.selected.harga
            },
            "tambahkanKeKeranjang": function () {
                if (this.properties.form.selected.id < 1) return;

                let alpineObj = this;
                this.postData(
                    '/api/keranjang/add',
                    this.createFormData({
                        'beras': this.properties.form.selected.id,
                        'jumlah_beli': this.properties.form.selected.jumlah_beli,
                        'key': this.properties.form.selected.key
                    }),
                    function (response) {
                        alpineObj.properties.data.list_keranjang = response.data.data;
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
                this.properties.form.selected.id = item.detail.id;
                this.properties.form.selected.jenis = item.detail.jenis;
                this.properties.form.selected.harga = item.detail.harga;
                this.properties.form.selected.stok = item.detail.stok;
                this.properties.form.selected.key = item.key;

                this.properties.form.selected.jumlah_beli = item.jumlah_beli;
                this.countTotal()
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
                        "list_beras": JSON.parse('<?= json_encode(array_map(fn ($item) => $item->toArray(), $listBeras)) ?>'),
                        "list_keranjang": {}
                    },
                    "form": {
                        "selected": {
                            'id' : -1,
                            'jenis': '-',
                            'harga': 0,
                            'stok': 0,
                            'jumlah_beli': 0,
                            'total': 0,
                            'key': null
                        }
                    }
                },
                "init": function() {
                    this.loadKeranjang();
                }
            })
        );
    });
</script>
