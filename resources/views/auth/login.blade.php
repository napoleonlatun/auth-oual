<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Auth Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-700 flex items-center justify-center h-screen">

    <div class="bg-gray-800 p-10 rounded-xl shadow-2xl w-96 border border-gray-700">
        
        <h2 class="text-3xl font-extrabold text-white mb-6 text-center tracking-wide">
            Login
        </h2>

        @if ($errors->any())
            <div class="bg-red-509/10 border border-red-500 text-red-500 p-3 rounded-lg mb-6 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Email Address</label>
                <input type="email" name="email" required 
                    class="w-full bg-gray-900 text-white border border-gray-600 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Password</label>
                <input type="password" name="password" required 
                    class="w-full bg-gray-900 text-white border border-gray-600 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-red-500 text-white font-bold py-3 rounded-full shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                Sign In
            </button>
        </form>

        <p class="text-gray-500 text-sm text-center mt-6">
            New here? <a href="/register" class="text-blue-400 hover:text-blue-300 font-semibold hover:underline">Create an account</a>
        </p>
    </div>

</body>
</html>