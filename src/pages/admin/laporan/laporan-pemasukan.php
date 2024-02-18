<?php

if (false === session()->isAuthenticatedAs('admin')) html_unauthorized();

$listStokBeras = $listStokBeras = app()->getManager()->getService('KelolaStok')->listStokBeras(100);
$listBulan = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];


?>
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
        <div class="grid grid-cols-4 gap-4">
            <div class="col-span-3 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="mb-4">
                    <form @submit.prevent="loadData" class="flex flex-row gap-4 justify-end items-center">
                        <div class="font-medium text-gray-900">Periode Laporan:</div>
                        <div class="flex flex-row gap-4">
                            <div>
                                <select x-model="properties.form.tahun" class="cursor-pointer shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none p-2.5 w-full">
                                    <?php for ($i = date('Y'); $i >= 2023; $i--) { ?>
                                        <option value="<?= $i ?>">Tahun <?= $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div>
                                <select @change="gantiBulanLaporan" x-model="properties.form.bulan" class="cursor-pointer shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none p-2.5 w-full">
                                    <option value="0">-- Semua Bulan --</option>
                                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                                        <option value="<?= $i ?>"><?= $listBulan[$i - 1] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div>
                                <input type="number" x-model="properties.form.tanggal" class="block w-32 cursor-pointer shadow-sm bg-gray-50 border-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:border-gray-500 outline-none p-2.5" max="31" min="0" placeholder="*Ketik Tanggal..">
                            </div>
                        </div>
                        <div class="w-1/4">
                            <button class="inline-flex w-full items-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center" type="submit">
                                <svg class="w-5 h-5 mr-2 text-white" viewBox="0 0 20 20">
                                    <path fill="currentColor" d="M19.305,9.61c-0.235-0.235-0.615-0.235-0.85,0l-1.339,1.339c0.045-0.311,0.073-0.626,0.073-0.949
								c0-3.812-3.09-6.901-6.901-6.901c-2.213,0-4.177,1.045-5.44,2.664l0.897,0.719c1.053-1.356,2.693-2.232,4.543-2.232
								c3.176,0,5.751,2.574,5.751,5.751c0,0.342-0.037,0.675-0.095,1l-1.746-1.39c-0.234-0.235-0.614-0.235-0.849,0
								c-0.235,0.235-0.235,0.615,0,0.85l2.823,2.25c0.122,0.121,0.282,0.177,0.441,0.172c0.159,0.005,0.32-0.051,0.44-0.172l2.25-2.25
								C19.539,10.225,19.539,9.845,19.305,9.61z M10.288,15.752c-3.177,0-5.751-2.575-5.751-5.752c0-0.276,0.025-0.547,0.062-0.813
								l1.203,1.203c0.235,0.234,0.615,0.234,0.85,0c0.234-0.235,0.234-0.615,0-0.85l-2.25-2.25C4.281,7.169,4.121,7.114,3.961,7.118
								C3.802,7.114,3.642,7.169,3.52,7.291l-2.824,2.25c-0.234,0.235-0.234,0.615,0,0.85c0.235,0.234,0.615,0.234,0.85,0l1.957-1.559
								C3.435,9.212,3.386,9.6,3.386,10c0,3.812,3.09,6.901,6.902,6.901c2.083,0,3.946-0.927,5.212-2.387l-0.898-0.719
								C13.547,14.992,12.008,15.752,10.288,15.752z"></path></svg>
                                Muat Laporan
                            </button>
                        </div>
                    </form>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                No
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Periode
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase w-64">
                                Pemasukan (Rupiah)
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template
                            x-for="(item, index) in properties.data.laporan.data"
                            :key="index"
                        >
                            <tr>
                                <td class="w-4 p-4" x-text="index + 1"></td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="text-base font-semibold text-gray-900 dark:text-white" x-text="parseNamaPeriode(item)"></span>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <p class="text-base text-gray-600">
                                        <span class="" x-text="currencyToRupiah(item.total)"></span>
                                    </p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="border-t">
                        <tr>
                            <td class="p-4 whitespace-nowrap text-right" colspan="2">
                                <span class="text-base font-semibold text-gray-900">Total</span>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <p class="text-base text-gray-600 font-semibold">
                                    <span class="" x-text="currencyToRupiah(properties.data.laporan.total)"></span>
                                </p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <form>
                <div class="mb-2 font-semibold text-xl">
                    Laporan Pemasukan
                </div>
                <p class="mb-4">
                    Diakses pada <?= tanggal(date_create(), false, true) ?> WIB oleh <?= session()->auth()->getUsername() ?>.
                </p>
                <a target="_blank" :href="generateDownloadUrl" class="inline-flex text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                    <svg class="w-4 h-4 mr-2 text-white" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                        <path fill="currentColor" d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                    </svg>
                    Unduh Laporan (.pdf)
                </a>
            </form>
        </div>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {
            "loadData": function () {
                if (this.properties.form.tanggal == '') this.properties.form.tanggal;

                this.getApiRequest('/api/laporan/pemasukan', {
                    'tahun' : this.properties.form.tahun,
                    'bulan': this.properties.form.bulan,
                    'tanggal': this.properties.form.tanggal
                }, response => {
                    if (response.data.data.data.length < 1) {
                        let temp = {
                            query: response.data.data.query,
                            data: [{
                                type: this.parseTypePeriode(response.data.data.query),
                                periode: 0,
                                total: 0
                            }],
                            total: 0
                        }

                        temp.data[0].periode = this.parsePeriode(temp.data[0].type, temp.query);
                        this.properties.data.laporan = temp;

                        return;
                    }

                    this.properties.data.laporan = response.data.data;
                }, error => {
                    console.error(error);
                });
            },
            "gantiBulanLaporan": function () {
                this.properties.form.tanggal = 0;
            },
            "parseNamaPeriode": function (item) {
                if (item.type === 'YEAR' || item.type === 'MONTH') {
                    return this.parseAsMonth(item.periode - 1); // js pake index untuk bulan, bukan bulannya. ex 0 = januari
                }

                if (item.type === 'DATE') {
                    let query = this.properties.data.laporan.query;

                    return this.tanggalToIndo(
                        `${query.tahun}-${query.bulan}-${query.tanggal}`
                    );
                }
            },
            "parseAsMonth": function (month) {
                let date = new Date();
                date.setMonth(month)

                return date.toLocaleDateString('id-ID',  { month: 'long' });
            },
            "parseTypePeriode": function (periode) {
                if (periode.tanggal >= 1) {
                    return 'DATE';
                }

                if (periode.bulan >= 1) {
                    return 'MONTH';
                }

                return 'YEAR';
            },
            "parsePeriode": function (type, periode) {
                console.log(type, periode);

                if (type === 'DATE') {
                    return `${periode.tahun}-${periode.bulan}-${periode.tanggal}`
                }

                if (type === 'MONTH') {
                    return periode.bulan;
                }

                return periode.tahun;
            },
            "generateDownloadUrl": function () {
                let params = new URLSearchParams(this.properties.form).toString();

                return `${this.properties.sites.api_url}/api/laporan/pdf/pemasukan?${params}`
            }
        };

        const utils = {
            "getApiRequest": function (to, params = null, successCallback, errorCallback) {
                return axios
                    .get(this.properties.sites.api_url + to, { params: params })
                    .then(successCallback)
                    .catch(errorCallback);
            },
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
                        "laporan": [],
                    },
                    "form": {
                        "tahun": 0,
                        "bulan": 0,
                        "tanggal": 0
                    }
                },
                "init": function() {
                    let today = new Date();
                    this.properties.form.tahun = today.getFullYear();
                    this.properties.form.bulan = today.getMonth() + 1;
                    this.properties.form.tanggal = today.getDate();

                    this.loadData();
                }
            })
        );
    });
</script>