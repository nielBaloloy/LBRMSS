<?php
class EventModel {
    public static function construct_event_array($eventData, $serviceData) {
        // Construct and return event array
        return array(
            "event_id" => '',
            "service_id" => $Event['Service'],
            "client" => $new_cid,
            "date" =>  $Event['Date'],
            "date_to" =>  $Event['Date'],
            "time_from"           => $Event['TimeTo'],
            "time_to"             => $Event['TimeFrom'],
            "venue_name"          => $Event['Venue'],
            "duration"            => $Event['Duration'],
            "type"                => $type,
            "days"                => $Event['Days'],
            "venue_type"          => $Event['Venue_type'],
            "priest_assigned_id"  => $Event['Assigned_Priest']['priest_id'],
            "event_progress"      => $eventProgress,
            "requirement_status"  => $MarriageData['Status'],
            "created_at"          => $dty,
            "created_by"          => '1',
            "remark"              => '1'
        );
    }

    public static function sendSMS($eventDetails){
        
        foreach($eventDetails as $eventData){
            $client = $eventData['name'];
            $contactNumber = $eventData['contact_no'];
            $referenceNumber = $eventData['reference_no'];
            $serviceName = $eventData['event_name'];
            $eventDate = $eventData['date'];
            $eventTime = $eventData['time_from'] . ' - ' . $eventData['time_to'];
            $venue = $eventData['venue_name'];
            $amount = $eventData['amount_total'];
            $payType = ($eventData['payment_type'] == 0) ? 'Full Payment' : 'Downpayment';
            $balance = $eventData['balance'];
        
        };
        $smsContent = 
        "Good day, $client\n\n" .
        "This is to inform you that your event has been successfully scheduled with the following details:\n\n" .
        "Reference No.: $referenceNumber\n" .
        // Optional: replace with actual client name
        "Service: $serviceName\n" .
        "Date: $eventDate\n" .
        "Time: $eventTime\n" .
        "Venue: $venue\n" .
    
        "Total Amount: ₱$amount\n" .
        "Payment Type: $payType\n" .
        "Remaining Balance: ₱$balance\n\n" .
        "Please keep this message as a reference. For any inquiries,\n\n" .
        "Thank you and God bless.\n– St Raphael Parish Office\n
         This is a system generated message, do not reply ";

        $api_key = "6OmPG_kBUmHNJ2VluIdC7fL5XscHBPJenmg8"; // see https://telerivet.com/dashboard/api
        $project_id = "PJdfd97143486e7ae7";

        require_once dirname(dirname(__FILE__)) . '/model/telerivet.php';

        
        $to_number = '0' . $contactNumber;
            $content = $smsContent ;
            
            $api = new Telerivet_API($api_key);
            
            $project = $api->initProjectById($project_id);
            
            try
            {
                $contact = $project->sendMessage(array(
                    'to_number' => $to_number,
                    'content' => $content
                ));
                
                return true;
            }
            catch (Telerivet_Exception $ex)
            {
                    return false;
            }
    
       

    }

    public static function sendAlert($upcoming){
     
            
        $client =($upcoming['name'] == null) ? "Client" : $upcoming['name'];
         $date = $upcoming['date'];
            $time_from = $upcoming['time_from'];
            $time_to = $upcoming['time_to'];
            $venue = $upcoming['venue_name'];
            $event = $upcoming['event_name'];
            $contact = $upcoming['contact_no'];

      
        $smsContent = 
        "Good day, $client\n\n" .
        "This is a reminder that you have an upcoming scheduled event.\n\n" .
        "Service: $event\n" .
        
        "Date: $date \n" .
        "Time: $time_from\n" .
        "Venue: $venue\n\n" .
        "Please arrive 15–30 minutes earlier .\n\n" .
        "If you have any concerns\n\n" .
        "Thank you and God bless.\n– St Raphael Parish Office";

        $api_key = "6OmPG_kBUmHNJ2VluIdC7fL5XscHBPJenmg8"; // see https://telerivet.com/dashboard/api
        $project_id = "PJdfd97143486e7ae7";

        require_once dirname(dirname(__FILE__)) . '/model/telerivet.php';

        
        // $to_number = '0' . $contact;
        $to_number = '09369388012';
            $content = $smsContent ;
            
            $api = new Telerivet_API($api_key);
            
            $project = $api->initProjectById($project_id);
            
            try
            {
                $contact = $project->sendMessage(array(
                    'to_number' => $to_number,
                    'content' => $content
                ));
                
                return true;
            }
            catch (Telerivet_Exception $ex)
            {
                    return false;
            }
    
       

    }
}
