<?php



// Add the Appointments Admin page
function ac_appointments_admin_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'appointments';

    // Check if an appointment ID is specified for editing
    if (isset($_GET['edit'])) {
		
        $appointment_id = absint($_GET['edit']);

        // Retrieve the appointment data
        $appointment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $appointment_id));

        if ($appointment) {
            // Display the appointment edit form
            ?>
            <h2>Edit Appointment</h2>
            <form method="post" action="<?php echo admin_url('admin.php?page=appointments-admin&edit=' . $appointment->id); ?>">
                <label for="ac_user_name">User Name:</label>
                <input type="text" name="ac_user_name" id="ac_user_name" value="<?php echo esc_attr($appointment->user_name); ?>" required><br><br>
                <label for="ac_user_phone">Phone:</label>
                <input type="text" name="ac_user_phone" id="ac_user_phone" value="<?php echo esc_attr($appointment->user_phone); ?>" required><br><br>
                <label for="ac_date">Appointment Date:</label>
                <input type="date" name="ac_date" id="ac_date" value="<?php echo esc_attr($appointment->appointment_date); ?>" required onchange="updateTimeSlots()"><br><br>
                <label for="ac_time">Appointment Time:</label>
                <?php
                $time_slots = ac_get_available_time_slotss($appointment->appointment_date, $appointment->appointment_time);
                ?>
                <select name="ac_time" id="ac_time" required>
                    <?php foreach ($time_slots as $slot) : ?>
                        <option value="<?php echo esc_attr($slot); ?>" <?php selected($appointment->appointment_time, $slot); ?>><?php echo esc_html(date('H:i', strtotime($slot))); ?></option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="hidden" name="ac_appointment_id" value="<?php echo $appointment->id; ?>">
                <button type="submit" class="button button-primary" name="
				">Update</button>
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
        // updateTimeSlots();
    </script>
            <?php
        } else {
            echo '<p>Appointment not found.</p>';
        }
    } else {
             
        $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
        $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';
        $appointments = array();
        
        // Calculate date ranges for filters
        if (isset($_GET['filter'])) {
            $filter = sanitize_text_field($_GET['filter']);
            switch ($filter) {
                case 'all':
                    $start_date = '';
                    $end_date = '';
                   break;
                case 'today':
                    $start_date = $end_date = date('Y-m-d');
                    break;
                case 'tomorrow':
                    $start_date = $end_date = date('Y-m-d', strtotime('+1 day'));
                    break;
                case 'this_week':
                    $start_date = date('Y-m-d', strtotime('this week'));
                    $end_date = date('Y-m-d', strtotime('this week +6 days'));
                    break;
                case 'next_week':
                    $start_date = date('Y-m-d', strtotime('next week'));
                    $end_date = date('Y-m-d', strtotime('next week +6 days'));
                    break;
                case 'last_month':
                    $start_date = date('Y-m-d', strtotime('first day of last month'));
                    $end_date = date('Y-m-d', strtotime('last day of last month'));
                    break;
                case 'next_month':
                    $start_date = date('Y-m-d', strtotime('first day of next month'));
                    $end_date = date('Y-m-d', strtotime('last day of next month'));
                    break;
            }
        } else {
            // Default: Show today and future appointments
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime('+1 year')); // Change this range as needed
        }
        
        // Check if the start and end dates are specified
        if (!empty($start_date) && !empty($end_date)) {
            $appointments = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table_name WHERE appointment_date BETWEEN %s AND %s ORDER BY appointment_date ASC, appointment_time ASC",
                $start_date,
                $end_date
            ));
        } else {
            // Fetch all appointments
            $appointments = $wpdb->get_results("SELECT * FROM $table_name ORDER BY appointment_date ASC, appointment_time ASC");
        }
        
        // Get the counts for each filter
        $filter_counts = array(
            'all' => array(
                'count' => count($wpdb->get_results("SELECT * FROM $table_name")),
                'display_name' => 'הכל'
            ),
            'today' => array(
                'count' => count($wpdb->get_results("SELECT * FROM $table_name WHERE appointment_date = CURDATE()")),
                'display_name' => 'היום'
            ),
            'tomorrow' => array(
                'count' => count($wpdb->get_results("SELECT * FROM $table_name WHERE appointment_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)")),
                'display_name' => 'מחר'
            ),
            'this_week' => array(
                'count' => count($wpdb->get_results("SELECT * FROM $table_name WHERE WEEK(appointment_date) = WEEK(CURDATE())")),
                'display_name' => 'השבוע'
            ),
            'next_week' => array(
                'count' => count($wpdb->get_results("SELECT * FROM $table_name WHERE WEEK(appointment_date) = WEEK(DATE_ADD(CURDATE(), INTERVAL 1 WEEK))")),
                'display_name' => 'השבוע הבא'
            ),
            /* 'last_month' => array(
                'count' => count($wpdb->get_results("SELECT * FROM $table_name WHERE MONTH(appointment_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))")),
                'display_name' => 'החודש הקודם'
            ), */ 
            'next_month' => array(
                '' => count($wpdb->get_results("SELECT * FROM $table_name WHERE MONTH(appointment_date) = MONTH(DATE_ADD(CURDATE(), INTERVAL 1 MONTH))")),
                'display_name' => 'החודש הבא'
            )
        );
        
        ?>
        <h2>Appointments</h2>
        <div class="date-filter">
            <form method="get" action="<?php echo admin_url('admin.php'); ?>">
                <strong>Date Filter:</strong>
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" value="<?php echo esc_attr($start_date); ?>">
                <label for="end_date">End Date:</label>count
                <input type="date" name="end_date" id="end_date" value="<?php echo esc_attr($end_date); ?>">
                <input type="hidden" name="page" value="appointments-admin">
                <select name="filter">
                    <?php foreach ($filter_counts as $filter_option => $count): ?>
                        <option value="<?=$filter_option?>"><?=$count['display_name']?> (<?= $count['count']?>)</option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Filter">
            </form>
        </div>
        

             
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>User Name</th>
                    <th>Phone</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment) : ?>
                    <tr>
                        <td><?php echo $appointment->id; ?></td>
                        <td><?php echo esc_html($appointment->user_name); ?></td>
                        <td><?php echo esc_html($appointment->user_phone); ?></td>
                        <td><?php echo esc_html($appointment->appointment_date); ?></td>
                        <td><?php echo esc_html(date('H:i', strtotime($appointment->appointment_time))); ?></td>
                        <td>
                            <a class="admin-button" href="<?php echo admin_url('admin.php?page=appointments-admin&edit=' . $appointment->id); ?>">Edit</a>
                            <a class="admin-button" href="<?php echo admin_url('admin.php?page=appointments-admin&delete=' . $appointment->id); ?>" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
		<style>
			.admin-button {
			  display: inline-block;
			  padding: 10px 20px;
			  background-color: #0073aa;
			  color: #fff;
			  text-decoration: none;
			  font-weight: bold;
			  border-radius: 4px;
			  transition: background-color 0.3s ease;
			}

			.admin-button:hover {
			  background-color: #005a87;
			  color:white;
			}
		</style>
        <?php
    }
}


