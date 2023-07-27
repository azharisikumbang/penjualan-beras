<?php

if (session()->auth()) response()->redirectTo(site_url(session()->auth()->getRole()->redirectPage()));

//html_require_component('navbar');

?>
<main class="bg-gray-100 min-h-screen">
    <div class="mx-w-screen mx-auto p-12">
        <div class="flex flex-row bg-white rounded-lg shadow-lg">
            <div class="w-1/2 py-16 px-24">
                <?php if(session('temp')):
                    html_temp_alert(session('temp')['message'], session('temp')['status'] ? 'green' : 'yellow');
                endif; ?>
                <form action="<?= site_url('create-akun') ?>" method="post">
                    <div class="mb-8">
                        <h2 class="block antialiased tracking-normal font-sans text-4xl font-bold leading-relaxed text-gray-600 ">Silahkan Bergabung !</h2>
                        <p class="font-sans text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </p>
                    </div>
                    <div>
                        <strong class="text-gray-600">Informasi Konsumen</strong>
                    </div>
                    <div class="w-full mb-4">
                        <label for="" class="font-sans text-base text-gray-600 mb-2 block">Nama Lengkap</label>
                        <input name="nama" type="text" class="w-full bg-white text-gray-700 font-sans font-normal outline outline-0 border-2 text-sm px-3 py-3 rounded-md border-gray-200 focus:border-gray-400" autofocus>
                    </div>
                    <div class="w-full mb-4">
                        <label for="" class="font-sans text-base text-gray-600 mb-2 block">No. Handphone (Whatsapp)</label>
                        <input name="kontak" type="text" class="w-full bg-white text-gray-700 font-sans font-normal outline outline-0 border-2 text-sm px-3 py-3 rounded-md border-gray-200 focus:border-gray-400">
                    </div>
                    <div class="w-full mb-4">
                        <label for="" class="font-sans text-base text-gray-600 mb-2 block">Alamat Lengkap</label>
                        <textarea name="alamat" class="w-full bg-white text-gray-700 font-sans font-normal outline outline-0 border-2 text-sm px-3 py-3 rounded-md border-gray-200 focus:border-gray-400"></textarea>
                    </div>
                    <div>
                        <strong class="text-gray-600">Informasi Akun</strong>
                    </div>
                    <div class="w-full mb-4">
                        <label for="" class="font-sans text-base text-gray-600 mb-2 block">Username</label>
                        <input name="username" type="text" class="w-full bg-white text-gray-700 font-sans font-normal outline outline-0 border-2 text-sm px-3 py-3 rounded-md border-gray-200 focus:border-gray-400" autofocus>
                    </div>
                    <div class="w-full mb-4">
                        <label for="" class="font-sans text-base text-gray-600 mb-2 block">Password</label>
                        <input name="password" type="password" class="w-full bg-white text-gray-700 font-sans font-normal outline outline-0 border-2 text-sm px-3 py-3 rounded-md border-gray-200 focus:border-gray-400">
                    </div>
                    <div class="w-full mb-4">
                        <label for="" class="font-sans text-base text-gray-600 mb-2 block">Ketik ulang password</label>
                        <input name="confirm_password" type="password" class="w-full bg-white text-gray-700 font-sans font-normal outline outline-0 border-2 text-sm px-3 py-3 rounded-md border-gray-200 focus:border-gray-400">
                    </div>
                    <div class="w-full mt-8 flex justify-end">
                        <button type="submit" class="w-48 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-4 text-center mr-3 md:mr-0">Mendaftar</button>
                    </div>
                    <div class="my-8 text-left">
                        Sudah punya akun ? <a href="<?= site_url('login') ?>" class="underline text-red-800 hover:text-red-600">Silahkan masuk</a>.
                    </div>
                </form>
            </div>
            <div class="w-1/2">
                <img class="object-cover rounded-tr-lg rounded-br-lg h-full" src="https://images.unsplash.com/photo-1631116279964-70a0e168fce4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="">
            </div>
        </div>
    </div>
</main>