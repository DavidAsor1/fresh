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
             // Display the list of appointments
        $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
        $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';
        $appointments = array();

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

        ?>
        <h2>Appointments</h2>
        <div class="date-filter">
            <form method="get" action="<?php echo admin_url('admin.php'); ?>">
                <strong>Date Filter:</strong>
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" value="<?php echo esc_attr($start_date); ?>" required>
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" value="<?php echo esc_attr($end_date); ?>" required>
                <input type="hidden" name="page" value="appointments-admin">
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

    $start_time = strtotime('9:00 AM');
    $end_time = strtotime('5:00 PM');
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



