<x-guest-layout>
    <div class="max-w-md w-full bg-gray-75 mt-12 p-8 space-y-6 mx-auto">
        <!-- Logo/Brand -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
            <p class="mt-2 text-sm text-gray-600">Please sign in to your account</p>
        </div>

        <!-- Login Form -->
        <form class="space-y-4" method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                    placeholder="you@example.com">
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                    placeholder="••••••••">
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember-me" class="h-4 w-4 text-sky-600 rounded border-gray-300">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>
                <a href="#" class="text-sm text-sky-600 hover:text-sky-500">Forgot password?</a>
            </div>

            <!-- Sign In Button -->
            <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                Sign in
            </button>
        </form>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-gray-50 text-gray-500">Or continue with</span>
            </div>
        </div>

        <!-- Social Login Buttons -->
        <div class="grid grid-cols-1 gap-3">
            <button
                onclick="loginWithGoogle()"
                class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="size-4 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z" />
                </svg>
                Google
            </button>
        </div>

        <!-- Sign Up Link -->
        <p class="text-center text-sm text-gray-600">
            Not a member?
            <a href="{{ route('register') }}" class="font-medium text-sky-600 hover:text-sky-500">Start a 14 day free
                trial</a>
        </p>

        <form id="social-login-form" action="/login/google/callback" method="POST" style="display: none;">
            {{ csrf_field() }}
            <input id="social-login-access-token" name="social-login-access-token" type="text">
            <input id="social-login-tokenId" name="social-login-tokenId" type="text">
        </form>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";
        const firebaseConfig = {
          apiKey: "AIzaSyA06wPu58r-hFpp-uStc7L9egoqab_2A6w",
          authDomain: "savoire-app.firebaseapp.com",
          projectId: "savoire-app",
          storageBucket: "savoire-app.firebasestorage.app",
          messagingSenderId: "172982158357",
          appId: "1:172982158357:web:d4d563df54aaa833ae3a1c"
        };
      
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        var googleProvider = new GoogleAuthProvider();
        async function loginWithGoogle() {
            console.log('loginWithGoogle')
            var socialProvider = null;
            var googleCallbackLink = '/login/google/callback';
        
            signInWithPopup(auth, googleProvider).then(function(result) {
                result.user.getIdToken().then(function(result) {
                    console.log(result)
                    document.getElementById('social-login-tokenId').value = result;
                    document.getElementById('social-login-form').submit();
                });
            }).catch(function(error) {
                console.log(error);
            });
        }
        window.loginWithGoogle = () => {
            loginWithGoogle().then(() => console.log('done'));
        }
      </script>

</x-guest-layout>