// Handle Appointment Update
function ac_handle_appointment_update()
{
    if (isset($_POST['ac_update_appointment'])) {
        $appointment_id = absint($_POST['ac_appointment_id']);
        $user_name = sanitize_text_field($_POST['ac_user_name']);
        $user_phone = sanitize_text_field($_POST['ac_user_phone']);
        $appointment_date = sanitize_text_field($_POST['ac_date']);
        $appointment_time = sanitize_text_field($_POST['ac_time']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'appointments';

        // Update the appointment in the database
        $wpdb->update(
            $table_name,
            array(
                'user_name' => $user_name,
                'user_phone' => $user_phone,
                'appointment_date' => $appointment_date,
                'appointment_time' => $appointment_time,
            ),
            array('id' => $appointment_id)
        );

        // Redirect to the appointments admin page after update
        wp_safe_redirect(admin_url('admin.php?page=appointments-admin'));
        exit;
    }
}
add_action('admin_init', 'ac_handle_appointment_update');




// Register the Appointments Admin page
function ac_add_appointments_admin_menu()
{
       add_menu_page(
        'Appointments',
        'Appointments',
        'manage_options', // Set the necessary capability here
        'appointments-admin',
        'ac_appointments_admin_page',
        'dashicons-calendar'
    );
}

// Hook the function to add the menu and page
add_action('admin_menu', 'ac_add_appointments_admin_menu');

// Handle Appointment Deletion
function ac_handle_appointment_deletion()
{
	// send_sms	();
    if (isset($_GET['delete'])) {
        $appointment_id = absint($_GET['delete']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'appointments';

        // Delete the appointment from the database
        $wpdb->delete($table_name, array('id' => $appointment_id));

        // Redirect to the appointments admin page after deletion
        wp_safe_redirect(admin_url('admin.php?page=appointments-admin')); 
        exit;
    }
}
add_action('admin_init', 'ac_handle_appointment_deletion');

// Retrieve Available Time Slots for a Date
function ac_get_available_time_slotss($selected_date, $selected_time = '')
{
    $selected_date_formatted = date('Y-m-d', strtotime($selected_date));

    global $wpdb;
    $table_name = $wpdb->prefix . 'appointments';
    $existing_appointments = $wpdb->get_col($wpdb->prepare(
        "SELECT appointment_time FROM $table_name WHERE appointment_date = %s",
        $selected_date_formatted
    ));

    $start_time = strtotime('1:00 AM');
    $end_time = strtotime('11:00 PM');
    $interval = 30 * 60; // 30 minutes in seconds

    $time_slots = array();

    for ($time = $start_time; $time <= $end_time; $time += $interval) {
        $hour = date('H:i:s', $time);

        if (!in_array($hour, $existing_appointments)) {
            $time_slots[] = $hour;
        }
    }

    $convertedTimeSlots = convertTimeSlots($time_slots);

    // If a selected time is provided, ensure it's included in the time slots
    if (!empty($selected_time) && !in_array($selected_time, $convertedTimeSlots)) {
        $convertedTimeSlots[] = $selected_time;
    }

    return $convertedTimeSlots;
}

function send_sms(){
	
	$url = "https://api.sms4free.co.il/apisms/sendsms";
	$key = "WTknGyzKb";
	$user = "0524256976";
	$pass = "41029823";
	$sender = "0524256976";
	$recipient = "0586003207"; // Numbers must be seperated with ;
	$msg = "test"; # can be anything

	$postdata = array(
		'key' => $key,
		'user' => $user,
		'pass' => $pass,
		'sender' => $sender,
		'recipient' => $recipient,
		'msg' => $msg
	);
	$data_string = json_encode($postdata);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //disable in production
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 400);
	set_time_limit(0);

	$data = curl_exec($ch);
	print_r($data);
	$curl_errno = curl_errno($ch);
	$curl_error = curl_error($ch);
	curl_close($ch);

	if ($curl_errno > 0) {
		echo "CURL Error ($curl_errno): $curl_error\n";
	} else {
		echo "Data received: $data\n"; // Gives you how many recipients the message was sent to
	}

}

//--------------------------------------------------------------------------------------------------

// Add a menu item under the "Settings" menu
// Add a submenu item under the "Appointments" menu
function add_hour_settings_submenu() {
    add_submenu_page('edit.php?post_type=appointment', 'Hour Settings', 'Hour Settings', 'manage_options', 'hour-settings', 'hour_settings_page');
}
add_action('admin_menu', 'add_hour_settings_submenu');

// Callback function for the hour settings page
function hour_settings_page() {
    // Check if the current user has the required capability to access the page
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    ?>
    <div class="wrap">
        <h1>Hour Settings</h1>
        <form method="post" action="options.php">
            <?php
            // Output nonce, action, and option fields
            settings_fields('hour_settings');
            do_settings_sections('hour_settings');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Start Hour</th>
                    <td>
                        <select name="start_hour">
                            <?php
                            // Generate options for start hour from 9 to 17
                            for ($hour = 9; $hour <= 17; $hour++) {
                                printf('<option value="%s" %s>%s</option>', $hour, selected(get_option('start_hour'), $hour, false), $hour);
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">End Hour</th>
                    <td>
                        <select name="end_hour">
                            <?php
                            // Generate options for end hour from 9 to 17
                            for ($hour = 9; $hour <= 17; $hour++) {
                                printf('<option value="%s" %s>%s</option>', $hour, selected(get_option('end_hour'), $hour, false), $hour);
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php
            // Output the submit button
            submit_button('Save Changes');
            ?>
        </form>
    </div>
    <?php
}


// Register the settings
function register_hour_settings() {
    // Add settings fields
    add_settings_section('hour_settings_section', '', '', 'hour_settings');
    add_settings_field('start_hour', 'Start Hour', 'hour_settings_start_hour_callback', 'hour_settings', 'hour_settings_section');
    add_settings_field('end_hour', 'End Hour', 'hour_settings_end_hour_callback', 'hour_settings', 'hour_settings_section');

    // Register settings
    register_setting('hour_settings', 'start_hour', 'intval');
    register_setting('hour_settings', 'end_hour', 'intval');
}
add_action('admin_init', 'register_hour_settings');

// Callback function for the start hour field
function hour_settings_start_hour_callback() {
    echo '<select name="start_hour">';
    for ($hour = 9; $hour <= 17; $hour++) {
        printf('<option value="%s" %s>%s</option>', $hour, selected(get_option('start_hour'), $hour, false), $hour);
    }
    echo '</select>';
}

// Callback function for the end hour field 
function hour_settings_end_hour_callback() {
    echo '<select name="end_hour">';
    for ($hour = 9; $hour <= 17; $hour++) {
        printf('<option value="%s" %s>%s</option>', $hour, selected(get_option('end_hour'), $hour, false), $hour);
    }
    echo '</select>';
}


function calendar_settings_page() {
    add_menu_page(
        'Calendar Settings',
        'Calendar Settings',
        'manage_options',
        'calendar-settings',
        'render_calendar_settings_page'
    );
}
add_action('admin_menu', 'calendar_settings_page');

function render_calendar_settings_page() {
    ?>
    <div class="wrap">
        <h2>Calendar Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('calendar_settings');
            do_settings_sections('calendar-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function calendar_settings_init() {
    add_settings_section(
        'calendar_section',
        'Calendar Settings',
        'calendar_section_callback',
        'calendar-settings'
    ); 

    $days_of_week = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
    foreach ($days_of_week as $day) {
        add_settings_field(
            'calendar_' . $day,
            ucfirst($day),
            'calendar_day_callback',
            'calendar-settings',
            'calendar_section',
            array('day' => $day)
        );
    }

    // Add a new settings field for interval
    add_settings_field(
        'calendar_interval',
        'Interval',
        'calendar_interval_callback',
        'calendar-settings',
        'calendar_section'
    );

    // Add a new settings field for selecting days of the week
    add_settings_field(
        'calendar_selected_days',
        'Selected Days of the Week',
        'calendar_selected_days_callback',
        'calendar-settings',
        'calendar_section'
    );

    register_setting('calendar_settings', 'calendar_days');
    register_setting('calendar_settings', 'calendar_interval');
    register_setting('calendar_settings', 'calendar_selected_days'); // Register the selected days setting
}
add_action('admin_init', 'calendar_settings_init');

function calendar_section_callback() {
    echo 'Manage your calendar settings here.';
}

function enqueue_select2_scripts() {
    
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js', array(), '3.6.4', true);
    // Enqueue Select2 scripts and styles
    wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js', array('jquery'), '4.1.0', true);
    wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css');

    // Enqueue jQuery and jQuery UI
    wp_enqueue_script('jquery-ui-core', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js', array('jquery'), '1.11.1', true);
    wp_enqueue_script('jquery-ui-datepicker', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js', array('jquery'), '1.11.1', true);

    // Enqueue jQuery UI stylesheet
    wp_enqueue_style('jquery-ui-datepicker-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
}
add_action('admin_enqueue_scripts', 'enqueue_select2_scripts');


function calendar_day_callback($args) {
    $day = $args['day'];
    $calendar_days = get_option('calendar_days', array());

    $start_time = isset($calendar_days[$day]['start_time']) ? $calendar_days[$day]['start_time'] : '';
    $end_time = isset($calendar_days[$day]['end_time']) ? $calendar_days[$day]['end_time'] : '';

    echo ' תור ראשון - <input type="time" name="calendar_days[' . $day . '][start_time]" value="' . esc_attr($start_time) . '"> - ';
    echo 'תור אחרון - <input type="time" name="calendar_days[' . $day . '][end_time]" value="' . esc_attr($end_time) . '">';
}

function calendar_interval_callback() {
    $calendar_interval = get_option('calendar_interval', '');

    echo '<input type="number" name="calendar_interval" value="' . esc_attr($calendar_interval) . '" min="1" step="1">';
}

function calendar_selected_days_callback() {
    $calendar_selected_days = get_option('calendar_selected_days', array());

    $days_of_week = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');

    echo '<select name="calendar_selected_days[]" class="select2" multiple>';
    foreach ($days_of_week as $day) {
        $selected = in_array($day, $calendar_selected_days) ? 'selected' : '';
        echo '<option value="' . esc_attr($day) . '" ' . $selected . '>' . ucfirst($day) . '</option>';
    }
    echo '</select>';
    echo '<script>
            jQuery(document).ready(function($) {
               
                $("select[name=\'calendar_selected_days[]\']").select2();
            });
        </script>';
}

