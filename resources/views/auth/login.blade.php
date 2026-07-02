<x-guest-layout>

<div class="flex items-center justify-center w-full">

    <div class="backdrop-blur-lg bg-white/20 p-8 rounded-2xl shadow-xl border border-white/30 w-[380px]">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="/images/logo.png" class="h-16">
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Username / Email -->
            <div>
                <label class="text-white text-sm">Username / Email</label>
                <input type="text" name="login" 
                class="w-full mt-1 p-2 rounded bg-white/80 outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Password -->
            <!--div>
                <label class="text-white text-sm">Password</label>
                <input type="password" name="password"
                    class="w-full mt-1 p-2 rounded bg-white/80 outline-none focus:ring-2 focus:ring-blue-400">
            </!--div-->

            <!-- Password -->
            <div>
                <label class="text-white text-sm">Password</label>
                <div style="position:relative; width:100%;">

                    
                <!--Temporary Fix-->
                    <!--input id="password" name="password" type="password" required autocomplete="current-password" 
                            style=" width:100%; padding:10px; padding-right:45px; border-radius:6px; background:white; color:black;box-sizing:border-box;">

                                <button-- type="button" id="togglePassword" style=" position:absolute; top:50%; right:12px; transform:translateY(-50%); border:none; background:none;cursor:pointer; padding:0; margin:0;">👁</button-->
                    
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full p-2 rounded bg-white/80 outline-none focus:ring-2 focus:ring-blue-400" 
                        style="
                            padding:10px;
                            padding-right:45px;
                            box-sizing:border-box;">


                    <button type="button" id="togglePassword" 
                        style="
                            position:absolute;
                            top:50%;
                            right:12px;
                            transform:translateY(-50%);
                            border:none;
                            background:transparent;
                            cursor:pointer;
                            padding:0;
                            display:flex;
                            align-items:center;
                            justify-content:center;">

                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M15 12a3 3 0 11-6 0
                                3 3 0 016 0zm6 0s-3-7-9-7-9 7-9 7 3 7 9 7 9-7 9-7"/>
                        </svg>

                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="hidden" width="20" height="20" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M6 6l12 12M9.88 9.88A3 3 0 0114.12 14.12M13.875
                                18.825A10.05 10.05 0 0112 19c-5
                                0-9-7-9-7a17.3 17.3 0 013.05-3.95"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember -->
            <div class="flex items-center text-white text-sm">
                <input type="checkbox" name="remember">
                <span class="ml-2">Remember me</span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Button -->
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
                    Log In
                </button>

                <!--button-->
                <a href="{{ route('register') }}" class="flex justify-center text-green-500 hover:bg-green-600 py-2 rounded-lg transition">
                    Register
                </a>
            </div>
            

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const password = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (!password || !toggle) return;
    toggle.addEventListener('click', function (e) {
        e.preventDefault();
        if (password.type === 'password') {
            password.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            password.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    });
});
</script>

<!--Test Script-->
<!--script>
document.getElementById("togglePassword").onclick = function () {

    const password = document.getElementById("password");

    password.type =
        password.type === "password"
            ? "text"
            : "password";
};
</!--script-->

</x-guest-layout>