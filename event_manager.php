<?php
/*
Plugin Name: Event Manager
Description: A simple Event Manager plugin.
Version: 1.0
Author: Habiba
*/

if (!defined('ABSPATH')) {
    exit;
}


/* ==============================
   ADMIN MENU
   ============================== */

function event_manager_menu() {

    add_menu_page(
        'Event Manager',
        'Event Manager',
        'manage_options',
        'event-manager',
        'event_manager_page',
        'dashicons-calendar-alt',
        25
    );
}

add_action('admin_menu', 'event_manager_menu');


/* ==============================
   ADMIN PAGE
   ============================== */

function event_manager_page() {

    $events = get_option('event_manager_events', array());


    /* DELETE EVENT */

    if (isset($_POST['delete_event'])) {

        $event_index = intval($_POST['event_index']);

        if (isset($events[$event_index])) {

            unset($events[$event_index]);

            $events = array_values($events);

            update_option('event_manager_events', $events);

            echo '<div class="notice notice-success is-dismissible">
                    <p>Event deleted successfully!</p>
                  </div>';
        }
    }


    /* UPDATE EVENT */

    if (isset($_POST['update_event'])) {

        $event_index = intval($_POST['event_index']);

        if (isset($events[$event_index])) {

            $events[$event_index] = array(

                'name' => sanitize_text_field($_POST['event_name']),

                'date' => sanitize_text_field($_POST['event_date']),

                'time' => sanitize_text_field($_POST['event_time']),

                'location' => sanitize_text_field($_POST['event_location'])

            );

            update_option('event_manager_events', $events);

            echo '<div class="notice notice-success is-dismissible">
                    <p>Event updated successfully!</p>
                  </div>';
        }
    }


    /* ADD EVENT */

    if (isset($_POST['save_event'])) {

        $event_name = sanitize_text_field($_POST['event_name']);

        $event_date = sanitize_text_field($_POST['event_date']);

        $event_time = sanitize_text_field($_POST['event_time']);

        $event_location = sanitize_text_field($_POST['event_location']);


        if (!empty($event_name)) {

            $events[] = array(

                'name' => $event_name,

                'date' => $event_date,

                'time' => $event_time,

                'location' => $event_location

            );

            update_option('event_manager_events', $events);

            echo '<div class="notice notice-success is-dismissible">
                    <p>Event added successfully!</p>
                  </div>';
        }
    }


    /* EDIT FORM */

    if (isset($_GET['edit_event'])) {

        $edit_index = intval($_GET['edit_event']);

        if (isset($events[$edit_index])) {

            $event = $events[$edit_index];

            ?>

            <div class="wrap">

                <h1>Edit Event</h1>

                <form method="post">

                    <input
                        type="hidden"
                        name="event_index"
                        value="<?php echo esc_attr($edit_index); ?>"
                    >

                    <table class="form-table">

                        <tr>
                            <th>Event Name</th>
                            <td>
                                <input
                                    type="text"
                                    name="event_name"
                                    class="regular-text"
                                    value="<?php echo esc_attr($event['name']); ?>"
                                    required
                                >
                            </td>
                        </tr>

                        <tr>
                            <th>Event Date</th>
                            <td>
                                <input
                                    type="date"
                                    name="event_date"
                                    value="<?php echo esc_attr($event['date']); ?>"
                                    required
                                >
                            </td>
                        </tr>

                        <tr>
                            <th>Event Time</th>
                            <td>
                                <input
                                    type="time"
                                    name="event_time"
                                    value="<?php echo esc_attr($event['time']); ?>"
                                >
                            </td>
                        </tr>

                        <tr>
                            <th>Location</th>
                            <td>
                                <input
                                    type="text"
                                    name="event_location"
                                    class="regular-text"
                                    value="<?php echo esc_attr($event['location']); ?>"
                                >
                            </td>
                        </tr>

                    </table>

                    <p>

                        <input
                            type="submit"
                            name="update_event"
                            class="button button-primary"
                            value="Update Event"
                        >

                        <a
                            href="<?php echo esc_url(
                                admin_url('admin.php?page=event-manager')
                            ); ?>"
                            class="button"
                        >
                            Cancel
                        </a>

                    </p>

                </form>

            </div>

            <?php

            return;
        }
    }


    ?>

    <div class="wrap">

        <h1>Event Manager</h1>


        <!-- ADD EVENT -->

        <h2>Add New Event</h2>

        <form method="post">

            <table class="form-table">

                <tr>

                    <th>Event Name</th>

                    <td>

                        <input
                            type="text"
                            name="event_name"
                            class="regular-text"
                            required
                        >

                    </td>

                </tr>


                <tr>

                    <th>Event Date</th>

                    <td>

                        <input
                            type="date"
                            name="event_date"
                            required
                        >

                    </td>

                </tr>


                <tr>

                    <th>Event Time</th>

                    <td>

                        <input
                            type="time"
                            name="event_time"
                        >

                    </td>

                </tr>


                <tr>

                    <th>Location</th>

                    <td>

                        <input
                            type="text"
                            name="event_location"
                            class="regular-text"
                        >

                    </td>

                </tr>

            </table>


            <p>

                <input
                    type="submit"
                    name="save_event"
                    class="button button-primary"
                    value="Add Event"
                >

            </p>

        </form>


        <hr>


        <!-- ALL EVENTS -->

        <h2>All Events</h2>


        <?php

        if (!empty($events)) {

            ?>

            <table class="widefat striped">

                <thead>

                    <tr>

                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                foreach ($events as $key => $event) {

                    ?>

                    <tr>

                        <td>
                            <?php echo esc_html($event['name']); ?>
                        </td>

                        <td>
                            <?php echo esc_html($event['date']); ?>
                        </td>

                        <td>
                            <?php echo esc_html($event['time']); ?>
                        </td>

                        <td>
                            <?php echo esc_html($event['location']); ?>
                        </td>

                        <td>

                            <a
                                href="<?php echo esc_url(
                                    admin_url(
                                        'admin.php?page=event-manager&edit_event=' . $key
                                    )
                                ); ?>"
                                class="button button-small"
                            >
                                Edit
                            </a>


                            <form
                                method="post"
                                style="display:inline;"
                            >

                                <input
                                    type="hidden"
                                    name="event_index"
                                    value="<?php echo esc_attr($key); ?>"
                                >

                                <input
                                    type="submit"
                                    name="delete_event"
                                    class="button button-small"
                                    value="Delete"
                                >

                            </form>

                        </td>

                    </tr>

                    <?php

                }

                ?>

                </tbody>

            </table>

            <?php

        } else {

            echo '<p>No events added yet.</p>';

        }

        ?>

    </div>

    <?php
}


