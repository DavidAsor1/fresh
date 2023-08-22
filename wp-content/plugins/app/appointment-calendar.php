<?php
/*
Plugin Name: Appointment Calendar
Description: Allows users to schedule appointments on specific dates and times.
Version: 1.0
Author: Your Name
*/

// Plugin code goes here

// Create appointment table
// Process appointment form submission

// Get the path to the plugin directory
$plugin_dir = plugin_dir_path(__FILE__);

// Include the appointments-admin.php file
require_once $plugin_dir . 'appointments-admin.php';

function ac_create_appointment_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'appointments';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        user_name VARCHAR(255) NOT NULL,
        user_phone VARCHAR(20) NOT NULL,
        appointment_date DATE NOT NULL,
        appointment_time TIME NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
	// create_ajax_handler_file();
}
register_activation_hook(__FILE__, 'ac_create_appointment_table');


function ac_process_appointment_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'appointments';

        $name = sanitize_text_field($_POST['ac_name']);
        $phone = sanitize_text_field($_POST['ac_phone']);
        $date = sanitize_text_field($_POST['ac_date']);
        $time = sanitize_text_field($_POST['ac_time']);

        // Check if the appointment slot is available
        $existing_appointment = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE appointment_date = %s AND appointment_time = %s",
            $date,
            $time
        ));

        if ($existing_appointment) {
            // echo 'The selected appointment slot is not available.';
        } else {
            // Save the appointment in the table
            $wpdb->insert(
                $table_name,
                array(
                    'user_name' => $name,
                    'user_phone' => $phone,
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                ),
                array('%s', '%s', '%s', '%s')
            );

            // echo 'Appointment booked successfully.';
        }
    }
}
add_action('template_redirect', 'ac_process_appointment_form');

function ac_appointment_form_shortcode()
{
    ob_start();
    ?>
    <form method="post" class="appointment-form-container">
        <label for="ac_name">Your Name:</label>
        <input type="text" name="ac_name" id="ac_name" required><br><br>
        <label for="ac_phone">Your Phone:</label>
        <input type="tel" name="ac_phone" id="ac_phone" required><br><br>
        <label for="ac_date">Select a date:</label>
        <input type="date" name="ac_date" id="ac_date" required onchange="updateTimeSlots()"><br><br>
        <label for="ac_time">Select a time:</label>
        <select name="ac_time" id="ac_time" required>
        </select><br><br>
        <input type="submit" value="Book Appointment">
    </form>

    <script>
        function updateTimeSlots() {
            var selectedDate = document.getElementById('ac_date').value;

            if (selectedDate) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var timeSlots = JSON.parse(xhr.responseText);
                        var selectElement = document.getElementById('ac_time');

                        // Clear existing options
                        selectElement.innerHTML = '';

                        // Populate the select dropdown with available time slots
                        for (var i = 0; i < timeSlots.length; i++) {
                            var option = document.createElement('option');
                            option.value = timeSlots[i];
                            option.text = timeSlots[i];
                            selectElement.appendChild(option);
                        }
                    }
                };

                // AJAX request to retrieve available time slots for the selected date
                xhr.open('GET', '<?php echo admin_url('admin-ajax.php'); ?>?action=ac_get_available_time_slots&date=' + selectedDate, true);
                xhr.send();
            }
        }

        // Initial update of time slots on page load
        updateTimeSlots();
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('appointment_form', 'ac_appointment_form_shortcode');

// AJAX callback function to retrieve available time slots
function ac_get_available_time_slots()
{
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
            $hour = date('H:i:s', $time);

            // Check if the hour is already taken
            if (!in_array($hour, $existing_appointments)) {
                $time_slots[] = $hour;
            }
        }
		$convertedTimeSlots = convertTimeSlots($time_slots);
        echo json_encode($convertedTimeSlots);
    }

    wp_die();
}
add_action('wp_ajax_ac_get_available_time_slots', 'ac_get_available_time_slots');
add_action('wp_ajax_nopriv_ac_get_available_time_slots', 'ac_get_available_time_slots');

function convertTimeSlots($timeSlots)
{
    $convertedSlots = array();
    
    foreach ($timeSlots as $slot) {
        $convertedSlot = date('H:i', strtotime($slot));
        $convertedSlots[] = $convertedSlot;
    }
    
    return $convertedSlots;
}








