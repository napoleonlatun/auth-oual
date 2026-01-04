<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Auth Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">

    <div class="bg-gray-800 p-10 rounded-xl shadow-2xl w-96 border border-gray-700">
        
        <h2 class="text-3xl font-extrabold text-white mb-6 text-center tracking-wide">
            Join the Lab
        </h2>

        <form action="/register" method="POST" class="space-y-5">
            @csrf
            
            <!-- Name Field -->
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Full Name</label>
                <input type="text" name="name" required 
                    class="w-full bg-gray-900 text-white border border-gray-600 rounded-lg p-3 focus:outline-none focus:border-blue-500 transition">
            </div>

            <!-- Email Field -->
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Email Address</label>
                <input type="email" name="email" required 
                    class="w-full bg-gray-900 text-white border border-gray-600 rounded-lg p-3 focus:outline-none focus:border-blue-500 transition">
            </div>

            <!-- Password Field -->
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Password</label>
                <input type="password" name="password" required 
                    class="w-full bg-gray-900 text-white border border-gray-600 rounded-lg p-3 focus:outline-none focus:border-blue-500 transition">
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" required 
                    class="w-full bg-gray-900 text-white border border-gray-600 rounded-lg p-3 focus:outline-none focus:border-blue-500 transition">
            </div>

            <!-- BUTTON (Match this color to your Login button!) -->
            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                Create Account
            </button>
        </form>

        <p class="text-gray-500 text-sm text-center mt-6">
            Already have an account? <a href="/login" class="text-blue-400 hover:text-blue-300 font-semibold hover:underline">Log in</a>
        </p>
    </div>

</body>
</html>