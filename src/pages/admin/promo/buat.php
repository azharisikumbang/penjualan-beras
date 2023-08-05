<?php

if (false === session()->isAuthenticatedAs('admin')) html_unauthorized();

if (isset($_GET['page'])) {
    if ($_GET['page'] <= 0) html_not_found();
}

$page = $_GET['page'] ?? 1;

$kelolaPromo = app()->getManager()->getService('KelolaPromo');
$generatedCouponCode = $kelolaPromo->generateCouponCode();
$listPromo = $kelolaPromo->listPromo(10, $page);

?>
<main x-data="container">
    <div class="px-4 pt-6">
        <div class="mb-4 col-span-full">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white" ref="title">Semua Promo Tersedia</h1>
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
        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <div class="px-4 py-8 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <form action="#" method="GET" class="flex flex-row justify-start items-center">
                        <div class="flex justify-end w-2/6 ">
                            <input type="text" name="email" id="products-search" class="bg-gray-50 border rounded-bl-lg rounded-tl-lg border-gray-300 text-gray-900 sm:text-sm focus:border-gray-200 focus:border-gray-200 outline-none block w-full p-2.5" placeholder="Cari kupon">
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
                                Jenis Promo
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Tanggal Kadaluarsa
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Kode Kupon
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Minimum Pembelian
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Potongan Harga
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <?php if($listPromo ): ?>
                            <template x-for="(item, index) in properties.data.list_promo" :key="index">
                                <tr>
                                    <td class="w-4 p-4" x-text="index + 1"></td>
                                    <td class="p-4 whitespace-nowrap">
                                        <p class="text-base">Kode Kupon</p>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <p class="text-base inline" x-text="tanggalToIndo(item.tanggal_kadaluarsa)"></p>
                                        <span x-show="item.kadaluarsa" class="px-1 text-white bg-red-500 rounded text-sm">Kadaluarsa</span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <p class="text-base text-gray-900 font-semibold" x-text="item.kode_kupon"></p>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <p class="text-base text-gray-900" x-text="currencyToRupiah(item.minimum_pembelian)"></p>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <p class="text-base text-gray-900" x-text="currencyToRupiah(item.potongan_harga)" x-show="!item.is_persen"></p>
                                        <p class="text-base text-gray-900" x-text="item.potongan_harga + ' %'" x-show="item.is_persen"></p>
                                    </td>
                                </tr>
                            </template>
                        <?php else: ?>
                            <tr>
                                <td class="text-center italic text-gray-400 pt-4" colspan="4">
                                    Tidak ada data.
                                </td>
                            </tr>
                        <?php endif; ?>
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
                <form @submit.prevent="simpanData" method="post" class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div>
                        <h3 class="font-semibold text-lg mb-4">Buat Promo Baru</h3>
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Promo</label>
                        <input x-model="properties.form.jenis_promo" type="radio" class="mr-1" value="COUPON_CODE" checked> Kode Kupon
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Kupon (otomatis)</label>
                        <input value="<?= $generatedCouponCode ?>" type="text" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5" disabled>
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Kadaluarsa</label>
                        <input x-model="properties.form.tanggal_kadaluarsa" type="date" min="<?= date('Y-m-d') ?>" class="cursor-pointer shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5">
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Minimum Total Pembelian (Rupiah)</label>
                        <input x-model="properties.form.minimum_pembelian" type="number" value="0" min="0" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5">
                    </div>
                    <div class="mb-4">
                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Diskon / Potongan Harga*</label>
                        <input x-model="properties.form.potongan_harga" type="number" value="0" min="0" class="shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none block w-full p-2.5">
                        <span class="text-sm italic text-red-500">*Jika angka dimasukkan dibawah 100, maka akan dikonversi menjadi diskon dalam bentuk persen (%).</span>
                    </div>
                    <div class="mb-2 mt-6 text-right">
                        <button class="inline-flex items-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="submit">
                            Buat Promo
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
            "simpanData": function () {
                this.clearMassage();

                this.postData(
                    '/api/promo/create',
                    this.createFormData({
                        'jenis_promo' : this.properties.form.jenis_promo,
                        'kode_kupon': this.properties.form.kode_kupon,
                        'tanggal_kadaluarsa': this.properties.form.tanggal_kadaluarsa,
                        'minimum_pembelian': this.properties.form.minimum_pembelian,
                        'potongan_harga': this.properties.form.potongan_harga
                    }),
                    response => {
                        this.addNormalMessage('form_response', `Berhasil! Promo (kode: ${response.data.data.kode_kupon}) berhasil dibuat. Halaman akan dimuat ulang untuk mendapatkan kode kupon baru.`);
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    },
                    err => {
                        this.addErrorMassage('bad_request', err.response.data.errors['message'] + ' Halaman akan dimuat ulang untuk mendapatkan kode kupon baru.');
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                );
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
                        'list_promo': JSON.parse('<?= json_encode($listPromo) ?>')
                    },
                    "form": {
                        'jenis_promo' : 'COUPON_CODE',
                        'kode_kupon': '',
                        'tanggal_kadaluarsa': '',
                        'minimum_pembelian': 0,
                        'potongan_harga': 0
                    }
                },
                "init": function() {
                    this.properties.form.kode_kupon = '<?= $generatedCouponCode ?>';
                }
            })
        );
    });
</script>
