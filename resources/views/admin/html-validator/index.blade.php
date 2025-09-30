@extends('layouts.admin')

@section('title', 'HTML Validator')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">HTML Validator</h1>
        <p class="mt-1 text-sm text-gray-600">Validate HTML structure, accessibility, and best practices</p>
    </div>
    <div class="flex gap-3">
        <button type="button" id="validatePageBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c0 5-4 9-9 9s-9-4-9-9m9 9c5 0 9-4 9-9"></path>
            </svg>
            Validate Page
        </button>
        <button type="button" id="batchValidateBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Batch Validate
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-md">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Valid Pages</dt>
                        <dd class="text-lg font-medium text-gray-900" id="validPagesCount">-</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-md">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Errors</dt>
                        <dd class="text-lg font-medium text-gray-900" id="totalErrorsCount">-</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-md">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Warnings</dt>
                        <dd class="text-lg font-medium text-gray-900" id="totalWarningsCount">-</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-md">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Average Score</dt>
                        <dd class="text-lg font-medium text-gray-900" id="averageScore">-</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Validator Interface -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- HTML Input -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">HTML Input</h3>
                <p class="mt-1 text-sm text-gray-600">Paste your HTML code below for validation</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <textarea
                            id="htmlInput"
                            class="w-full h-64 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"
                            placeholder="Paste your HTML code here..."
                        ></textarea>
                    </div>

                    <!-- Validation Options -->
                    <div class="space-y-3">
                        <h4 class="text-sm font-medium text-gray-900">Validation Options</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" id="validateAccessibility" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Accessibility</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="validatePerformance" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Performance</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="validateSEO" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">SEO</span>
                            </label>
                        </div>
                    </div>

                    <button
                        type="button"
                        id="validateBtn"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Validate HTML
                    </button>
                </div>
            </div>
        </div>

        <!-- Validation Results -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Validation Results</h3>
                <p class="mt-1 text-sm text-gray-600">HTML validation results will appear here</p>
            </div>
            <div class="p-6">
                <div id="validationResults" class="text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>No validation results yet. Enter HTML code and click "Validate HTML" to begin.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Validations -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Validations</h3>
            <p class="mt-1 text-sm text-gray-600">History of your recent HTML validations</p>
        </div>
        <div class="p-6">
            <div id="recentValidations" class="space-y-4">
                <div class="text-center text-gray-500 py-8">
                    <p>No recent validations found.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Validation Modal -->
