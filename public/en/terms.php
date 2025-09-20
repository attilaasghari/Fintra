<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions | Fintra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i data-feather="dollar-sign" class="text-blue-600"></i>
                <span class="font-bold text-xl">Fintra</span>
            </div>
            <div class="hidden md:flex space-x-6 items-center">
                <a href="/en" class="hover:text-blue-600">Home</a>
                <a href="index.php#features" class="hover:text-blue-600">Features</a>
                <a href="index.php#download" class="hover:text-blue-600">Download</a>
                <a href="index.php#contribute" class="hover:text-blue-600">Contribute</a>
                <a href="/" class="text-blue-600 hover:underline">فارسی</a>
                <a href="/?action=login" class="px-3 py-1 border border-blue-600 text-blue-600 rounded hover:bg-blue-50 transition">Login</a>
                <a href="/?action=register" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Register</a>
            </div>
            <button class="md:hidden">
                <i data-feather="menu"></i>
            </button>
        </div>
    </nav>

    <!-- Terms and Privacy Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 max-w-4xl">
            <h1 class="text-3xl font-bold mb-8 text-center">Terms and Conditions</h1>
            
            <div class="bg-white p-8 rounded-lg shadow-sm mb-8">
                <h2 class="text-2xl font-bold mb-4">Terms of Use</h2>
                <p class="mb-4">By accessing or using Fintra, you agree to be bound by these terms and conditions. Please read them carefully.</p>
                
                <h3 class="text-xl font-bold mt-6 mb-3">Use of Services</h3>
                <p class="mb-4">Fintra is an open-source project available free of charge to the public. You may use it for personal financial management.</p>
                
                <h3 class="text-xl font-bold mt-6 mb-3">Limitation of Liability</h3>
                <p class="mb-4">Fintra is provided in good faith but without any warranty, including but not limited to warranties of functionality or data accuracy. The developers are not liable for any damages arising from the use of this software.</p>
                
                <h3 class="text-xl font-bold mt-6 mb-3">Intellectual Property Rights</h3>
                <p class="mb-4">Fintra is released under the MIT license. This means you can view, modify, and distribute the source code, provided you retain the original license.</p>
                
                <h3 class="text-xl font-bold mt-6 mb-3">Usage Rules</h3>
                <ul class="list-disc pl-6 space-y-2 mb-4">
                    <li>You must not use Fintra for illegal purposes</li>
                    <li>You must not attempt to bypass Fintra's security systems</li>
                    <li>You are responsible for securing your own data</li>
                    <li>Developers may make changes to the services at any time</li>
                </ul>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <h2 class="text-2xl font-bold mb-4">Privacy Policy</h2>
                <p class="mb-4">Fintra is an open-source project that runs locally on your device. Your financial information is stored on your own device.</p>
                
                <h3 class="text-xl font-bold mt-6 mb-3">Data Collection</h3>
                <p class="mb-4">The local version of Fintra does not collect any of your personal information. All data is stored locally on your device.</p>
                
                <h3 class="text-xl font-bold mt-6 mb-3">Online Version</h3>
                <p class="mb-4">When using the online version of Fintra at vitren.ir, information such as your email, username, and financial data may be stored. This information is used solely for providing services.</p>
                
                <h3 class="text-xl font-bold mt-6 mb-3">Use of Information</h3>
                <p class="mb-4">Your information is used only for providing and improving Fintra services and is not shared with any third parties.</p>
                
                <h3 class="text-xl font-bold mt-6 mb-3">Your Rights</h3>
                <p class="mb-4">You have the right to view, modify, or delete your information at any time. You can contact us for this purpose.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 mb-4 md:mb-0">
                    <i data-feather="dollar-sign" class="text-blue-400"></i>
                    <span class="font-bold text-xl">Fintra</span>
                </div>
                
                <div class="flex space-x-6">
                    <a href="terms.php" class="hover:text-blue-400">Terms</a>
                    <a href="privacy.php" class="hover:text-blue-400">Privacy</a>
                    <a href="../" class="hover:text-blue-400">فارسی</a>
                    <a href="https://github.com/attilaasghari/Fintra" class="hover:text-blue-400">GitHub</a>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-6 pt-6 text-center text-gray-400">
                <p>© 2025 Fintra <a href="https://vitren.ir">vitren</a> - An open-source project under <a href="../LICENSE.txt">MIT</a> License</p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init();
        feather.replace();
    </script>
</body>
</html>