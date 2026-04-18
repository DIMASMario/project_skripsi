<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">View Log</h1>
            <p class="text-sm text-gray-500 mt-1"><?= esc($filename) ?> (<?= number_format($totalLines) ?> lines)</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('writable/logs/' . $filename) ?>" 
               download
               class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="material-icons text-sm mr-2">download</i>
                Download
            </a>
            <a href="<?= base_url('admin/logs') ?>" 
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <i class="material-icons text-sm mr-2">arrow_back</i>
                Back
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" 
                       id="search-input" 
                       placeholder="Search in log..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <select id="level-filter" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Levels</option>
                <option value="CRITICAL">CRITICAL</option>
                <option value="ERROR">ERROR</option>
                <option value="WARNING">WARNING</option>
                <option value="INFO">INFO</option>
                <option value="DEBUG">DEBUG</option>
            </select>
            <button onclick="clearFilter()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                Clear
            </button>
        </div>
    </div>

    <!-- Log Content -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-medium text-gray-900">Log Content (Last 1000 lines)</h2>
        </div>
        <div class="p-4 bg-gray-900 overflow-x-auto">
            <pre id="log-content" class="text-xs text-gray-100 font-mono whitespace-pre-wrap"><?= esc($content) ?></pre>
        </div>
    </div>
</div>

<script>
const logContent = document.getElementById('log-content');
const originalContent = logContent.textContent;

document.getElementById('search-input').addEventListener('input', function(e) {
    filterLogs();
});

document.getElementById('level-filter').addEventListener('change', function(e) {
    filterLogs();
});

function filterLogs() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const levelFilter = document.getElementById('level-filter').value;
    
    let lines = originalContent.split('\n');
    let filteredLines = lines;
    
    // Filter by search term
    if (searchTerm) {
        filteredLines = filteredLines.filter(line => 
            line.toLowerCase().includes(searchTerm)
        );
    }
    
    // Filter by level
    if (levelFilter) {
        filteredLines = filteredLines.filter(line => 
            line.includes(levelFilter)
        );
    }
    
    logContent.textContent = filteredLines.join('\n');
    
    // Highlight search term
    if (searchTerm) {
        highlightText(searchTerm);
    }
}

function highlightText(searchTerm) {
    const content = logContent.textContent;
    const regex = new RegExp(searchTerm, 'gi');
    const highlighted = content.replace(regex, match => `<mark class="bg-yellow-300 text-black">${match}</mark>`);
    logContent.innerHTML = highlighted;
}

function clearFilter() {
    document.getElementById('search-input').value = '';
    document.getElementById('level-filter').value = '';
    logContent.textContent = originalContent;
}

// Color coding for log levels
document.addEventListener('DOMContentLoaded', function() {
    const content = logContent.innerHTML;
    let coloredContent = content
        .replace(/CRITICAL/g, '<span class="text-red-500 font-bold">CRITICAL</span>')
        .replace(/ERROR/g, '<span class="text-red-400 font-bold">ERROR</span>')
        .replace(/WARNING/g, '<span class="text-yellow-400 font-bold">WARNING</span>')
        .replace(/INFO/g, '<span class="text-blue-400">INFO</span>')
        .replace(/DEBUG/g, '<span class="text-gray-400">DEBUG</span>');
    
    logContent.innerHTML = coloredContent;
});
</script>

<?= $this->endsection() ?>
