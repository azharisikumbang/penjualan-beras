<?php

if (false === session()->isAuthenticatedAs('pimpinan')) html_unauthorized();

$listStokBeras = $listStokBeras = app()->getManager()->getService('KelolaStok')->listStokBeras(100);

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
        <div class="flex gap-4">
            <div class="p-4 w-2/3 bg-white border border-gray-200 rounded-lg shadow-sm ">
                <div class="block mb-4 text-2xl font-medium text-gray-900">
                    Laporan Stok Beras
                </div>
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                No
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Jenis Beras
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Takaran
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                Stok Tersedia* (dikali: Takaran)
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Harga (Rupiah)
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="(item, index) in properties.data.list_stok_beras" :key="index">
                        <tr>
                            <td class="w-4 p-4" x-text="index + 1"></td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="text-base font-semibold text-gray-900 dark:text-white" x-text="item.relations.beras.jenis"></span>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="" x-text="item.relations.takaran.variant.toUpperCase()"></span>
                            </td>
                            <td class="p-4 whitespace-nowrap text-center">
                                <p class="text-base text-gray-600">
                                    <span class="" x-text="addDotToNumber(item.jumlah_stok)"></span>
                                </p>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <p class="text-base text-gray-600">
                                    <span class="" x-text="currencyToRupiah(item.harga)"></span>
                                </p>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
            <form class="w-1/3" @submit.prevent="unduhLaporan">
                <p class="mb-4">
                    Diakses pada <?= tanggal(date_create(), false, true) ?> WIB oleh <?= session()->auth()->getUsername() ?>.
                </p>
                <button class="inline-flex text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="submit">
                    <svg class="w-4 h-4 mr-2 text-white" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                        <path fill="currentColor" d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                    </svg>
                    Unduh Laporan (.pdf)
                </button>
            </form>
        </div>
    </div>
</main>
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        const actions = {
            "unduhLaporan": function () {
                window.location.href = `${this.properties.sites.api_url}/api/laporan/stok?kriteria=${this.properties.form.kriteria}`
            }
        };

        const utils = {
            "getApiRequest": function (to, params = null) {
                return axios
                    .get(this.properties.sites.api_url + to, { params: params })
                    .then(res => res.data)
                    .catch(err => console.log(err));
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
                        "list_stok_beras": JSON.parse('<?= json_encode($listStokBeras) ?>'),
                    },
                    "form": {}
                },
                "init": function() {}
            })
        );
    });
</script>