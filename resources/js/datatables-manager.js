// DataTables Manager - A global manager for DataTables initialization
window.DataTablesManager = (function() {
    // Track initialized tables to prevent double initialization
    const initializedTables = new Set();

    // Store DataTable instances by their ID for easy access
    const tableInstances = {};

    // Default configuration with Indonesian language
    const defaultConfig = {
        responsive: true,
        columnDefs: [{
            responsivePriority: 1,
            targets: [0, 1, -1] // Priority on first, second and last column
        }, {
            orderable: false,
            targets: [-1] // Last column (actions) not sortable
        }],
        language: {
            "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
            "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
            "lengthMenu": "Tampilkan _MENU_ entri",
            "loadingRecords": "Sedang memuat...",
            "processing": "Sedang memproses...",
            "search": "Cari:",
            "zeroRecords": "Tidak ditemukan data yang sesuai",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            },
            "aria": {
                "sortAscending": ": aktifkan untuk mengurutkan kolom ke atas",
                "sortDescending": ": aktifkan untuk mengurutkan kolom ke bawah"
            }
        },
        // Default draw callback to reinitialize tooltips
        drawCallback: function() {
            // Reinitialize tooltips on draw
            if (window.bootstrap && typeof bootstrap.Tooltip !== 'undefined') {
                const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                [...tooltips].forEach(el => {
                    // Destroy existing tooltip if any
                    const tooltip = bootstrap.Tooltip.getInstance(el);
                    if (tooltip) {
                        tooltip.dispose();
                    }
                    // Create new tooltip
                    new bootstrap.Tooltip(el);
                });
            }
        }
    };

    // Initialize DataTable with custom config
    function initializeTable(tableSelector, customConfig = {}) {
        const $ = window.jQuery || window.$;

        if (!$) {
            console.error("jQuery is not loaded, DataTables cannot be initialized");
            return null;
        }

        if (typeof $.fn.DataTable === 'undefined') {
            console.error("DataTables is not loaded");
            return null;
        }

        // Get the table element
        const $table = $(tableSelector);

        if ($table.length === 0) {
            console.warn(`Table not found: ${tableSelector}`);
            return null;
        }

        // Get table ID (or generate one if not present)
        const tableId = $table.attr('id') || `datatable-${Math.random().toString(36).substring(2, 9)}`;

        // Check if this table has already been initialized
        if (initializedTables.has(tableId)) {
            console.log(`Table already initialized: ${tableId}`);
            return tableInstances[tableId];
        }

        // Parse data attributes from the table element
        const dataConfig = parseDataAttributes($table);

        // Merge configs with priority: default < data attributes < custom config
        const finalConfig = {
            ...defaultConfig,
            ...dataConfig,
            ...customConfig
        };

        // Initialize the DataTable
        console.log(`Initializing DataTable: ${tableId}`);
        try {
            const instance = $table.DataTable(finalConfig);

            // Register this table as initialized
            initializedTables.add(tableId);
            tableInstances[tableId] = instance;

            // Add the table ID to the element if it doesn't have one
            if (!$table.attr('id')) {
                $table.attr('id', tableId);
            }

            return instance;
        } catch (error) {
            console.error(`Error initializing DataTable ${tableId}:`, error);
            return null;
        }
    }

    // Parse data attributes into a configuration object
    function parseDataAttributes($table) {
        const config = {};

        // Page length
        if ($table.data('page-length') !== undefined) {
            config.pageLength = parseInt($table.data('page-length'));
        }

        // Order
        if ($table.data('order') !== undefined) {
            try {
                config.order = JSON.parse($table.data('order'));
            } catch (e) {
                console.warn(`Invalid order data attribute: ${$table.data('order')}`);
            }
        }

        // Priority columns
        if ($table.data('priority-columns') !== undefined) {
            try {
                const priorityColumns = JSON.parse($table.data('priority-columns'));
                config.columnDefs = config.columnDefs || [];

                // Find and update the responsivePriority definition or add a new one
                let found = false;
                for (let i = 0; i < config.columnDefs.length; i++) {
                    if (config.columnDefs[i].responsivePriority !== undefined) {
                        config.columnDefs[i].targets = priorityColumns;
                        found = true;
                        break;
                    }
                }

                if (!found) {
                    config.columnDefs.push({
                        responsivePriority: 1,
                        targets: priorityColumns
                    });
                }
            } catch (e) {
                console.warn(`Invalid priority-columns data attribute: ${$table.data('priority-columns')}`);
            }
        }

        // Non-sortable columns
        if ($table.data('non-sortable-columns') !== undefined) {
            try {
                const nonSortableColumns = JSON.parse($table.data('non-sortable-columns'));
                config.columnDefs = config.columnDefs || [];

                // Find and update the orderable definition or add a new one
                let found = false;
                for (let i = 0; i < config.columnDefs.length; i++) {
                    if (config.columnDefs[i].orderable === false) {
                        config.columnDefs[i].targets = nonSortableColumns;
                        found = true;
                        break;
                    }
                }

                if (!found) {
                    config.columnDefs.push({
                        orderable: false,
                        targets: nonSortableColumns
                    });
                }
            } catch (e) {
                console.warn(`Invalid non-sortable-columns data attribute: ${$table.data('non-sortable-columns')}`);
            }
        }

        // Default order
        if ($table.data('default-order-column') !== undefined && $table.data('default-order-dir') !== undefined) {
            const column = parseInt($table.data('default-order-column'));
            const dir = $table.data('default-order-dir');
            if (!isNaN(column) && (dir === 'asc' || dir === 'desc')) {
                config.order = [[column, dir]];
            }
        }

        return config;
    }

    // Initialize all data-tables found on the page
    function initializeAll() {
        const $ = window.jQuery || window.$;

        if (!$) {
            console.error("jQuery is not loaded, DataTables cannot be initialized");
            return;
        }

        if (typeof $.fn.DataTable === 'undefined') {
            console.error("DataTables is not loaded");
            return;
        }

        $('.data-table').each(function() {
            const $table = $(this);
            const tableId = $table.attr('id') || '';

            // Skip if already initialized
            if (initializedTables.has(tableId) && tableId !== '') {
                console.log(`Skipping already initialized table: ${tableId}`);
                return;
            }

            // Initialize this table
            initializeTable('#' + tableId);
        });
    }

    // Check if a table with the given selector or ID has been initialized
    function isInitialized(tableSelector) {
        const $ = window.jQuery || window.$;

        if (!$) return false;

        const $table = $(tableSelector);
        if ($table.length === 0) return false;

        const tableId = $table.attr('id');
        return tableId && initializedTables.has(tableId);
    }

    // Get a DataTable instance by selector or ID
    function getInstance(tableSelector) {
        const $ = window.jQuery || window.$;

        if (!$) return null;

        const $table = $(tableSelector);
        if ($table.length === 0) return null;

        const tableId = $table.attr('id');
        return tableId ? tableInstances[tableId] : null;
    }

    // Destroy a DataTable instance
    function destroyTable(tableSelector) {
        const $ = window.jQuery || window.$;

        if (!$) return false;

        const $table = $(tableSelector);
        if ($table.length === 0) return false;

        const tableId = $table.attr('id');
        if (!tableId || !initializedTables.has(tableId)) return false;

        try {
            // Destroy the DataTable
            tableInstances[tableId].destroy();

            // Remove from our tracking
            initializedTables.delete(tableId);
            delete tableInstances[tableId];

            return true;
        } catch (error) {
            console.error(`Error destroying DataTable ${tableId}:`, error);
            return false;
        }
    }

    // Public API
    return {
        initialize: initializeTable,
        initializeAll: initializeAll,
        isInitialized: isInitialized,
        getInstance: getInstance,
        destroy: destroyTable
    };
})();