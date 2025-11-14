<?php
        session_start();
        if (!isset($_SESSION['text'])) {
            $_SESSION['booked'] = [];
        }
        if (!isset($_SESSION['waiting'])) {
            $_SESSION['waiting'] = [];
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $source = $_POST['source'];
            $dest = $_POST['dest'];
            $category = $_POST['category'];
            $reservation = "$name, $source, $dest";
            if ($category === "book") {
                if (count($_SESSION['booked']) < 6) {
                    $_SESSION['booked'][] = $reservation;
                } else {
                    $_SESSION['waiting'][] = $reservation;
                }
            } elseif ($category === "cancel") {
                $key = array_search($reservation, $_SESSION['booked']);
                if ($key !== false) {
                    unset($_SESSION['booked'][$key]);
                    $_SESSION['booked'] = array_values($_SESSION['booked']);

                    if (count($_SESSION['waiting']) > 0) {
                        $first_waiting = array_shift($_SESSION['waiting']);
                        $_SESSION['booked'][] = $first_waiting;
                    }
                }
            }
            $total_reservations = count($_SESSION['booked']) + count($_SESSION['waiting']);
            if ($total_reservations % 6 === 0) {
                echo "Success <br>";
            }
            echo "Booked: " . count($_SESSION['booked']) . "<br>";
            echo "Waiting: " . count($_SESSION['waiting']) . "<br>";
            echo "Booked Passengers: <br>";
            foreach ($_SESSION['booked'] as $booked) {
                echo $booked . "<br>";
            }
            echo "Waiting List: <br>";
            foreach ($_SESSION['waiting'] as $waiting) {
                echo $waiting . "<br>";
            }
        }
    ?>



