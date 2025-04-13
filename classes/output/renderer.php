<?php
namespace local_pluginusagereporter\output;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {
    public function render_dashboard($data) {
        return $this->render_from_template('local_pluginusagereporter/dashboard', $data);
    }
}