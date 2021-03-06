
<?php

class Helper{
    
    public static function arrayToObject(array $array, $className) {
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($className),
            $className,
            strstr(serialize($array), ':')
        ));
    }
    
    
    public static function get_distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
    
    public static function objectToObject($instance, $className) {
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($className),
            $className,
            strstr(strstr(serialize($instance), '"'), ':')
        ));
    }


    public static function redirect_to($url){
        header('Location: ' . $url);
    }

    public static function is_post(){
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function is_get(){
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function post_val($val){
        return (isset($_POST[$val]) && (!empty($_POST[$val]))) ? trim($_POST[$val]) : null;
    }

    public static function get_val($val){
        return (isset($_GET[$val]) && (!empty($_GET[$val]))) ? trim($_GET[$val]) : null;
    }

    public static function validateEmail($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Invalid Email";
        else return null;
    }

    public static function invalid_length($key, $value, $length){
        if(strlen($value) < $length)  return ucfirst($key) . ' must be at least ' . $length . ' char long.';
        else return null;
    }

    public static function unique_code($limit){
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    public static function unique_numeric_code($limit){
        return substr(uniqid(mt_rand()), 0, $limit);
    }

    public static function format_address($address){
        $formatted_address = "";
        if(empty($address)) $formatted_address = "Unknown";
        else{
            $address->id = null;
            $address->user = null;
            foreach ($address as $key => $value){
                if(!empty($value)) {
                    $formatted_address .=  $value . ", ";
                }
            }
        }
        return rtrim($formatted_address, ", ");
    }

    public static function format_time($time){
        $date = date_create($time);
        return date_format($date, 'g:ia, M j, Y');
    }

}