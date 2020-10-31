<?php

namespace Miniature;

use DateTime;
use PDO;
use Miniature\Database as DB;
use Miniature\Holiday;

class Calendar {

    protected $date = \NULL;
    protected $page = 0;
    public $output = \NULL;
    protected $username = \NULL;
    protected $user_id = \NULL;
    protected $query = \NULL;
    protected $stmt = \NULL;
    protected $urlDate = \NULL;
    protected $sendDate = \NULL;
    protected $prev = \NULL;
    public $current = \NULL;
    protected $next = \NULL;
    protected $month = \NULL;
    protected $day = \NULL;
    protected $year = \NULL;
    protected $days = \NULL;
    protected $currentDay = \NULL;
    protected $highlightToday = \NULL;
    protected $highlightHoliday = \NULL;
    protected $isHoliday = \NULL;
    protected $prevMonth = \NULL;
    protected $nextMonth = \NULL;
    public $selectedMonth = \NULL;
    public $n = \NULL;
    public $index = 0;
    public $result = \NULL;
    public $tab = "\t"; // Tab 2 spaces over;
    public $calendar = []; // The HTML Calender:
    protected $holiday = \NULL;
    protected $alphaDay = [0 => "Sun", 1 => "Mon", 2 => "Tue", 3 => "Wed", 4 => "Thu", 5 => "Fri", 6 => "Sat"];
    protected $smallDays = [0 => "S", 1 => "M", 2 => "T", 3 => "W", 4 => "T", 5 => "F", 6 => "S"];
    protected $imporantDates = [];
    protected $myPage = \NULL;
    protected $now = \NULL;
    protected $monthlyChange = \NULL;
    protected $pageName = "index";

    static protected function pdo() {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        return $pdo;
    }

    /* Constructor to create the calendar */

    public function __construct($date = null) {
        $this->selectedMonth = new \DateTime($date, new \DateTimeZone("America/Detroit"));
        $this->current = new \DateTime($date, new \DateTimeZone("America/Detroit"));
        $this->current->modify("first day of this month");
        $this->n = $this->current->format("n"); // Current Month as a number (1-12):
    }

    public function set_user_id($user_id = -1) {
        $this->user_id = $user_id;
    }

    public function checkIsAValidDate($myDateString) {
        return (bool) strtotime($myDateString);
    }

    public function phpDate() {
        $setDate = filter_input(INPUT_GET, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $valid = $this->checkIsAValidDate($setDate);
        if (isset($setDate) && strlen($setDate) === 10 && $valid) {
            self::__construct($setDate);
        }
    }

    public function returnDate() {
        return $this->selectedMonth;
    }

    public function getHolidayNames() {
        return $this->isHoliday->checkForHoliday($this->selectedMonth->format('Y-m-j'));
    }

    protected function isItToday() {
        /*
         * If selected month (user) equals today's date then highlight the day, if
         * not then treat it as a normal day to be displayed.
         */
        if ($this->now->format("F j, Y") === $this->current->format("F j, Y")) {
            $this->calendar[$this->index]['class'] = 'item today';
            $this->calendar[$this->index]['date'] = $this->current->format("j");
        } else {
            $this->todaysSquares(); // Check to See if it is a Holiday:
        }
    }

    protected function todaysSquares() {
        
        $result = $this->checkForEntry($this->current->format("Y-m-d"));
        
        if ($result) {
            $bold = " entryBold";
        } else {
            $bold = null;
        }
        
        /*
         * Determine if just a regular day or if it's a holiday.
         */
        if (array_key_exists($this->current->format("Y-m-d"), $this->holiday)) {
            $this->calendar[$this->index]['class'] = 'item holiday' . $bold;
            $this->calendar[$this->index]['date'] = $this->current->format("j");
        } else { // Just a Normal day
            $this->calendar[$this->index]['class'] = 'item date' . $bold;
            $this->calendar[$this->index]['date'] = $this->current->format("j");
        }
    }

    protected function checkForEntry($calDate, $page = 'index.php') {
        
        $this->username = isset($_SESSION['user']) ? $_SESSION['user']->username : \NULL;

        $this->query = 'SELECT 1 FROM cms WHERE page=:page AND DATE_FORMAT(date_added, "%Y-%m-%d")=:date_added';

        $this->stmt = static::pdo()->prepare($this->query);

        $this->stmt->execute([':page' => $page, ':date_added' => $calDate]);

        $this->result = $this->stmt->fetch();

        /* If result is true there is data in day, otherwise no data */
        if ($this->result) {
            return \TRUE;
        } else {
            return \FALSE;
        }
    }

    protected function drawDays() {

        $this->now = new \DateTime("Now", new \DateTimeZone("America/Detroit"));
        $x = 1;
        while ($x <= 7) {
            /*
             * Determine if selected month (user) equals current month to be
             * displayed. If it is proceed with check dates, if not the fade 
             * the box (using HTML classes) ,so that the user will know that 
             * it is not the month currently being displayed.
             */
            if ($this->selectedMonth->format('n') === $this->current->format('n')) {
                $this->isItToday(); // Check for Today & Holidays:
            } else {
                /*
                 * Fade out previous and next month's dates
                 * (note prev-date class is both previous & next month dates)
                 * (Me Bad)
                 */
                $this->calendar[$this->index]['class'] = 'item prev-date';
                $this->calendar[$this->index]['date'] = $this->current->format("j");
            }

            $this->current->modify("+1 day");
            $x += 1;
            $this->index += 1;
        }
    }

    protected function controls() {
        /* Grab Current Month to be Displayed */
        $this->monthlyChange = new DateTime($this->current->format("F j, Y"));
        /* Figure Out Previous Month for Previous Button */
        $this->monthlyChange->modify("-1 month");
        /* Assign Previous Month to a Variable */
        $this->prev = $this->monthlyChange->format("Y-m-d");
        /* Figure Out Next Month for Next Button */
        $this->monthlyChange->modify("+2 month");
        /* Assign Next Month to a Vairiable */
        $this->next = $this->monthlyChange->format("Y-m-d");

        /* Create Previous / Next Buttons for the Calendar */
        $this->calendar[$this->index]['previous'] = $this->pageName . '?location=' . $this->prev;
        $this->calendar[$this->index]['next'] = $this->pageName . '?location=' . $this->next;
    }

    protected function display() {
        /* Grab Holiday from Holiday Class (if there is one) */
        $holidayCheck = new Holiday($this->current->format("F j, Y"), 1);
        /* Assign Holiday to a Variable/Argument */
        $this->holiday = $holidayCheck->holidays();



        $this->controls(); // Create Buttons for Previous/Next Calendars: 

        /*
         * Month Being Displayed Variable
         */
        $this->calendar[$this->index]['month'] = $this->current->format('F Y');


        /* Generate last Sunday of previous Month */
        $this->current->modify("last sun of previous month");

        /*
         * Output 6 rows (42 days) guarantees an even calendar that will
         * display nicely.
         */
        $num = 1;
        while ($num <= 6) {
            $this->drawDays(); // Grab the Current Row of Dates:
            $num += 1;
        }

        return $this->calendar;
    }

    public function generateCalendar(string $pageName = "index") {
        $this->pageName = $pageName; // The Page the Calendar is On:
        return $this->display();
    }

}
