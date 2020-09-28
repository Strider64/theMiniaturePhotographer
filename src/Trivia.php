<?php

namespace Miniature;

use PDO;
use Miniature\Database as DB;

class Trivia {

    public $schedule = null;
    
    static protected function pdo() {
        $db = DB::getInstance();
        $pdo = $db->getConnection();
        return $pdo;
    }

    static public function countAll() {
        $stmt = static::pdo()->prepare("SELECT count(*) FROM trivia_questions WHERE hidden = ?");
        $stmt->execute(['no']);
        $count = $stmt->fetchColumn();
        return $count;
    }

    static public function read() {
        $query = 'SELECT id FROM trivia_questions WHERE hidden=:hidden';
        $stmt = static::pdo()->prepare($query); // Prepare the query:
        $stmt->execute([':hidden' => 'yes']); // Execute the query with the supplied user's parameter(s):
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function resetPlaydate() {
        $this->schedule = new \DateTime("now", new \DateTimeZone("America/Detroit"));
        $this->records = static::read();
        foreach ($this->records as $record) {
            $this->query = "UPDATE trivia_questions SET play_date=:play_date, day_of_week=:day_of_week WHERE id=:id";
            $this->stmt = static::pdo()->prepare($this->query);
            $today = $this->schedule->format("Y-m-d H:i:s");
            $day_of_week = $this->schedule->format('N');
            $this->stmt->execute([':play_date' => $today, ':day_of_week' => $day_of_week, ':id' => $record['id'] ]);
        }
    }

}
