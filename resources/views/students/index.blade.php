<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Students - EduConnect</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>tailwind.config={theme:{extend:{colors:{primary:'#4f46e5',secondary:'#f97316'},borderRadius:{'none':'0px','sm':'4px',DEFAULT:'8px','md':'12px','lg':'16px','xl':'20px','2xl':'24px','3xl':'32px','full':'9999px','button':'8px'}}}}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
    <style>
        :where([class^="ri-"])::before { content: "\f3c2"; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background-color: #f3f4f6;
        }
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }
        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            transition: .4s;
            border-radius: 34px;
        }
        .switch-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .switch-slider {
            background-color: #4f46e5;
        }
        input:checked + .switch-slider:before {
            transform: translateX(20px);
        }
        .custom-radio {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .custom-radio-input {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            margin-right: 8px;
            position: relative;
            cursor: pointer;
        }
        .custom-radio-input:checked {
            border-color: #4f46e5;
        }
        .custom-radio-input:checked::after {
            content: "";
            position: absolute;
            top: 3px;
            left: 3px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #4f46e5;
        }
        .custom-checkbox {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .custom-checkbox-input {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            margin-right: 8px;
            position: relative;
            cursor: pointer;
        }
        .custom-checkbox-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        .custom-checkbox-input:checked::after {
            content: "";
            position: absolute;
            top: 2px;
            left: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .custom-range {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            border-radius: 5px;
            background: #e5e7eb;
            outline: none;
        }
        .custom-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #4f46e5;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .custom-range::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #4f46e5;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .student-modal {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            max-width: 600px;
            background-color: white;
            z-index: 50;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }
        .add-student-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-width: 500px;
            background-color: white;
            z-index: 50;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-col md:w-64 bg-white shadow-sm">
            <div class="p-4 flex items-center">
                <span class="font-['Pacifico'] text-primary text-2xl">EduConnect</span>
            </div>
            <div class="p-4">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                        <i class="ri-user-line text-primary ri-lg"></i>
                    </div>
                    <div>
                        <p class="font-medium">Emily Johnson</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full">Teacher</span>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto custom-scrollbar">
                <a href="https://readdy.ai/home/b8e20487-1c5f-4382-bb15-ebd7a3c4d48a/c7194a38-0291-4ef5-acc3-195b70ab57d1" data-readdy="true" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">
                    <div class="w-6 h-6 mr-3 flex items-center justify-center">
                        <i class="ri-dashboard-line"></i>
                    </div>
                    Dashboard
                </a>
                <a href="/students" class="flex items-center px-4 py-2.5 text-sm font-medium text-primary bg-primary/10 rounded-lg">
                    <div class="w-6 h-6 mr-3 flex items-center justify-center">
                        <i class="ri-user-line"></i>
                    </div>
                    Students
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">
                    <div class="w-6 h-6 mr-3 flex items-center justify-center">
                        <i class="ri-calendar-line"></i>
                    </div>
                    Attendance
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">
                    <div class="w-6 h-6 mr-3 flex items-center justify-center">
                        <i class="ri-book-open-line"></i>
                    </div>
                    Assignments
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">
                    <div class="w-6 h-6 mr-3 flex items-center justify-center">
                        <i class="ri-bar-chart-line"></i>
                    </div>
                    Grades
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">
                    <div class="w-6 h-6 mr-3 flex items-center justify-center">
                        <i class="ri-message-2-line"></i>
                    </div>
                    Messages
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">
                    <div class="w-6 h-6 mr-3 flex items-center justify-center">
                        <i class="ri-calendar-event-line"></i>
                    </div>
                    Events
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">
                    <div class="w-6 h-6 mr-3 flex items-center justify-center">
                        <i class="ri-settings-line"></i>
                    </div>
                    Settings
                </a>
            </nav>
            <div class="p-4 border-t">
                <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">
                    <div class="w-6 h-6 mr-3 flex items-center justify-center">
                        <i class="ri-logout-box-line"></i>
                    </div>
                    Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center md:hidden">
                        <button type="button" class="text-gray-500 hover:text-gray-600 p-2 rounded-md">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="ri-menu-line"></i>
                            </div>
                        </button>
                        <span class="font-['Pacifico'] text-primary text-xl ml-2">EduConnect</span>
                    </div>
                    <div class="hidden md:flex items-center flex-1 px-4">
                        <div class="relative max-w-md w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <div class="w-5 h-5 flex items-center justify-center text-gray-400">
                                    <i class="ri-search-line"></i>
                                </div>
                            </div>
                            <input type="text" class="bg-gray-50 border-none text-gray-900 text-sm rounded-lg block w-full pl-10 p-2.5 focus:ring-2 focus:ring-primary/20 focus:outline-none" placeholder="Search students, classes, or activities...">
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button type="button" class="relative p-2 text-gray-500 hover:text-gray-600 rounded-full">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="ri-notification-3-line"></i>
                                </div>
                                <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 rounded-full flex items-center justify-center text-xs text-white">3</span>
                            </button>
                        </div>
                        <div class="relative">
                            <button type="button" class="relative p-2 text-gray-500 hover:text-gray-600 rounded-full">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="ri-message-2-line"></i>
                                </div>
                                <span class="absolute top-0 right-0 h-4 w-4 bg-primary rounded-full flex items-center justify-center text-xs text-white">5</span>
                            </button>
                        </div>
                        <div class="relative ml-2">
                            <button type="button" class="flex items-center text-sm rounded-full focus:outline-none">
                                <img class="h-8 w-8 rounded-full object-cover" src="https://readdy.ai/api/search-image?query=professional%2520portrait%2520of%2520a%2520female%2520teacher%2520with%2520brown%2520hair%252C%2520warm%2520smile%252C%2520business%2520casual%2520attire%252C%2520neutral%2520background%252C%2520high%2520quality%252C%2520photorealistic&width=200&height=200&seq=teacher1&orientation=squarish" alt="User">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="border-b border-gray-200 px-4 py-2 flex items-center overflow-x-auto custom-scrollbar">
                    <div class="flex space-x-4" id="classNavigation">
                        <a href="/students" class="whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300" data-class="all">Overview</a>
                        <!-- Class buttons will be dynamically added here -->
                    </div>
                </div>

                <script>
                    // Add this to your existing script section
                    function loadClassNavigation() {
                        fetch('/api/students?page=1')
                            .then(response => response.json())
                            .then(data => {
                                const classNavigation = document.getElementById('classNavigation');
                                const classes = data.filters.classes || [];
                                
                                // Keep the Overview link
                                const overviewLink = classNavigation.querySelector('[data-class="all"]');
                                classNavigation.innerHTML = '';
                                classNavigation.appendChild(overviewLink);
                                
                                // Add class buttons
                                classes.forEach(className => {
                                    const button = document.createElement('button');
                                    button.className = 'whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300';
                                    button.textContent = className;
                                    button.setAttribute('data-class', className);
                                    
                                    button.addEventListener('click', function() {
                                        // Update active state
                                        classNavigation.querySelectorAll('a, button').forEach(el => {
                                            el.classList.remove('text-primary', 'border-primary');
                                            el.classList.add('text-gray-500', 'border-transparent');
                                        });
                                        this.classList.remove('text-gray-500', 'border-transparent');
                                        this.classList.add('text-primary', 'border-primary');
                                        
                                        // Update filters and load students
                                        currentFilters.class = className;
                                        loadStudents(1);
                                    });
                                    
                                    classNavigation.appendChild(button);
                                });
                                
                                // Set initial active state
                                const activeClass = currentFilters.class;
                                if (activeClass !== 'all') {
                                    const activeButton = classNavigation.querySelector(`[data-class="${activeClass}"]`);
                                    if (activeButton) {
                                        activeButton.classList.remove('text-gray-500', 'border-transparent');
                                        activeButton.classList.add('text-primary', 'border-primary');
                                    }
                                } else {
                                    overviewLink.classList.remove('text-gray-500', 'border-transparent');
                                    overviewLink.classList.add('text-primary', 'border-primary');
                                }
                            })
                            .catch(error => console.error('Error loading class navigation:', error));
                    }

                    // Add click handler for Overview link
                    document.addEventListener('DOMContentLoaded', function() {
                        const overviewLink = document.querySelector('[data-class="all"]');
                        if (overviewLink) {
                            overviewLink.addEventListener('click', function(e) {
                                e.preventDefault();
                                
                                // Update active state
                                document.querySelectorAll('#classNavigation a, #classNavigation button').forEach(el => {
                                    el.classList.remove('text-primary', 'border-primary');
                                    el.classList.add('text-gray-500', 'border-transparent');
                                });
                                this.classList.remove('text-gray-500', 'border-transparent');
                                this.classList.add('text-primary', 'border-primary');
                                
                                // Reset filters and load students
                                currentFilters.class = 'all';
                                loadStudents(1);
                            });
                        }
                        
                        // Load class navigation
                        loadClassNavigation();
                    });
                </script>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 custom-scrollbar">
                <div class="max-w-7xl mx-auto">
                    <!-- Breadcrumb -->
                    <nav class="flex mb-5" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="https://readdy.ai/home/b8e20487-1c5f-4382-bb15-ebd7a3c4d48a/c7194a38-0291-4ef5-acc3-195b70ab57d1" data-readdy="true" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary">
                                    <div class="w-4 h-4 mr-2 flex items-center justify-center">
                                        <i class="ri-dashboard-line"></i>
                                    </div>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 text-gray-400 mx-1 flex items-center justify-center">
                                        <i class="ri-arrow-right-s-line"></i>
                                    </div>
                                    <span class="text-sm font-medium text-primary">Students</span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <!-- Students Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Students</h1>
                            <p class="text-gray-600">Manage all your students in one place</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                            <button id="importBtn" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 !rounded-button whitespace-nowrap">
                                <div class="w-4 h-4 mr-2 flex items-center justify-center">
                                    <i class="ri-upload-2-line"></i>
                                </div>
                                Import
                            </button>
                            <button id="exportBtn" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 !rounded-button whitespace-nowrap">
                                <div class="w-4 h-4 mr-2 flex items-center justify-center">
                                    <i class="ri-download-2-line"></i>
                                </div>
                                Export
                            </button>
                            <input type="file" id="importFile" class="hidden" accept=".csv">
                            <button id="addStudentBtn" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 !rounded-button whitespace-nowrap">
                                <div class="w-4 h-4 mr-2 flex items-center justify-center">
                                    <i class="ri-user-add-line"></i>
                                </div>
                                Add New Student
                            </button>
                        </div>
                    </div>

                    <!-- Search and Filter Section -->
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <div class="w-5 h-5 flex items-center justify-center text-gray-400">
                                            <i class="ri-search-line"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="bg-gray-50 border-none text-gray-900 text-sm rounded-lg block w-full pl-10 p-2.5 focus:ring-2 focus:ring-primary/20 focus:outline-none" placeholder="Search by name, ID, class, or email...">
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <div class="relative">
                                    <select id="classFilter" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 !rounded-button whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        <option value="all">All Classes</option>
                                    </select>
                                </div>
                                <div class="relative">
                                    <select id="statusFilter" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 !rounded-button whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        <option value="all">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="on leave">On Leave</option>
                                    </select>
                                </div>
                                <div class="relative">
                                    <select id="performanceFilter" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 !rounded-button whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        <option value="all">All Performance</option>
                                        <option value="excellent">Excellent (90-100%)</option>
                                        <option value="good">Good (80-89%)</option>
                                        <option value="average">Average (70-79%)</option>
                                        <option value="below_average">Below Average (60-69%)</option>
                                        <option value="poor">Poor (<60%)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Active Filters -->
                        <div class="flex flex-wrap gap-2 mt-4" id="activeFilters">
                            <!-- Active filters will be dynamically added here -->
                        </div>
                    </div>

                    <script>
                        // Add this to your existing script section
                        let currentFilters = {
                            class: 'all',
                            status: 'all',
                            performance: 'all'
                        };

                        function updateActiveFilters() {
                            const activeFiltersContainer = document.getElementById('activeFilters');
                            activeFiltersContainer.innerHTML = '';

                            Object.entries(currentFilters).forEach(([key, value]) => {
                                if (value !== 'all') {
                                    const filterDiv = document.createElement('div');
                                    filterDiv.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary/10 text-primary';
                                    
                                    let displayValue = value;
                                    if (key === 'performance') {
                                        displayValue = document.getElementById('performanceFilter').options[document.getElementById('performanceFilter').selectedIndex].text;
                                    }
                                    
                                    filterDiv.innerHTML = `
                                        ${key.charAt(0).toUpperCase() + key.slice(1)}: ${displayValue}
                                        <button class="ml-2 text-primary" onclick="removeFilter('${key}')">
                                            <div class="w-4 h-4 flex items-center justify-center">
                                                <i class="ri-close-line"></i>
                                            </div>
                                        </button>
                                    `;
                                    activeFiltersContainer.appendChild(filterDiv);
                                }
                            });

                            if (Object.values(currentFilters).some(v => v !== 'all')) {
                                const clearAllButton = document.createElement('button');
                                clearAllButton.className = 'text-sm text-primary hover:text-primary/80';
                                clearAllButton.textContent = 'Clear All Filters';
                                clearAllButton.onclick = clearAllFilters;
                                activeFiltersContainer.appendChild(clearAllButton);
                            }
                        }

                        function removeFilter(filterKey) {
                            currentFilters[filterKey] = 'all';
                            document.getElementById(filterKey + 'Filter').value = 'all';
                            loadStudents(1);
                            updateActiveFilters(); // Add this line to update the display
                        }

                        function clearAllFilters() {
                            currentFilters = {
                                class: 'all',
                                status: 'all',
                                performance: 'all'
                            };
                            document.getElementById('classFilter').value = 'all';
                            document.getElementById('statusFilter').value = 'all';
                            document.getElementById('performanceFilter').value = 'all';
                            loadStudents(1);
                            updateActiveFilters(); // Add this line to update the display
                        }

                        // Update the loadStudents function
                        function loadStudents(page = 1) {
                            // Show loader
                            const tableLoader = document.getElementById('tableLoader');
                            tableLoader.classList.remove('hidden');
                            
                            currentPage = page;
                            const url = new URL('/api/students', window.location.origin);
                            url.searchParams.append('page', page);
                            if (searchQuery) url.searchParams.append('search', searchQuery);
                            if (currentFilters.class !== 'all') url.searchParams.append('class', currentFilters.class);
                            if (currentFilters.status !== 'all') url.searchParams.append('status', currentFilters.status);
                            if (currentFilters.performance !== 'all') url.searchParams.append('performance', currentFilters.performance);

                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    updateTable(data.data.data);
                                    updatePagination(data.data);
                                    updateStats(data.data);
                                    updateActiveFilters();
                                    
                                    // Update class filter options
                                    const classFilter = document.getElementById('classFilter');
                                    classFilter.innerHTML = '<option value="all">All Classes</option>';
                                    data.filters.classes.forEach(className => {
                                        const option = document.createElement('option');
                                        option.value = className;
                                        option.textContent = className;
                                        classFilter.appendChild(option);
                                    });
                                    classFilter.value = currentFilters.class;
                                })
                                .catch(error => {
                                    console.error('Error loading students:', error);
                                    // Show error message
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to load students. Please try again.'
                                    });
                                })
                                .finally(() => {
                                    // Hide loader
                                    tableLoader.classList.add('hidden');
                                });
                        }

                        // Add event listeners for filters
                        document.addEventListener('DOMContentLoaded', function() {
                            const classFilter = document.getElementById('classFilter');
                            const statusFilter = document.getElementById('statusFilter');
                            const performanceFilter = document.getElementById('performanceFilter');

                            classFilter.addEventListener('change', function() {
                                currentFilters.class = this.value;
                                loadStudents(1);
                                updateActiveFilters();
                            });

                            statusFilter.addEventListener('change', function() {
                                currentFilters.status = this.value;
                                loadStudents(1);
                                updateActiveFilters();
                            });

                            performanceFilter.addEventListener('change', function() {
                                currentFilters.performance = this.value;
                                loadStudents(1);
                                updateActiveFilters();
                            });

                            // Initial load
                            loadStudents();
                        });
                    </script>

                    <!-- Students List -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 mb-6">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">All Students <span class="text-gray-500 text-sm">(<span id="totalStudents">0</span>)</span></h2>
                            <div class="flex items-center">
                                <label class="custom-checkbox mr-4">
                                    <input type="checkbox" class="custom-checkbox-input" id="selectAllStudents">
                                    <span class="text-sm text-gray-700">Select All</span>
                                </label>
                                <div class="relative">
                                    <button id="bulkActionsBtn" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 !rounded-button whitespace-nowrap">
                                        Bulk Actions
                                        <div class="w-4 h-4 ml-2 flex items-center justify-center">
                                            <i class="ri-arrow-down-s-line"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Table -->
                        <div class="overflow-x-auto relative">
                            <!-- Loading Overlay -->
                            <div id="tableLoader" class="hidden absolute inset-0 bg-white/80 z-10 flex items-center justify-center">
                                <div class="flex flex-col items-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
                                    <p class="mt-2 text-sm text-gray-600">Loading students...</p>
                                </div>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                            <span class="sr-only">Select</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Class
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Performance
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Attendance
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contact
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="studentsTableBody">
                                    <!-- Table rows will be dynamically inserted here -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button id="prevPageMobile" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 !rounded-button whitespace-nowrap">
                                    Previous
                                </button>
                                <button id="nextPageMobile" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 !rounded-button whitespace-nowrap">
                                    Next
                                </button>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span id="showingFrom" class="font-medium">0</span> to <span id="showingTo" class="font-medium">0</span> of <span id="totalCount" class="font-medium">0</span> students
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination" id="pagination">
                                        <!-- Pagination links will be dynamically inserted here -->
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mr-4">
                                    <div class="w-6 h-6 flex items-center justify-center text-primary">
                                        <i class="ri-user-line"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Students</p>
                                    <h3 class="text-2xl font-bold text-gray-900" id="totalStudentsCount">0</h3>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-sm">
                                <div class="w-4 h-4 flex items-center justify-center text-green-500 mr-1" id="totalStudentsIcon">
                                    <i class="ri-arrow-up-line"></i>
                                </div>
                                <span class="text-green-500 font-medium" id="totalStudentsChange">0%</span>
                                <span class="text-gray-500 ml-1">from last month</span>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <div class="w-6 h-6 flex items-center justify-center text-blue-600">
                                        <i class="ri-bar-chart-line"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Average Performance</p>
                                    <h3 class="text-2xl font-bold text-gray-900" id="averagePerformance">0%</h3>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-sm">
                                <div class="w-4 h-4 flex items-center justify-center text-green-500 mr-1" id="performanceIcon">
                                    <i class="ri-arrow-up-line"></i>
                                </div>
                                <span class="text-green-500 font-medium" id="performanceChange">0%</span>
                                <span class="text-gray-500 ml-1">from last semester</span>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <div class="w-6 h-6 flex items-center justify-center text-green-600">
                                        <i class="ri-calendar-check-line"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Attendance Rate</p>
                                    <h3 class="text-2xl font-bold text-gray-900" id="attendanceRate">0%</h3>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-sm">
                                <div class="w-4 h-4 flex items-center justify-center text-green-500 mr-1" id="attendanceIcon">
                                    <i class="ri-arrow-up-line"></i>
                                </div>
                                <span class="text-green-500 font-medium" id="attendanceChange">0%</span>
                                <span class="text-gray-500 ml-1">from last month</span>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mr-4">
                                    <div class="w-6 h-6 flex items-center justify-center text-amber-600">
                                        <i class="ri-flag-line"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">At-Risk Students</p>
                                    <h3 class="text-2xl font-bold text-gray-900" id="atRiskStudents">0</h3>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-sm">
                                <div class="w-4 h-4 flex items-center justify-center text-red-500 mr-1" id="atRiskIcon">
                                    <i class="ri-arrow-up-line"></i>
                                </div>
                                <span class="text-red-500 font-medium" id="atRiskChange">0%</span>
                                <span class="text-gray-500 ml-1">from last month</span>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Add this to your existing script section
                        function loadStudentStats() {
                            fetch('/api/students-stats')
                                .then(response => response.json())
                                .then(data => {
                                    // Update Total Students
                                    document.getElementById('totalStudentsCount').textContent = data.total_students.value;
                                    document.getElementById('totalStudentsChange').textContent = `${data.total_students.change}%`;
                                    updateChangeIndicator('totalStudents', data.total_students.change_type);

                                    // Update Average Performance
                                    document.getElementById('averagePerformance').textContent = `${data.average_performance.value}%`;
                                    document.getElementById('performanceChange').textContent = `${data.average_performance.change}%`;
                                    updateChangeIndicator('performance', data.average_performance.change_type);

                                    // Update Attendance Rate
                                    document.getElementById('attendanceRate').textContent = `${data.attendance_rate.value}%`;
                                    document.getElementById('attendanceChange').textContent = `${data.attendance_rate.change}%`;
                                    updateChangeIndicator('attendance', data.attendance_rate.change_type);

                                    // Update At-Risk Students
                                    document.getElementById('atRiskStudents').textContent = data.at_risk_students.value;
                                    document.getElementById('atRiskChange').textContent = `${data.at_risk_students.change}%`;
                                    updateChangeIndicator('atRisk', data.at_risk_students.change_type);
                                })
                                .catch(error => console.error('Error loading student stats:', error));
                        }

                        function updateChangeIndicator(type, changeType) {
                            const icon = document.getElementById(`${type}Icon`);
                            const change = document.getElementById(`${type}Change`);
                            
                            if (changeType === 'increase') {
                                icon.className = 'w-4 h-4 flex items-center justify-center text-green-500 mr-1';
                                icon.innerHTML = '<i class="ri-arrow-up-line"></i>';
                                change.className = 'text-green-500 font-medium';
                            } else {
                                icon.className = 'w-4 h-4 flex items-center justify-center text-red-500 mr-1';
                                icon.innerHTML = '<i class="ri-arrow-down-line"></i>';
                                change.className = 'text-red-500 font-medium';
                            }
                        }

                        // Call loadStudentStats when the page loads
                        document.addEventListener('DOMContentLoaded', function() {
                            loadStudentStats();
                            // Refresh stats every 5 minutes
                            setInterval(loadStudentStats, 300000);
                        });
                    </script>

                    <!-- Performance Distribution Chart -->
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium text-gray-900">Performance Distribution</h2>
                            <div class="relative">
                                <select id="performanceClassFilter" class="text-gray-400 hover:text-gray-500 flex items-center text-sm bg-transparent border-none focus:ring-0">
                                    <option value="all">All Classes</option>
                                </select>
                            </div>
                        </div>
                        <div class="h-80" id="performance-distribution-chart"></div>
                    </div>

                    <script>
                        // Chart instances
                        let performanceDistributionChart = null;
                        let studentPerformanceChart = null;
                        let studentAttendanceChart = null;

                        // Initialize charts
                        function initializeCharts() {
                            // Initialize Performance Distribution Chart
                            const performanceChartElement = document.getElementById('performance-distribution-chart');
                            if (performanceChartElement) {
                                performanceDistributionChart = echarts.init(performanceChartElement);
                            }

                            // Initialize Student Performance Chart (for modal)
                            const studentPerformanceElement = document.getElementById('student-performance-chart');
                            if (studentPerformanceElement) {
                                studentPerformanceChart = echarts.init(studentPerformanceElement);
                            }

                            // Initialize Student Attendance Chart (for modal)
                            const studentAttendanceElement = document.getElementById('student-attendance-chart');
                            if (studentAttendanceElement) {
                                studentAttendanceChart = echarts.init(studentAttendanceElement);
                            }
                        }

                        // Safe resize function
                        function safeResizeCharts() {
                            if (performanceDistributionChart) {
                                performanceDistributionChart.resize();
                            }
                            if (studentPerformanceChart) {
                                studentPerformanceChart.resize();
                            }
                            if (studentAttendanceChart) {
                                studentAttendanceChart.resize();
                            }
                        }

                        // Update the window resize handler
                        window.addEventListener('resize', safeResizeCharts);

                        // Initialize charts when DOM is loaded
                        document.addEventListener('DOMContentLoaded', function() {
                            initializeCharts();
                            
                            // Initialize performance distribution
                            const performanceClassFilter = document.getElementById('performanceClassFilter');
                            if (performanceClassFilter) {
                                performanceClassFilter.addEventListener('change', function() {
                                    loadPerformanceDistribution(this.value);
                                });
                            }
                            loadPerformanceDistribution();
                        });

                        function loadPerformanceDistribution(classFilter = 'all') {
                            fetch(`/api/performance-distribution?class=${classFilter}`)
                                .then(response => response.json())
                                .then(data => {
                                    // Update class filter options
                                    const classFilterSelect = document.getElementById('performanceClassFilter');
                                    if (classFilterSelect) {
                                        classFilterSelect.innerHTML = '<option value="all">All Classes</option>';
                                        data.classes.forEach(className => {
                                            const option = document.createElement('option');
                                            option.value = className;
                                            option.textContent = className;
                                            classFilterSelect.appendChild(option);
                                        });
                                        classFilterSelect.value = classFilter;
                                    }

                                    // Prepare chart data
                                    const series = [];
                                    const colors = [
                                        'rgba(87, 181, 231, 1)',
                                        'rgba(141, 211, 199, 1)',
                                        'rgba(251, 191, 114, 1)',
                                        'rgba(252, 141, 98, 1)'
                                    ];

                                    // Ensure distribution is always an array
                                    const distributionData = Array.isArray(data.distribution) ? data.distribution : [data.distribution];
                                    
                                    // Clear previous series
                                    series.length = 0;
                                    
                                    distributionData.forEach((item, index) => {
                                        if (item) {  // Check if item exists
                                            series.push({
                                                name: item.class,
                                                type: 'bar',
                                                data: [
                                                    item.below_60,
                                                    item.between_60_70,
                                                    item.between_70_80,
                                                    item.between_80_90,
                                                    item.above_90
                                                ],
                                                itemStyle: {
                                                    color: colors[index % colors.length],
                                                    borderRadius: [4, 4, 0, 0]
                                                },
                                                emphasis: {
                                                    itemStyle: {
                                                        opacity: 0.8
                                                    }
                                                }
                                            });
                                        }
                                    });

                                    // Update chart if it exists
                                    if (performanceDistributionChart) {
                                        const performanceDistributionOption = {
                                            animation: false,
                                            tooltip: {
                                                trigger: 'axis',
                                                backgroundColor: 'rgba(255, 255, 255, 0.8)',
                                                borderColor: '#E5E7EB',
                                                textStyle: {
                                                    color: '#1F2937'
                                                }
                                            },
                                            legend: {
                                                data: distributionData.map(item => item.class),
                                                bottom: 0,
                                                textStyle: {
                                                    color: '#1F2937'
                                                }
                                            },
                                            grid: {
                                                left: '3%',
                                                right: '3%',
                                                top: '3%',
                                                bottom: '15%',
                                                containLabel: true
                                            },
                                            xAxis: {
                                                type: 'category',
                                                data: ['<60%', '60-70%', '70-80%', '80-90%', '90-100%'],
                                                axisLine: {
                                                    lineStyle: {
                                                        color: '#E5E7EB'
                                                    }
                                                },
                                                axisLabel: {
                                                    color: '#6B7280'
                                                }
                                            },
                                            yAxis: {
                                                type: 'value',
                                                name: 'Number of Students',
                                                nameTextStyle: {
                                                    color: '#6B7280'
                                                },
                                                axisLine: {
                                                    show: false
                                                },
                                                axisLabel: {
                                                    color: '#6B7280'
                                                },
                                                splitLine: {
                                                    lineStyle: {
                                                        color: '#F3F4F6'
                                                    }
                                                }
                                            },
                                            series: series
                                        };
                                        
                                        // Clear previous chart data
                                        performanceDistributionChart.clear();
                                        // Set new options
                                        performanceDistributionChart.setOption(performanceDistributionOption, true);
                                    }
                                })
                                .catch(error => console.error('Error loading performance distribution:', error));
                        }
                    </script>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-10">
        <div class="flex justify-around">
            <a href="https://readdy.ai/home/b8e20487-1c5f-4382-bb15-ebd7a3c4d48a/c7194a38-0291-4ef5-acc3-195b70ab57d1" data-readdy="true" class="flex flex-col items-center py-2 px-3 text-gray-500">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-dashboard-line"></i>
                </div>
                <span class="text-xs mt-1">Dashboard</span>
            </a>
            <a href="#" class="flex flex-col items-center py-2 px-3 text-primary">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-user-line"></i>
                </div>
                <span class="text-xs mt-1">Students</span>
            </a>
            <a href="#" class="flex flex-col items-center py-2 px-3 text-gray-500">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-calendar-line"></i>
                </div>
                <span class="text-xs mt-1">Schedule</span>
            </a>
            <a href="#" class="flex flex-col items-center py-2 px-3 text-gray-500">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-message-2-line"></i>
                </div>
                <span class="text-xs mt-1">Messages</span>
            </a>
            <a href="#" class="flex flex-col items-center py-2 px-3 text-gray-500">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-menu-line"></i>
                </div>
                <span class="text-xs mt-1">More</span>
            </a>
        </div>
    </div>

    <!-- Student Profile Modal -->
    <div class="modal-overlay" id="studentModalOverlay"></div>
    <div class="student-modal custom-scrollbar" id="studentProfileModal">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">Student Profile</h2>
            <button id="closeStudentModal" class="text-gray-500 hover:text-gray-700">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-close-line"></i>
                </div>
            </button>
        </div>
        <div class="p-6">
            <!-- Student Basic Info -->
            <div class="flex flex-col items-center mb-6">
                <img class="w-24 h-24 rounded-full mb-4 object-cover" src="https://readdy.ai/api/search-image?query=portrait%2520of%2520a%2520teenage%2520boy%2520student%2520with%2520brown%2520hair%252C%2520smiling%252C%2520school%2520uniform%252C%2520classroom%2520background%252C%2520high%2520quality%252C%2520photorealistic&width=200&height=200&seq=student1&orientation=squarish" alt="Student">
                <h3 class="text-xl font-bold text-gray-900">Michael Anderson</h3>
                <p class="text-gray-500">STU-2025-001</p>
                <div class="flex mt-2 space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                        Class 8A
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                </div>
                <div class="flex mt-4 space-x-3">
                    <button class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 !rounded-button whitespace-nowrap">
                        <div class="w-4 h-4 mr-2 flex items-center justify-center">
                            <i class="ri-message-2-line"></i>
                        </div>
                        Message
                    </button>
                    <button class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 !rounded-button whitespace-nowrap">
                        <div class="w-4 h-4 mr-2 flex items-center justify-center">
                            <i class="ri-edit-line"></i>
                        </div>
                        Edit
                    </button>
                </div>
            </div>

            <!-- Student Details Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <div class="flex space-x-8">
                    <button class="px-1 py-4 text-sm font-medium text-primary border-b-2 border-primary">Overview</button>
                    <button class="px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300">Academics</button>
                    <button class="px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300">Attendance</button>
                    <button class="px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300">Notes</button>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Date of Birth</p>
                        <p class="text-sm font-medium text-gray-900">June 12, 2010</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Gender</p>
                        <p class="text-sm font-medium text-gray-900">Male</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-sm font-medium text-gray-900">michael.anderson@example.com</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="text-sm font-medium text-gray-900">+1 (555) 123-4567</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="text-sm font-medium text-gray-900">123 Main Street, Anytown, CA 94321</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Enrollment Date</p>
                        <p class="text-sm font-medium text-gray-900">September 1, 2023</p>
                    </div>
                </div>
            </div>

            <!-- Parent/Guardian Information -->
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Parent/Guardian Information</h4>
                <div class="space-y-4">
                    <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                        <img class="w-10 h-10 rounded-full mr-4 object-cover" src="https://readdy.ai/api/search-image?query=professional%2520portrait%2520of%2520a%2520middle-aged%2520woman%2520with%2520blonde%2520hair%252C%2520warm%2520smile%252C%2520business%2520casual%2520attire%252C%2520neutral%2520background%252C%2520high%2520quality%252C%2520photorealistic&width=200&height=200&seq=parent1&orientation=squarish" alt="Parent">
                        <div>
                            <h5 class="text-sm font-medium text-gray-900">Rebecca Anderson (Mother)</h5>
                            <p class="text-sm text-gray-500">rebecca.anderson@example.com</p>
                            <p class="text-sm text-gray-500">+1 (555) 987-6543</p>
                            <div class="mt-2">
                                <button class="inline-flex items-center px-2 py-1 text-xs font-medium text-primary bg-primary/10 rounded-full !rounded-button whitespace-nowrap">
                                    <div class="w-3 h-3 mr-1 flex items-center justify-center">
                                        <i class="ri-message-2-line"></i>
                                    </div>
                                    Message
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                        <img class="w-10 h-10 rounded-full mr-4 object-cover" src="https://readdy.ai/api/search-image?query=professional%2520portrait%2520of%2520a%2520middle-aged%2520man%2520with%2520brown%2520hair%252C%2520warm%2520smile%252C%2520business%2520casual%2520attire%252C%2520neutral%2520background%252C%2520high%2520quality%252C%2520photorealistic&width=200&height=200&seq=parent2&orientation=squarish" alt="Parent">
                        <div>
                            <h5 class="text-sm font-medium text-gray-900">David Anderson (Father)</h5>
                            <p class="text-sm text-gray-500">david.anderson@example.com</p>
                            <p class="text-sm text-gray-500">+1 (555) 876-5432</p>
                            <div class="mt-2">
                                <button class="inline-flex items-center px-2 py-1 text-xs font-medium text-primary bg-primary/10 rounded-full !rounded-button whitespace-nowrap">
                                    <div class="w-3 h-3 mr-1 flex items-center justify-center">
                                        <i class="ri-message-2-line"></i>
                                    </div>
                                    Message
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Performance -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-medium text-gray-900">Academic Performance</h4>
                    <button class="text-sm font-medium text-primary">View Full Report</button>
                </div>
                <div class="h-64" id="student-performance-chart"></div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Mathematics</p>
                        <p class="text-lg font-medium text-gray-900">85%</p>
                        <div class="flex items-center text-xs mt-1">
                            <div class="w-3 h-3 flex items-center justify-center text-green-500 mr-1">
                                <i class="ri-arrow-up-line"></i>
                            </div>
                            <span class="text-green-500">+15%</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Science</p>
                        <p class="text-lg font-medium text-gray-900">78%</p>
                        <div class="flex items-center text-xs mt-1">
                            <div class="w-3 h-3 flex items-center justify-center text-green-500 mr-1">
                                <i class="ri-arrow-up-line"></i>
                            </div>
                            <span class="text-green-500">+5%</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">English</p>
                        <p class="text-lg font-medium text-gray-900">92%</p>
                        <div class="flex items-center text-xs mt-1">
                            <div class="w-3 h-3 flex items-center justify-center text-green-500 mr-1">
                                <i class="ri-arrow-up-line"></i>
                            </div>
                            <span class="text-green-500">+3%</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">History</p>
                        <p class="text-lg font-medium text-gray-900">75%</p>
                        <div class="flex items-center text-xs mt-1">
                            <div class="w-3 h-3 flex items-center justify-center text-red-500 mr-1">
                                <i class="ri-arrow-down-line"></i>
                            </div>
                            <span class="text-red-500">-2%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Record -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-medium text-gray-900">Attendance Record</h4>
                    <button class="text-sm font-medium text-primary">View Full Record</button>
                </div>
                <div class="h-48" id="student-attendance-chart"></div>
                <div class="flex justify-between mt-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Present</p>
                        <p class="text-lg font-medium text-gray-900">92%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Absent</p>
                        <p class="text-lg font-medium text-gray-900">5%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Late</p>
                        <p class="text-lg font-medium text-gray-900">3%</p>
                    </div>
                </div>
            </div>

            <!-- Recent Notes -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-medium text-gray-900">Recent Notes</h4>
                    <button class="text-sm font-medium text-primary">Add Note</button>
                </div>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="text-sm font-medium text-gray-900">Math Progress</h5>
                            <p class="text-xs text-gray-500">April 25, 2025</p>
                        </div>
                        <p class="text-sm text-gray-700">Michael has shown significant improvement in his math problem-solving skills. His approach to complex problems is becoming more methodical.</p>
                        <p class="text-xs text-gray-500 mt-2">Added by: Emily Johnson</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="text-sm font-medium text-gray-900">Science Project</h5>
                            <p class="text-xs text-gray-500">April 15, 2025</p>
                        </div>
                        <p class="text-sm text-gray-700">Michael demonstrated excellent teamwork skills during the group science project. He took initiative in coordinating tasks and helped other team members.</p>
                        <p class="text-xs text-gray-500 mt-2">Added by: Robert Chen</p>
                    </div>
                </div>
            </div>

            <!-- Back to Dashboard -->
            <div class="mt-8 text-center">
                <a href="https://readdy.ai/home/b8e20487-1c5f-4382-bb15-ebd7a3c4d48a/c7194a38-0291-4ef5-acc3-195b70ab57d1" data-readdy="true" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 !rounded-button whitespace-nowrap">
                    <div class="w-4 h-4 mr-2 flex items-center justify-center">
                        <i class="ri-arrow-left-line"></i>
                    </div>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal-overlay" id="addStudentModalOverlay"></div>
    <div class="add-student-modal" id="addStudentModal">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">Add New Student</h2>
            <button id="closeAddStudentModal" class="text-gray-500 hover:text-gray-700">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-close-line"></i>
                </div>
            </button>
        </div>
        <div class="p-6">
            <form id="addStudentForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Enter first name" required>
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Enter last name" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Enter email address" required>
                    </div>
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Enter phone number" required>
                    </div>
                    <div>
                        <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" id="dob" name="dob" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" required>
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select id="gender" name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" required>
                            <option value="">Select gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                        <select id="class" name="class" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" required>
                            <option value="">Select class</option>
                            <option value="Class 8A">Class 8A</option>
                            <option value="Class 9B">Class 9B</option>
                            <option value="Class 10C">Class 10C</option>
                            <option value="Class 11A">Class 11A</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" required>
                            <option value="">Select status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="on leave">On Leave</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" id="address" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Enter full address" required>
                </div>

                <div class="mb-6">
                    <h3 class="text-md font-medium text-gray-900 mb-3">Parent/Guardian Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Name</label>
                            <input type="text" id="parent_name" name="parent_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Enter name" required>
                        </div>
                        <div>
                            <label for="relationship" class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                            <select id="relationship" name="relationship" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" required>
                                <option value="">Select relationship</option>
                                <option value="Mother">Mother</option>
                                <option value="Father">Father</option>
                                <option value="Guardian">Guardian</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="email_parent" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email_parent" name="email_parent" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Enter email address" required>
                        </div>
                        <div>
                            <label for="phone_parent" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="phone_parent" name="phone_parent" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Enter phone number" required>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelAddStudent" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 !rounded-button whitespace-nowrap">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 !rounded-button whitespace-nowrap">Add Student</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Performance Distribution Chart
            const performanceDistributionChart = echarts.init(document.getElementById('performance-distribution-chart'));
            const performanceDistributionOption = {
                animation: false,
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(255, 255, 255, 0.8)',
                    borderColor: '#E5E7EB',
                    textStyle: {
                        color: '#1F2937'
                    }
                },
                legend: {
                    data: ['Class 8A', 'Class 9B', 'Class 10C', 'Class 11A'],
                    bottom: 0,
                    textStyle: {
                        color: '#1F2937'
                    }
                },
                grid: {
                    left: '3%',
                    right: '3%',
                    top: '3%',
                    bottom: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: ['<60%', '60-70%', '70-80%', '80-90%', '90-100%'],
                    axisLine: {
                        lineStyle: {
                            color: '#E5E7EB'
                        }
                    },
                    axisLabel: {
                        color: '#6B7280'
                    }
                },
                yAxis: {
                    type: 'value',
                    name: 'Number of Students',
                    nameTextStyle: {
                        color: '#6B7280'
                    },
                    axisLine: {
                        show: false
                    },
                    axisLabel: {
                        color: '#6B7280'
                    },
                    splitLine: {
                        lineStyle: {
                            color: '#F3F4F6'
                        }
                    }
                },
                series: [
                    {
                        name: 'Class 8A',
                        type: 'bar',
                        data: [2, 5, 12, 8, 5],
                        itemStyle: {
                            color: 'rgba(87, 181, 231, 1)',
                            borderRadius: [4, 4, 0, 0]
                        },
                        emphasis: {
                            itemStyle: {
                                opacity: 0.8
                            }
                        }
                    },
                    {
                        name: 'Class 9B',
                        type: 'bar',
                        data: [3, 7, 10, 6, 4],
                        itemStyle: {
                            color: 'rgba(141, 211, 199, 1)',
                            borderRadius: [4, 4, 0, 0]
                        },
                        emphasis: {
                            itemStyle: {
                                opacity: 0.8
                            }
                        }
                    },
                    {
                        name: 'Class 10C',
                        type: 'bar',
                        data: [1, 6, 9, 10, 6],
                        itemStyle: {
                            color: 'rgba(251, 191, 114, 1)',
                            borderRadius: [4, 4, 0, 0]
                        },
                        emphasis: {
                            itemStyle: {
                                opacity: 0.8
                            }
                        }
                    },
                    {
                        name: 'Class 11A',
                        type: 'bar',
                        data: [0, 4, 8, 12, 10],
                        itemStyle: {
                            color: 'rgba(252, 141, 98, 1)',
                            borderRadius: [4, 4, 0, 0]
                        },
                        emphasis: {
                            itemStyle: {
                                opacity: 0.8
                            }
                        }
                    }
                ]
            };
            performanceDistributionChart.setOption(performanceDistributionOption);

            // Student Performance Chart (for modal)
            if (document.getElementById('student-performance-chart')) {
                const studentPerformanceChart = echarts.init(document.getElementById('student-performance-chart'));
                const studentPerformanceOption = {
                    animation: false,
                    tooltip: {
                        trigger: 'axis',
                        backgroundColor: 'rgba(255, 255, 255, 0.8)',
                        borderColor: '#E5E7EB',
                        textStyle: {
                            color: '#1F2937'
                        }
                    },
                    legend: {
                        data: ['Current Term', 'Previous Term'],
                        bottom: 0,
                        textStyle: {
                            color: '#1F2937'
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '3%',
                        top: '3%',
                        bottom: '15%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: ['Math', 'Science', 'English', 'History', 'Geography', 'Art'],
                        axisLine: {
                            lineStyle: {
                                color: '#E5E7EB'
                            }
                        },
                        axisLabel: {
                            color: '#6B7280'
                        }
                    },
                    yAxis: {
                        type: 'value',
                        max: 100,
                        axisLine: {
                            show: false
                        },
                        axisLabel: {
                            color: '#6B7280',
                            formatter: '{value}%'
                        },
                        splitLine: {
                            lineStyle: {
                                color: '#F3F4F6'
                            }
                        }
                    },
                    series: [
                        {
                            name: 'Current Term',
                            type: 'bar',
                            data: [85, 78, 92, 75, 80, 88],
                            itemStyle: {
                                color: 'rgba(87, 181, 231, 1)',
                                borderRadius: [4, 4, 0, 0]
                            },
                            emphasis: {
                                itemStyle: {
                                    opacity: 0.8
                                }
                            }
                        },
                        {
                            name: 'Previous Term',
                            type: 'bar',
                            data: [70, 73, 89, 77, 75, 85],
                            itemStyle: {
                                color: 'rgba(251, 191, 114, 1)',
                                borderRadius: [4, 4, 0, 0]
                            },
                            emphasis: {
                                itemStyle: {
                                    opacity: 0.8
                                }
                            }
                        }
                    ]
                };
                studentPerformanceChart.setOption(studentPerformanceOption);
            }

            // Student Attendance Chart (for modal)
            if (document.getElementById('student-attendance-chart')) {
                const studentAttendanceChart = echarts.init(document.getElementById('student-attendance-chart'));
                const studentAttendanceOption = {
                    animation: false,
                    tooltip: {
                        trigger: 'item',
                        backgroundColor: 'rgba(255, 255, 255, 0.8)',
                        borderColor: '#E5E7EB',
                        textStyle: {
                            color: '#1F2937'
                        }
                    },
                    legend: {
                        orient: 'horizontal',
                        bottom: 0,
                        textStyle: {
                            color: '#1F2937'
                        }
                    },
                    series: [
                        {
                            name: 'Attendance',
                            type: 'pie',
                            radius: ['40%', '70%'],
                            avoidLabelOverlap: false,
                            itemStyle: {
                                borderRadius: 8,
                                borderColor: '#fff',
                                borderWidth: 2
                            },
                            label: {
                                show: false,
                                position: 'center'
                            },
                            emphasis: {
                                label: {
                                    show: true,
                                    fontSize: '16',
                                    fontWeight: 'bold'
                                }
                            },
                            labelLine: {
                                show: false
                            },
                            data: [
                                { value: 92, name: 'Present', itemStyle: { color: 'rgba(87, 181, 231, 1)' } },
                                { value: 5, name: 'Absent', itemStyle: { color: 'rgba(252, 141, 98, 1)' } },
                                { value: 3, name: 'Late', itemStyle: { color: 'rgba(251, 191, 114, 1)' } }
                            ]
                        }
                    ]
                };
                studentAttendanceChart.setOption(studentAttendanceOption);
            }

            // Resize charts when window size changes
            window.addEventListener('resize', function() {
                performanceDistributionChart.resize();
                if (document.getElementById('student-performance-chart')) {
                    studentPerformanceChart.resize();
                }
                if (document.getElementById('student-attendance-chart')) {
                    studentAttendanceChart.resize();
                }
            });
        });

        // Custom Checkbox Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const customCheckboxes = document.querySelectorAll('.custom-checkbox-input');
            customCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('click', function() {
                    this.checked = !this.checked;
                });
            });

            // Select All Students checkbox
            const selectAllCheckbox = document.getElementById('selectAllStudents');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('click', function() {
                    const isChecked = this.checked;
                    studentCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                });
            }
        });

        // Student Profile Modal
        document.addEventListener('DOMContentLoaded', function() {
            const studentRows = document.querySelectorAll('tr[data-student-id]');
            const studentModal = document.getElementById('studentProfileModal');
            const studentModalOverlay = document.getElementById('studentModalOverlay');
            const closeStudentModalBtn = document.getElementById('closeStudentModal');
            
            studentRows.forEach(row => {
                row.addEventListener('click', function(e) {
                    // Don't open modal if clicking on checkboxes or action buttons
                    if (e.target.closest('.custom-checkbox') || e.target.closest('button')) {
                        return;
                    }
                    
                    studentModal.style.display = 'block';
                    studentModalOverlay.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                    
                    // Initialize charts if they exist
                    if (window.echarts) {
                        if (document.getElementById('student-performance-chart')) {
                            window.echarts.getInstanceByDom(document.getElementById('student-performance-chart')).resize();
                        }
                        if (document.getElementById('student-attendance-chart')) {
                            window.echarts.getInstanceByDom(document.getElementById('student-attendance-chart')).resize();
                        }
                    }
                });
            });
            
            if (closeStudentModalBtn) {
                closeStudentModalBtn.addEventListener('click', function() {
                    studentModal.style.display = 'none';
                    studentModalOverlay.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }
            
            if (studentModalOverlay) {
                studentModalOverlay.addEventListener('click', function() {
                    studentModal.style.display = 'none';
                    studentModalOverlay.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }
        });

        // Add Student Modal
        document.addEventListener('DOMContentLoaded', function() {
            const addStudentBtn = document.getElementById('addStudentBtn');
            const addStudentModal = document.getElementById('addStudentModal');
            const addStudentModalOverlay = document.getElementById('addStudentModalOverlay');
            const closeAddStudentModalBtn = document.getElementById('closeAddStudentModal');
            const cancelAddStudentBtn = document.getElementById('cancelAddStudent');
            
            if (addStudentBtn) {
                addStudentBtn.addEventListener('click', function() {
                    addStudentModal.style.display = 'block';
                    addStudentModalOverlay.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            }
            
            if (closeAddStudentModalBtn) {
                closeAddStudentModalBtn.addEventListener('click', function() {
                    addStudentModal.style.display = 'none';
                    addStudentModalOverlay.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }
            
            if (cancelAddStudentBtn) {
                cancelAddStudentBtn.addEventListener('click', function() {
                    addStudentModal.style.display = 'none';
                    addStudentModalOverlay.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }
            
            if (addStudentModalOverlay) {
                addStudentModalOverlay.addEventListener('click', function() {
                    addStudentModal.style.display = 'none';
                    addStudentModalOverlay.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }
        });

        // Add this to your existing script section
        let currentPage = 1;
        let totalPages = 1;
        let searchQuery = '';
        let classFilter = '';
        let statusFilter = '';

        function loadStudents(page = 1) {
                            currentPage = page;
                            const url = new URL('/api/students', window.location.origin);
                            url.searchParams.append('page', page);
                            if (searchQuery) url.searchParams.append('search', searchQuery);
                            if (currentFilters.class !== 'all') url.searchParams.append('class', currentFilters.class);
                            if (currentFilters.status !== 'all') url.searchParams.append('status', currentFilters.status);
                            if (currentFilters.performance !== 'all') url.searchParams.append('performance', currentFilters.performance);

                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    updateTable(data.data.data);
                                    updatePagination(data.data);
                                    updateStats(data.data);
                                    
                                    // Update class filter options
                                    const classFilter = document.getElementById('classFilter');
                                    classFilter.innerHTML = '<option value="all">All Classes</option>';
                                    data.filters.classes.forEach(className => {
                                        const option = document.createElement('option');
                                        option.value = className;
                                        option.textContent = className;
                                        classFilter.appendChild(option);
                                    });
                                    classFilter.value = currentFilters.class;
                })
                .catch(error => console.error('Error loading students:', error));
        }

        function updateTable(students) {
            const tbody = document.getElementById('studentsTableBody');
            tbody.innerHTML = '';

            if (!Array.isArray(students)) {
                console.error('Expected students to be an array, got:', typeof students);
                return;
            }

            students.forEach(student => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 cursor-pointer';
                row.setAttribute('data-student-id', student.id);
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <label class="custom-checkbox">
                            <input type="checkbox" class="custom-checkbox-input student-checkbox">
                            <span class="sr-only">Select student</span>
                        </label>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover" src="${student.avatar_url}" alt="Student">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${student.first_name} ${student.last_name}</div>
                                <div class="text-sm text-gray-500">${student.email}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${student.student_id}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${student.class}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 max-w-[100px]">
                                <div class="bg-primary h-2.5 rounded-full" style="width: ${student.performance}%"></div>
                            </div>
                            <span class="text-sm text-gray-900">${student.performance}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${student.attendance}%</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${student.phone_number}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(student.status)}">
                            ${student.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <button class="text-gray-500 hover:text-primary" title="Edit">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="ri-edit-line"></i>
                                </div>
                            </button>
                            <button class="text-gray-500 hover:text-primary" title="Message">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="ri-message-2-line"></i>
                                </div>
                            </button>
                            <button class="text-gray-500 hover:text-gray-700" title="More">
                                <div class="w-6 h-6 flex items-center justify-center">
                                    <i class="ri-more-2-line"></i>
                                </div>
                            </button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });

            // Reattach event listeners for the new rows
            attachRowEventListeners();
        }

        function updateStats(data) {
            // Update total students count
            document.getElementById('totalStudentsCount').textContent = data.total || 0;
            
            // Update performance stats if available
            if (data.average_performance) {
                document.getElementById('averagePerformance').textContent = `${data.average_performance}%`;
            }
            
            // Update attendance stats if available
            if (data.average_attendance) {
                document.getElementById('attendanceRate').textContent = `${data.average_attendance}%`;
            }
            
            // Update at-risk students count if available
            if (data.at_risk_students) {
                document.getElementById('atRiskStudents').textContent = data.at_risk_students;
            }
        }

        function updatePagination(data) {
            const pagination = document.getElementById('pagination');
            totalPages = data.last_page;
            
            let paginationHtml = `
                <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" 
                        ${currentPage === 1 ? 'disabled' : ''} onclick="loadStudents(${currentPage - 1})">
                    <span class="sr-only">Previous</span>
                    <div class="w-5 h-5 flex items-center justify-center">
                        <i class="ri-arrow-left-s-line"></i>
                    </div>
                </button>
            `;

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    paginationHtml += `
                        <button class="relative inline-flex items-center px-4 py-2 border text-sm font-medium ${i === currentPage ? 'z-10 bg-primary text-white border-primary' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'}" 
                                onclick="loadStudents(${i})">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    paginationHtml += `
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            ...
                        </span>
                    `;
                }
            }

            paginationHtml += `
                <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" 
                        ${currentPage === totalPages ? 'disabled' : ''} onclick="loadStudents(${currentPage + 1})">
                    <span class="sr-only">Next</span>
                    <div class="w-5 h-5 flex items-center justify-center">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                </button>
            `;

            pagination.innerHTML = paginationHtml;

            // Update showing text
            document.getElementById('showingFrom').textContent = data.from || 0;
            document.getElementById('showingTo').textContent = data.to || 0;
            document.getElementById('totalCount').textContent = data.total || 0;
            document.getElementById('totalStudents').textContent = data.total || 0;
        }

        function getStatusClass(status) {
            switch (status.toLowerCase()) {
                case 'active':
                    return 'bg-green-100 text-green-800';
                case 'on leave':
                    return 'bg-yellow-100 text-yellow-800';
                case 'inactive':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        function attachRowEventListeners() {
            const rows = document.querySelectorAll('tr[data-student-id]');
            rows.forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('.custom-checkbox') || e.target.closest('button')) {
                        return;
                    }
                    const studentId = this.getAttribute('data-student-id');
                    loadStudentDetails(studentId);
                });
            });
        }

        // Initialize the table when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadStudents();
            
            // Add search functionality
            const searchInput = document.querySelector('input[placeholder="Search by name, ID, class, or email..."]');
            let searchTimeout;
            
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchQuery = e.target.value;
                    loadStudents(1);
                }, 300);
            });
            
            // Add filter functionality
            const classFilterBtn = document.getElementById('classFilterBtn');
            const statusFilterBtn = document.getElementById('statusFilterBtn');
            
            if (classFilterBtn) {
                classFilterBtn.addEventListener('click', function() {
                    // Implement class filter dropdown
                });
            }
            
            if (statusFilterBtn) {
                statusFilterBtn.addEventListener('click', function() {
                    // Implement status filter dropdown
                });
            }
        });

        // Replace the existing handleAddStudent function
        function handleAddStudent(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            
            // Add CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Show loading state
            Swal.fire({
                title: 'Adding Student...',
                text: 'Please wait while we process your request',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/api/students', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Close the modal
                    const modal = document.getElementById('addStudentModal');
                    const modalOverlay = document.getElementById('addStudentModalOverlay');
                    if (modal) {
                        modal.style.display = 'none';
                    }
                    if (modalOverlay) {
                        modalOverlay.style.display = 'none';
                    }
                    document.body.style.overflow = '';
                    
                    // Reset the form
                    form.reset();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Student has been added successfully',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload the students list
                        loadStudents(1);
                    });
                } else {
                    // Handle validation errors
                    if (result.errors) {
                        let errorMessage = '<ul class="text-left">';
                        Object.keys(result.errors).forEach(key => {
                            errorMessage += `<li>${result.errors[key][0]}</li>`;
                        });
                        errorMessage += '</ul>';
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessage
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message || 'Failed to add student'
                        });
                    }
                }
                                })
                                .catch(error => {
                console.error('Error:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                    text: 'An error occurred while adding the student'
                });
            });
        }

        // Update the form submission handler
        document.addEventListener('DOMContentLoaded', function() {
            const addStudentForm = document.getElementById('addStudentForm');
            if (addStudentForm) {
                addStudentForm.addEventListener('submit', handleAddStudent);
            }
        });

        // Add Import/Export functionality
        document.addEventListener('DOMContentLoaded', function() {
            const importBtn = document.getElementById('importBtn');
            const exportBtn = document.getElementById('exportBtn');
            const importFile = document.getElementById('importFile');

            // Import functionality
            importBtn.addEventListener('click', function() {
                importFile.click();
            });

            importFile.addEventListener('change', function(e) {
                if (!e.target.files || !e.target.files[0]) {
                    return;
                }

                const file = e.target.files[0];
                
                // Validate file type
                if (file.type !== 'text/csv' && !file.name.endsWith('.csv')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please upload a CSV file.'
                    });
                    e.target.value = '';
                    return;
                }

                const formData = new FormData();
                formData.append('file', file);

                // Show loading state
                Swal.fire({
                    title: 'Importing Students...',
                    text: 'Please wait while we process your CSV file.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send file to server
                fetch('/api/students/import', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Import Successful',
                            html: data.message + (data.errors && data.errors.length > 0 ? 
                                '<br><br><strong>Errors:</strong><br>' + data.errors.join('<br>') : ''),
                            confirmButtonText: 'OK'
                        }).then(() => {
                            loadStudents(1); // Reload the table
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Import Failed',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        text: 'An error occurred while importing the CSV file.'
                                    });
                                })
                                .finally(() => {
                    // Reset file input
                    e.target.value = '';
                });
            });

            // Export functionality
            exportBtn.addEventListener('click', function() {
                // Get current filters
                const params = new URLSearchParams();
                if (currentFilters.class !== 'all') params.append('class', currentFilters.class);
                if (currentFilters.status !== 'all') params.append('status', currentFilters.status);
                if (currentFilters.performance !== 'all') params.append('performance', currentFilters.performance);

                // Show loading state
                Swal.fire({
                    title: 'Exporting Students...',
                    text: 'Please wait while we prepare your CSV file.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create a temporary link to trigger the download
                const link = document.createElement('a');
                link.href = `/api/students/export?${params.toString()}`;
                link.setAttribute('download', `students_export_${new Date().toISOString().split('T')[0]}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Export Started',
                    text: 'Your CSV file download should begin shortly.',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    </script>
</body>
</html>
