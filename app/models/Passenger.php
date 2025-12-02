<?php
class Passenger extends BaseModel
{
    protected $table = "tour_passenger";

    // Get all passengers for a booking
    public function getPassengersByBooking($booking_id)
    {
        $sql = "SELECT * FROM tour_passenger WHERE booking_id = ? ORDER BY passenger_id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get passenger by ID
    public function find($passenger_id)
    {
        $sql = "SELECT * FROM tour_passenger WHERE passenger_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$passenger_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Check-in a passenger
    public function checkIn($passenger_id)
    {
        $sql = "UPDATE tour_passenger SET checked_in = 1 WHERE passenger_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$passenger_id]);
    }

    // Check-out a passenger (if needed)
    public function checkOut($passenger_id)
    {
        $sql = "UPDATE tour_passenger SET checked_in = 0 WHERE passenger_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$passenger_id]);
    }

    // Get check-in status
    public function isCheckedIn($passenger_id)
    {
        $passenger = $this->find($passenger_id);
        return $passenger && $passenger['checked_in'] == 1;
    }

    // Get all passengers for a schedule with booking information
    public function getPassengersBySchedule($schedule_id)
    {
        $sql = "SELECT tp.*, b.booking_id, b.user_id as customer_id, u.full_name as customer_name
                FROM tour_passenger tp
                JOIN booking b ON tp.booking_id = b.booking_id
                JOIN users u ON b.user_id = u.user_id
                WHERE b.schedule_id = ?
                ORDER BY tp.passenger_id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$schedule_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get check-in statistics for a schedule
    public function getCheckInStats($schedule_id)
    {
        $sql = "SELECT 
                    COUNT(*) as total_passengers,
                    SUM(CASE WHEN checked_in = 1 THEN 1 ELSE 0 END) as checked_in_count,
                    SUM(CASE WHEN checked_in = 0 THEN 1 ELSE 0 END) as not_checked_in_count
                FROM tour_passenger tp
                JOIN booking b ON tp.booking_id = b.booking_id
                WHERE b.schedule_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$schedule_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update special request for a passenger
    public function updateSpecialRequest($passenger_id, $special_request)
    {
        $sql = "UPDATE tour_passenger SET special_request = ? WHERE passenger_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$special_request, $passenger_id]);
    }
    
    // Create passengers for bookings that don't have them
    public function createPassengersForBookings()
    {
        // Get bookings that don't have passengers
        $sql = "SELECT b.booking_id, b.user_id, u.full_name, s.seats_booked
                FROM booking b
                JOIN users u ON b.user_id = u.user_id
                JOIN tour_schedule s ON b.schedule_id = s.schedule_id
                WHERE b.booking_id NOT IN (SELECT DISTINCT booking_id FROM tour_passenger WHERE booking_id IS NOT NULL)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $bookingsWithoutPassengers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Create passengers for each booking
        foreach ($bookingsWithoutPassengers as $booking) {
            $booking_id = $booking['booking_id'];
            $customer_name = $booking['full_name'];
            $seats_booked = $booking['seats_booked'] > 0 ? $booking['seats_booked'] : 1;
            
            // Create passengers for this booking
            for ($i = 1; $i <= $seats_booked; $i++) {
                $passenger_name = $seats_booked > 1 ? $customer_name . " - Hành khách " . $i : $customer_name;
                $sql = "INSERT INTO tour_passenger (booking_id, full_name, age, type, checked_in) 
                        VALUES (?, ?, 25, 'adult', 0)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$booking_id, $passenger_name]);
            }
        }
        
        return count($bookingsWithoutPassengers);
    }
}