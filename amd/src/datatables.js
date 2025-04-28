/**
 * DataTables Initialization for Plugin Usage Reporter Dashboard.
 *
 * @module local_pluginusagereporter/datatables
 */
define(['jquery', 'core/str'], function ($, str) {
    return {
        init: function () {
            $(document).ready(function () {
                $('#reports-table').DataTable({
                    "language": {
                        "url": M.cfg.wwwroot + '/local/pluginusagereporter/datatables/lang/datatables_en.json'
                    },
                    "order": [[2, "desc"]]
                });
            });
        }
    };
});
