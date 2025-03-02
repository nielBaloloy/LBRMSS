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
}
