<?php

if (false === session()->isAuthenticatedAs('pelanggan') ||
    request()->notGetRequest()
) response()->notFound();

$nomorPesanan = $_GET['nomor'] ?? session('pesanan');
if(empty($nomorPesanan) || is_null($nomorPesanan)) response()->notFound();

/** @var $kelolaPesanan KelolaPesanan */
$kelolaPesanan = app()->getManager()->getService('KelolaPesanan');
$pesanan = $kelolaPesanan->cariBerdasarkanNomorPesanan($nomorPesanan);

if(is_null($pesanan)) response()->notFound();

$akun = session()->auth();
$valid = $kelolaPesanan->cekPemilikPesanan($pesanan, $akun);

if(false === $valid) response()->notFound();

?>
<main x-data="container">
    <form @submit.prevent="simpanData">
        <section id="errors">
            <?php if(session('temp')): ?>
                <div class="mb-4 block w-full text-base font-regular px-4 py-4 rounded-lg bg-<?= session('temp')['color'] ?? 'yellow' ?>-500 text-white">
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
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Informasi Pengiriman</h1>
            </div>
            <div class="grid grid-cols-3">
                <div class="col-span-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Pemesan</label>
                            <input x-model="properties.form.nama" type="text" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Handphone</label>
                            <input x-model="properties.form.kontak" type="text" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" min="0" value="0" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Pengiriman</label>
                        <textarea x-model="properties.form.alamat" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" rows="5" required></textarea>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="rounded border border-blue-600 p-4 mb-4 text-blue-600">
                        <strong>Informasi Pembelian !</strong>
                        <p>Mohon sertakan informasi dengan sebenar-benarnya agar pembelian dapat berjalan dengan lancar.</p>
                    </div>
                    <div class="text-gray-600 border rounded border-gray-300 p-4 mb-4">
                        <h4 class="text-lg font-bold mb-2">Informasi Pemesanan</h4>
                        <div class="flex justify-between items-center border-b text-gray-900 py-1 mb-1">
                            <p>Nomor Pemesanan</p>
                            <p class="font-semibold" x-text="properties.data.pesanan.nomor_pesanan"></p>
                        </div>
                        <div class="flex justify-between items-center border-b text-gray-900 py-1 mb-1">
                            <p>Tanggal Pemesanan</p>
                            <p class="font-semibold" x-text="tanggalToIndo(properties.data.pesanan.tanggal_pemesanan)"></p>
                        </div>
                        <div class="flex justify-between items-center border-b text-gray-900 py-1 mb-1">
                            <p>Total Tagihan</p>
                            <p class="font-semibold" x-text="currencyToRupiah(properties.data.pesanan.total_tagihan)"></p>
                        </div>
                        <div class="flex justify-between text-gray-900 py-1 mb-1">
                            <p>Item pembelian</p>
                            <ul>
                                <template x-for="item in properties.data.pesanan.list_pesanan">
                                    <li class="text-right text-sm italic">
                                        <span x-text="item.jenis_beras"></span> (takaran: <span x-text="item.takaran_beras"></span>)
                                        @<span x-text="currencyToRupiah(item.total)"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="block text-sm w-full text-white bg-green-500 rounded py-2 px-4 hover:bg-green-600 text-center">Lanjutkan Pemesanan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {
            "simpanData": function () {
                this.clearMassage();
                let alpineObj = this;

                this.postData(
                    '/api/pesanan/pengiriman/update',
                    this.createFormData({
                        'nama': this.properties.form.nama,
                        'kontak': this.properties.form.kontak,
                        'alamat': this.properties.form.alamat,
                    }),
                    function (response) {
                        alpineObj.addNormalMessage('form_response', response.data.data.message);

                        setTimeout(function () {
                            window.location = `${alpineObj.properties.sites.api_url}/pelanggan/pembayaran?nomor=${response.data.data.data.nomor_pesanan}`
                        }, 2000);
                    },
                    function (err) {
                        alpineObj.addErrorMassage('bad_request', err.response.data.errors[0]);
                    }
                )
            },
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
                    },
                    "messages": {
                        "errors": [],
                        "normal": []
                    },
                    "data": {
                        "pesanan": JSON.parse('<?= json_encode($pesanan->toArray()) ?>'),
                    },
                    "form": {
                        'nama': '',
                        'kontak': '',
                        'alamat': ''
                    }
                },
                "init": function() {
                    this.properties.form.nama = this.properties.data.pesanan.nama_pesanan === ''
                        ||  this.properties.data.pesanan.nama_pesanan == undefined
                        ? this.properties.data.pesanan.pemesan.nama
                        : this.properties.data.pesanan.nama_pesanan;

                    this.properties.form.kontak = this.properties.data.pesanan.kontak_pesanan === ''
                        ||  this.properties.data.pesanan.kontak_pesanan == undefined
                        ? this.properties.data.pesanan.pemesan.kontak
                        : this.properties.data.pesanan.kontak_pesanan;

                    this.properties.form.alamat = this.properties.data.pesanan.alamat_pengiriman === ''
                        ||  this.properties.data.pesanan.alamat_pengiriman == undefined
                        ? this.properties.data.pesanan.pemesan.alamat
                        : this.properties.data.pesanan.alamat_pengiriman;
                }
            })
        );
    });
</script>