/* ==============================
   FRONT-END SHORTCODE
   ============================== */

function event_manager_shortcode() {

    $events = get_option('event_manager_events', array());

    ob_start();

    ?>

    <div class="event-manager-list">

        <h2>Upcoming Events</h2>

        <?php

        if (!empty($events)) {

            foreach ($events as $event) {

                ?>

                <div class="event-box">

                    <h3>
                        <?php echo esc_html($event['name']); ?>
                    </h3>

                    <p>
                        <strong>Date:</strong>
                        <?php echo esc_html($event['date']); ?>
                    </p>

                    <p>
                        <strong>Time:</strong>
                        <?php echo esc_html($event['time']); ?>
                    </p>

                    <p>
                        <strong>Location:</strong>
                        <?php echo esc_html($event['location']); ?>
                    </p>

                </div>

                <?php

            }

        } else {

            echo '<p>No events available.</p>';

        }

        ?>

    </div>

    <?php

    return ob_get_clean();
}

add_shortcode('event_manager', 'event_manager_shortcode');

/* ==============================
   EVENT STYLING
   ============================== */

function event_manager_styles() {
    ?>

    <style>

        .event-manager-list {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .event-manager-list h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }

        .event-box {
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .event-box h3 {
            margin-top: 0;
            font-size: 24px;
        }

        .event-box p {
            font-size: 16px;
            margin: 10px 0;
        }

        .event-box strong {
            margin-right: 5px;
        }

    </style>

    <?php
}

add_action('wp_head', 'event_manager_styles');