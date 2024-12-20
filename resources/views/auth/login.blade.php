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
                <svg class="size-4 mr-2" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
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
          apiKey: "{{ env('FIREBASE_API_KEY') }}",
          authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
          projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
          storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
          messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
          appId: "{{ env('FIREBASE_APP_ID') }}"
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
