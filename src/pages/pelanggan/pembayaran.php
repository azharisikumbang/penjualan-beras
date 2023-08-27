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

if (false === $pesanan->informasiPengirimanIsFilled()) {
    session()->add('temp', ['message' => 'Informasi pengiriman masih kosong, mohon diisi terlebih dahulu.', 'color' => 'red']);
    response()->redirectTo(site_url('pelanggan/pengiriman?nomor='. $pesanan->getNomorPesanan()));
}

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
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Informasi Pembayaran</h1>
            </div>
            <div class="grid grid-cols-3">
                <div class="col-span-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Atas Nama Pembayaran</label>
                            <input x-model="properties.form.nama" type="text" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bank Pembayaran</label>
                            <input x-model="properties.form.bank" type="text" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" min="0" value="0" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Dibayarkan (<small>diisi otomaris sesuai angka tagihan.</small>)</label>
                        <p class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" x-text="currencyToRupiah(properties.data.pesanan.total_tagihan)"></p>
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bukti Pembayaran (gambar)</label>
                        <input accept="image/*" @change="properties.form.file_bukti = Object.values($event.target.files)[0]" type="file" class="text-gray-900 outline-none block w-full"></div>
                </div>
                <div class="ml-4">
                    <div class="rounded border border-blue-600 p-4 mb-4 text-blue-600">
                        <strong>Informasi Pembayaran !</strong>
                        <p>Silahkan lakukan pembayaran ke Bank BRI a/n SUHERMEN dengan no. 5461-01-022-884532, kemudian isi informasi pembayaran dengan sebenar-benarnya.</p>
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
                        <div class="mb-4 hidden">
                            <template x-for="item in properties.data.pesanan.list_pesanan">
                                <div class="flex justify-between border-b pb-1 mb-2">
                                    <div>
                                        <p class="text-gray-600">
                                            <span x-text="item.nama_beras" class="font-semibold"></span> @<span x-text="addDotToCurrentcy(item.jumlah_beli)"></span>kg
                                        </p>
                                    </div>
                                    <div>
                                        <p x-text="currencyToRupiah(item.total)"></p>
                                    </div>
                                </div>
                            </template>
                            <div class="flex justify-between items-center font-semibold">
                                <p>Total Belanja</p>
                                <p x-text="currencyToRupiah(properties.data.pesanan.total_tagihan)"></p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="block text-sm w-full text-white bg-green-500 rounded py-2 px-4 hover:bg-green-600 text-center">Selesaikan Pesanan</button>
                        <a :href="`${properties.sites.api_url}/pelanggan/riwayat/detail?nomor=${properties.data.pesanan.nomor_pesanan}`" class="block text-sm w-full text-white bg-gray-500 rounded py-2 px-4 hover:bg-gray-600 text-center mt-2 cursor-pointer">Bayar Nanti</a>
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
                    '/api/pesanan/pembayaran/update',
                    this.createFormData({
                        'nama': this.properties.form.nama,
                        'bank': this.properties.form.bank,
                        'nominal': this.properties.form.nominal,
                        'file_bukti': this.properties.form.file_bukti,
                    }),
                    {
                        "headers": {
                            "Content-Type": "multipart/form-data"
                        }
                    },
                    function (response) {
                        alpineObj.addNormalMessage('form_response', response.data.data.message);

                        setTimeout(function () {
                            window.location = `${alpineObj.properties.sites.api_url}/pelanggan/riwayat/detail?nomor=${response.data.data.data.nomor_pesanan}`
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
            "postData": function (to, data, headers, callback, callbackError) {
                let that = this;
                return axios
                    .post(this.properties.sites.api_url + to, data, headers)
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
                        'bank': '',
                        'nominal': '',
                        'file_bukti': ''
                    }
                },
                "init": function() {
                    this.properties.form.nominal = this.properties.data.pesanan.total_tagihan;
                }
            })
        );
    });
</script>


