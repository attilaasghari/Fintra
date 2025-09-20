<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fintra | Personal Finance Tracker</title>
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
                <a href="#features" class="hover:text-blue-600">Features</a>
                <a href="#download" class="hover:text-blue-600">Download</a>
                <a href="#contribute" class="hover:text-blue-600">Contribute</a>
                <a href="terms.php" class="hover:text-blue-600">Terms</a>
                <a href="privacy.php" class="hover:text-blue-600">Privacy</a>
                <a href="/" class="text-blue-600 hover:underline">فارسی</a>
                <a href="/?action=login" class="px-3 py-1 border border-blue-600 text-blue-600 rounded hover:bg-blue-50 transition">Login</a>
                <a href="/?action=register" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Register</a>
            </div>
            <button class="md:hidden">
                <i data-feather="menu"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-16 md:py-24 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0" data-aos="fade-right">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Personal Finance Management with <span class="text-blue-600">Fintra</span></h1>
                <p class="text-lg text-gray-600 mb-6">A simple open-source system to track income, expenses, debts, and installments</p>
                <div class="flex flex-wrap gap-3">
                    <a href="#download" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition">Download Now</a>
                    <a href="https://github.com/attilaasghari/Fintra" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-md font-medium transition flex items-center gap-2">
                        <i data-feather="github"></i>
                        GitHub Repository
                    </a>
                </div>
            </div>
            <div class="md:w-1/2" data-aos="fade-left">
                <img src="http://static.photos/finance/640x360/1" alt="Fintra Dashboard" class="rounded-lg shadow-lg border border-gray-200">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-12">Key Features of Fintra</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="dollar-sign" class="text-blue-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Income & Expense Management</h3>
                    <p class="text-gray-600">Record and categorize personal income and expenses with full details</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="credit-card" class="text-green-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Debt & Loan Tracking</h3>
                    <p class="text-gray-600">Manage debts, credits, and loan installments with automatic reminders</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="file-text" class="text-purple-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Professional Reports</h3>
                    <p class="text-gray-600">Generate various reports in PDF and Excel formats for financial analysis</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="bell" class="text-yellow-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Automatic Reminders</h3>
                    <p class="text-gray-600">Automatic reminders for payment and receipt deadlines</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="database" class="text-red-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Easy Backup</h3>
                    <p class="text-gray-600">Simple data backup and recovery capabilities</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="code" class="text-indigo-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Open Source & Extendable</h3>
                    <p class="text-gray-600">Open source code that developers can extend and customize</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Download Section -->
    <section id="download" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl font-bold mb-6">Get Fintra</h2>
                <p class="text-gray-600 mb-8">You can install Fintra on your own server or use the online version</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm" data-aos="fade-right">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="download" class="text-blue-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-3">Local Download & Install</h3>
                        <p class="text-gray-600 mb-4">Download the project files and install on localhost or your own server</p>
                        <a href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition">Download Latest Version</a>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-sm" data-aos="fade-left">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="cloud" class="text-green-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-3">Online Usage</h3>
                        <p class="text-gray-600 mb-4">Use the online version of Fintra without any installation</p>
                        <a href="https://fintra.vitren.ir" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium transition">Access Online Version</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contribute Section -->
    <section id="contribute" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-12">Contribute to Fintra</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center" data-aos="fade-up">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="code" class="text-purple-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Coding</h3>
                        <p class="text-gray-600">Help develop new features or fix bugs in the project</p>
                    </div>
                    
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="book" class="text-yellow-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Documentation</h3>
                        <p class="text-gray-600">Improve project documentation for easier usage</p>
                    </div>
                    
                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="message-circle" class="text-red-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Report Issues</h3>
                        <p class="text-gray-600">Report bugs and issues so they can be fixed</p>
                    </div>
                </div>
                
                <div class="mt-12 text-center">
                    <a href="https://github.com/attilaasghari/Fintra" class="inline-flex items-center bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-md font-medium transition">
                        <i data-feather="github" class="mr-2"></i>
                        Contribute on GitHub
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 mb-4 md:mb-0">
                    <i data-feather="dollar-sign" class="text-blue-400"></i>
                    <span class="font-bold text-xl">Fintra</span>
                </div>
                
                <div class="flex space-x-6">
                    <a href="terms.php" class="hover:text-blue-400">Terms</a>
                    <a href="privacy.php" class="hover:text-blue-400">Privacy</a>
                    <a href="/" class="hover:text-blue-400">فارسی</a>
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