<div id="pageValidationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c0 5-4 9-9 9s-9-4-9-9m9 9c5 0 9-4 9-9"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 ml-4">Validate Page</h3>
            </div>
            <div class="mt-2">
                <input
                    type="url"
                    id="pageUrl"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter page URL (e.g., https://example.com)"
                >
            </div>
            <div class="items-center px-4 py-3">
                <button
                    id="validatePageConfirm"
                    class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                    Validate Page
                </button>
                <button
                    id="closePageModal"
                    class="mt-3 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Batch Validation Modal -->
<div id="batchValidationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 ml-4">Batch Validate</h3>
            </div>
            <div class="mt-2">
                <textarea
                    id="batchUrls"
                    class="w-full h-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Enter URLs (one per line)&#10;https://example.com&#10;https://example.com/about&#10;https://example.com/contact"
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">Maximum 10 URLs</p>
            </div>
            <div class="items-center px-4 py-3">
                <button
                    id="batchValidateConfirm"
                    class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300"
                >
                    Start Batch Validation
                </button>
                <button
                    id="closeBatchModal"
                    class="mt-3 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const validateBtn = document.getElementById('validateBtn');
    const htmlInput = document.getElementById('htmlInput');
    const validationResults = document.getElementById('validationResults');
    const pageValidationModal = document.getElementById('pageValidationModal');
    const batchValidationModal = document.getElementById('batchValidationModal');

    // Sample HTML for testing
    const sampleHtml = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Page</title>
    <meta name="description" content="This is a sample page for HTML validation testing">
</head>
<body>
    <header>
        <h1>Welcome to Our Website</h1>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="hero">
            <h2>Hero Section</h2>
            <p>This is a sample hero section with proper semantic HTML.</p>
            <img src="hero-image.jpg" alt="Hero banner showing our services">
        </section>

        <section id="contact-form">
            <h2>Contact Us</h2>
            <form>
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit">Send Message</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Sample Website. All rights reserved.</p>
    </footer>
</body>
</html>`;

    // Load sample HTML on page load
    htmlInput.value = sampleHtml;

    // Validate HTML
    validateBtn.addEventListener('click', function() {
        const html = htmlInput.value.trim();

        if (!html) {
            alert('Please enter HTML code to validate');
            return;
        }

        validateBtn.disabled = true;
        validateBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Validating...';

        fetch('{{ route("admin.html-validator.validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                html: html,
                validate_accessibility: document.getElementById('validateAccessibility').checked,
                validate_performance: document.getElementById('validatePerformance').checked,
                validate_seo: document.getElementById('validateSEO').checked
            })
        })
        .then(response => response.json())
        .then(data => {
            displayValidationResults(data);
            updateStats(data);
            addToRecentValidations(data, 'HTML Code');
        })
        .catch(error => {
            console.error('Error:', error);
            validationResults.innerHTML = '<div class="text-red-600">Error validating HTML: ' + error.message + '</div>';
        })
        .finally(() => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Validate HTML';
        });
    });

    // Display validation results
    function displayValidationResults(data) {
        const { valid, score, summary, issues } = data;

        let html = `
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            ${valid ?
                                '<div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full"><svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>' :
                                '<div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full"><svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></div>'
                            }
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium ${valid ? 'text-green-900' : 'text-red-900'}">${valid ? 'Valid HTML' : 'Invalid HTML'}</h4>
                            <p class="text-sm text-gray-600">Score: ${score}/100</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold ${score >= 80 ? 'text-green-600' : score >= 60 ? 'text-yellow-600' : 'text-red-600'}">${score}</div>
                        <div class="text-xs text-gray-500">out of 100</div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-red-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-red-600">${summary.errors}</div>
                        <div class="text-xs text-red-800">Errors</div>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-yellow-600">${summary.warnings}</div>
                        <div class="text-xs text-yellow-800">Warnings</div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-blue-600">${summary.suggestions}</div>
                        <div class="text-xs text-blue-800">Suggestions</div>
                    </div>
                </div>
        `;

        // Display issues
        if (issues.errors.length > 0 || issues.warnings.length > 0 || issues.suggestions.length > 0) {
            html += `<div class="space-y-3">`;

            if (issues.errors.length > 0) {
                html += `
                    <div>
                        <h5 class="text-sm font-medium text-red-900 mb-2">Errors (${issues.errors.length})</h5>
                        <div class="space-y-2">
                            ${issues.errors.map(error => `
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h6 class="text-sm font-medium text-red-800">${error.type.replace(/_/g, ' ').toUpperCase()}</h6>
                                            <p class="text-sm text-red-700">${error.message}</p>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            if (issues.warnings.length > 0) {
                html += `
                    <div>
                        <h5 class="text-sm font-medium text-yellow-900 mb-2">Warnings (${issues.warnings.length})</h5>
                        <div class="space-y-2">
                            ${issues.warnings.map(warning => `
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h6 class="text-sm font-medium text-yellow-800">${warning.type.replace(/_/g, ' ').toUpperCase()}</h6>
                                            <p class="text-sm text-yellow-700">${warning.message}</p>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            if (issues.suggestions.length > 0) {
                html += `
                    <div>
                        <h5 class="text-sm font-medium text-blue-900 mb-2">Suggestions (${issues.suggestions.length})</h5>
                        <div class="space-y-2">
                            ${issues.suggestions.map(suggestion => `
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h6 class="text-sm font-medium text-blue-800">${suggestion.type.replace(/_/g, ' ').toUpperCase()}</h6>
                                            <p class="text-sm text-blue-700">${suggestion.message}</p>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            html += `</div>`;
        }

        html += `</div>`;

        validationResults.innerHTML = html;
    }

    // Update stats
    function updateStats(data) {
        document.getElementById('totalErrorsCount').textContent = data.summary.errors;
        document.getElementById('totalWarningsCount').textContent = data.summary.warnings;
        document.getElementById('averageScore').textContent = data.score + '/100';

        // Update valid pages count (simplified)
        if (data.valid) {
            const currentCount = parseInt(document.getElementById('validPagesCount').textContent) || 0;
            document.getElementById('validPagesCount').textContent = currentCount + 1;
        }
    }

    // Add to recent validations
    function addToRecentValidations(data, source) {
        const recentContainer = document.getElementById('recentValidations');
        const timestamp = new Date().toLocaleString();

        const validationItem = document.createElement('div');
        validationItem.className = 'border rounded-lg p-4';
        validationItem.innerHTML = `
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium">${source}</h4>
                    <p class="text-sm text-gray-600">${timestamp}</p>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold ${data.score >= 80 ? 'text-green-600' : data.score >= 60 ? 'text-yellow-600' : 'text-red-600'}">${data.score}/100</div>
                    <div class="text-xs text-gray-500">${data.summary.errors} errors, ${data.summary.warnings} warnings</div>
                </div>
            </div>
        `;

        // Remove "no recent validations" message if it exists
        const noValidationsMsg = recentContainer.querySelector('.text-center');
        if (noValidationsMsg) {
            noValidationsMsg.remove();
        }

        recentContainer.insertBefore(validationItem, recentContainer.firstChild);

        // Keep only last 5 validations
        const items = recentContainer.children;
        if (items.length > 5) {
            recentContainer.removeChild(items[items.length - 1]);
        }
    }

    // Modal handlers
    document.getElementById('validatePageBtn').addEventListener('click', function() {
        pageValidationModal.classList.remove('hidden');
    });

    document.getElementById('closePageModal').addEventListener('click', function() {
        pageValidationModal.classList.add('hidden');
    });

    document.getElementById('batchValidateBtn').addEventListener('click', function() {
        batchValidationModal.classList.remove('hidden');
    });

    document.getElementById('closeBatchModal').addEventListener('click', function() {
        batchValidationModal.classList.add('hidden');
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === pageValidationModal) {
            pageValidationModal.classList.add('hidden');
        }
        if (event.target === batchValidationModal) {
            batchValidationModal.classList.add('hidden');
        }
    });

    // Page validation form handler
    document.getElementById('validatePageConfirm').addEventListener('click', function() {
        const url = document.getElementById('pageUrl').value.trim();

        if (!url) {
            alert('Please enter a valid URL');
            return;
        }

        const button = this;
        const originalText = button.textContent;

        // Show loading state
        button.textContent = 'Validating...';
        button.disabled = true;

        fetch('{{ route("admin.html-validator.validate-page") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ url: url })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }

            // Display results
            displayValidationResults(data);
            updateStats(data);
            addToRecentValidations(data, `Page: ${url}`);

            // Close modal
            pageValidationModal.classList.add('hidden');
            document.getElementById('pageUrl').value = '';

            // Show success message
            showNotification('Page validation completed successfully!', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error validating page: ' + error.message, 'error');
        })
        .finally(() => {
            // Reset button state
            button.textContent = originalText;
            button.disabled = false;
        });
    });

    // Batch validation form handler
    document.getElementById('batchValidateConfirm').addEventListener('click', function() {
        const urlsText = document.getElementById('batchUrls').value.trim();

        if (!urlsText) {
            alert('Please enter at least one URL');
            return;
        }

        // Parse URLs (one per line)
        const urls = urlsText.split('\n')
            .map(url => url.trim())
            .filter(url => url.length > 0);

        if (urls.length === 0) {
            alert('Please enter valid URLs');
            return;
        }

        if (urls.length > 10) {
            alert('Maximum 10 URLs allowed for batch validation');
            return;
        }

        const button = this;
        const originalText = button.textContent;

        // Show loading state
        button.textContent = 'Validating...';
        button.disabled = true;

        fetch('{{ route("admin.html-validator.batch-validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ urls: urls })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }

            // Display batch results
            displayBatchResults(data);

            // Close modal
            batchValidationModal.classList.add('hidden');
            document.getElementById('batchUrls').value = '';

            // Show success message
            showNotification(`Batch validation completed! ${data.summary.valid_urls}/${data.summary.total_urls} pages valid.`, 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error during batch validation: ' + error.message, 'error');
        })
        .finally(() => {
            // Reset button state
            button.textContent = originalText;
            button.disabled = false;
        });
    });

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }

    // Display batch validation results
    function displayBatchResults(data) {
        const validationResults = document.getElementById('validationResults');

        let html = `
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Batch Validation Results</h3>

                    <!-- Summary -->
                    <div class="grid grid-cols-4 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">${data.summary.total_urls}</div>
                            <div class="text-sm text-gray-600">Total URLs</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">${data.summary.valid_urls}</div>
                            <div class="text-sm text-gray-600">Valid</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">${data.summary.invalid_urls}</div>
                            <div class="text-sm text-gray-600">Invalid</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">${Math.round(data.summary.average_score)}/100</div>
                            <div class="text-sm text-gray-600">Avg Score</div>
                        </div>
                    </div>

                    <!-- Individual Results -->
                    <div class="space-y-4">
        `;

        data.batch_results.forEach((result, index) => {
            const validation = result.validation;
            const scoreColor = validation.score >= 80 ? 'text-green-600' : validation.score >= 60 ? 'text-yellow-600' : 'text-red-600';

            html += `
                <div class="border rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">${result.url}</h4>
                            <div class="text-sm text-gray-600">
                                ${validation.summary.errors} errors, ${validation.summary.warnings} warnings
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold ${scoreColor}">${validation.score}/100</div>
                            <div class="text-xs ${validation.valid ? 'text-green-600' : 'text-red-600'}">${validation.valid ? 'Valid' : 'Invalid'}</div>
                        </div>
                    </div>

                    ${validation.summary.errors > 0 ? `
                        <div class="mt-2">
                            <details class="text-sm">
                                <summary class="cursor-pointer text-red-600 hover:text-red-800">Show ${validation.summary.errors} errors</summary>
                                <div class="mt-2 space-y-1">
                                    ${validation.issues.errors.slice(0, 3).map(error => `
                                        <div class="text-red-700">• ${error.message}</div>
                                    `).join('')}
                                    ${validation.issues.errors.length > 3 ? `<div class="text-red-600">... and ${validation.issues.errors.length - 3} more</div>` : ''}
                                </div>
                            </details>
                        </div>
                    ` : ''}
                </div>
            `;
        });

        html += `
                    </div>
                </div>
            </div>
        `;

        validationResults.innerHTML = html;
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === pageValidationModal) {
            pageValidationModal.classList.add('hidden');
        }
        if (event.target === batchValidationModal) {
            batchValidationModal.classList.add('hidden');
        }
    });
});
</script>
@endsection
