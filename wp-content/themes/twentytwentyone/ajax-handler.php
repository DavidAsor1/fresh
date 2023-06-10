<?php
// ajax-handler.php

// Load WordPress environment

$wp_load_path = 'D:/wamp64/www/fresh/wp-load.php';

while (!file_exists($wp_load_path) && $root_path !== dirname($root_path)) {
    $root_path = dirname($root_path);
    $wp_load_path = $root_path . '/wp-load.php';
}

if (file_exists($wp_load_path)) {
    require_once($wp_load_path);

    // Fetch existing appointments for the selected date
    if (isset($_GET['date'])) {
        $selected_date = sanitize_text_field($_GET['date']);
        $selected_date_formatted = date('Y-m-d', strtotime($selected_date));

        // Fetch existing appointments for the selected date
        global $wpdb;
        $table_name = $wpdb->prefix . 'appointments';
        $existing_appointments = $wpdb->get_col($wpdb->prepare(
            "SELECT appointment_time FROM $table_name WHERE appointment_date = %s",
            $selected_date_formatted
        ));

        // Generate time slots for the day
        $time_slots = array();
        $start_time = strtotime('9:00 AM');
        $end_time = strtotime('5:00 PM');
        $interval = 30 * 60; // 30 minutes in seconds

        for ($time = $start_time; $time <= $end_time; $time += $interval) {
            $hour = date('g:i A', $time);

            // Check if the hour is already taken
            if (!in_array($hour, $existing_appointments)) {
                $time_slots[] = $hour;
            }
        }

        echo json_encode($time_slots);
    }
} else {
    echo 'Failed to load the WordPress environment.';
}
