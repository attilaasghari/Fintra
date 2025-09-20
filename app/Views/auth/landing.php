<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فینترا | سیستم مدیریت مالی شخصی</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap');
        body {
            font-family: 'Vazirmatn', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-2 space-x-reverse">
                <i data-feather="dollar-sign" class="text-blue-600"></i>
                <span class="font-bold text-xl">فینترا</span>
            </div>
            <div class="hidden md:flex space-x-6 space-x-reverse items-center">
                <a href="#features" class="hover:text-blue-600">امکانات</a>
                <a href="#download" class="hover:text-blue-600">دانلود</a>
                <a href="#contribute" class="hover:text-blue-600">مشارکت</a>
                <a href="terms.php" class="hover:text-blue-600">قوانین</a>
                <a href="privacy.php" class="hover:text-blue-600">حریم خصوصی</a>
                <a href="/en" class="text-blue-600 hover:underline">English</a>
                <a href="https://fintra.vitren.ir" class="text-blue-600 hover:underline">نسخه آنلاین</a>
                <a href="https://fintra.vitren.ir/?action=login" class="px-3 py-1 border border-blue-600 text-blue-600 rounded hover:bg-blue-50 transition">ورود</a>
                <a href="https://fintra.vitren.ir/?action=register" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">ثبت‌نام</a>
            </div>
            <button class="md:hidden">
                <i data-feather="menu"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-16 md:py-24 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0" data-aos="fade-left">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">مدیریت مالی شخصی با <span class="text-blue-600">فینترا</span></h1>
                <p class="text-lg text-gray-600 mb-6">یک سیستم ساده و متن باز برای پیگیری درآمدها، هزینه‌ها، بدهی‌ها و اقساط</p>
                <div class="flex flex-wrap gap-3">
                    <a href="#download" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition">دانلود کنید</a>
                    <a href="https://github.com/attilaasghari/Fintra" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-md font-medium transition flex items-center gap-2">
                        <i data-feather="github"></i>
                        مخزن گیت‌هاب
                    </a>
                </div>
            </div>
            <div class="md:w-1/2" data-aos="fade-right">
                <img src="http://static.photos/finance/640x360/1" alt="Fintra Dashboard" class="rounded-lg shadow-lg border border-gray-200">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-12">امکانات اصلی فینترا</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="dollar-sign" class="text-blue-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">مدیریت درآمد و هزینه</h3>
                    <p class="text-gray-600">ثبت و دسته‌بندی درآمدها و هزینه‌های شخصی با جزئیات کامل</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="credit-card" class="text-green-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">پیگیری بدهی‌ها و وام‌ها</h3>
                    <p class="text-gray-600">مدیریت بدهی‌ها، طلب‌ها و اقساط وام با امکان یادآوری خودکار</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="file-text" class="text-purple-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">گزارش‌های حرفه‌ای</h3>
                    <p class="text-gray-600">تهیه گزارش‌های مختلف در قالب PDF و Excel برای تحلیل مالی</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="bell" class="text-yellow-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">یادآوری خودکار</h3>
                    <p class="text-gray-600">یادآوری سررسید پرداخت‌ها و دریافت‌ها به صورت خودکار</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="database" class="text-red-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">پشتیبان‌گیری آسان</h3>
                    <p class="text-gray-600">امکان پشتیبان‌گیری و بازیابی اطلاعات به سادگی</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="code" class="text-indigo-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">متن باز و قابل توسعه</h3>
                    <p class="text-gray-600">کدهای باز و قابل توسعه برای برنامه‌نویسان</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Download Section -->
    <section id="download" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl font-bold mb-6">فینترا را دریافت کنید</h2>
                <p class="text-gray-600 mb-8">می‌توانید فینترا را روی سرور شخصی خود نصب کنید یا از نسخه آنلاین آن استفاده نمایید</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm" data-aos="fade-right">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="download" class="text-blue-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-3">دانلود و نصب محلی</h3>
                        <p class="text-gray-600 mb-4">فایل‌های پروژه را دریافت کرده و روی لوکال‌هاست یا سرور خود نصب کنید</p>
                        <a href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition">دانلود آخرین نسخه</a>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-sm" data-aos="fade-left">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="cloud" class="text-green-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-3">استفاده آنلاین</h3>
                        <p class="text-gray-600 mb-4">بدون نیاز به نصب، همین حالا از نسخه آنلاین فینترا استفاده کنید</p>
                        <a href="https://fintra.vitren.ir" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium transition">ورود به نسخه آنلاین</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contribute Section -->
    <section id="contribute" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-12">به فینترا کمک کنید</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center" data-aos="fade-up">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="code" class="text-purple-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-2">کدنویسی</h3>
                        <p class="text-gray-600">با توسعه ویژگی‌های جدید یا رفع باگ‌ها به پروژه کمک کنید</p>
                    </div>
                    
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="book" class="text-yellow-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-2">مستندسازی</h3>
                        <p class="text-gray-600">به بهبود مستندات پروژه برای استفاده آسان‌تر کمک کنید</p>
                    </div>
                    
                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="message-circle" class="text-red-600"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-2">گزارش مشکل</h3>
                        <p class="text-gray-600">باگ‌ها و مشکلات را گزارش دهید تا رفع شوند</p>
                    </div>
                </div>
                
                <div class="mt-12 text-center">
                    <a href="https://github.com/attilaasghari/Fintra" class="inline-flex items-center bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-md font-medium transition">
                        <i data-feather="github" class="ml-2"></i>
                        مشارکت در گیت‌هاب
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 space-x-reverse mb-4 md:mb-0">
                    <i data-feather="dollar-sign" class="text-blue-400"></i>
                    <span class="font-bold text-xl">فینترا</span>
                </div>
                
                <div class="flex space-x-6 space-x-reverse">
                    <a href="terms.php" class="hover:text-blue-400">قوانین</a>
                    <a href="privacy.php" class="hover:text-blue-400">حریم خصوصی</a>
                    <a href="/en" class="hover:text-blue-400">English</a>
                    <a href="https://github.com/attilaasghari/Fintra" class="hover:text-blue-400">GitHub</a>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-6 pt-6 text-center text-gray-400">
                <p>© 2025 Fintra & <a href="https://vitren.ir">Vitren</a>- یک پروژه متن باز تحت مجوز <a href="LICENSE.txt">MIT</a></p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init();
        feather.replace();
    </script>
</body>
</html>
